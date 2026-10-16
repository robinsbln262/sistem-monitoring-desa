<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\Proyek;
use App\Models\RekananProyek;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan data monitoring yang sudah di-group berdasarkan proyek
     * dengan filter akses rekanan
     */
    public function index()
    {
        // Ambil semua proyek yang disetujui rekanan yang sedang login
        $proyeks = Proyek::whereHas('rekananProyek', function ($q) {
            $q->where('id_rekanan', Auth::id())
                ->where('status', 'setuju');
        })->with(['monitorings' => function ($query) {
            $query->orderBy('tanggal_monitoring', 'desc');
        }])->get();

        // Group data - hanya proyek yang memiliki riwayat monitoring
        $groupedMonitorings = [];
        foreach ($proyeks as $proyek) {
            if ($proyek->monitorings->count() > 0) {
                $groupedMonitorings[] = [
                    'pembangunan' => $proyek,
                    'riwayat' => $proyek->monitorings,
                    'progres_terbaru' => $proyek->monitorings->first()?->progres ?? 0,
                ];
            }
        }

        // Untuk dropdown di modal tambah (proyek yang disetujui)
        $allPembangunans = Proyek::whereHas('rekananProyek', function ($q) {
            $q->where('id_rekanan', Auth::id())
                ->where('status', 'setuju');
        })->whereIn('status', ['direncanakan', 'berjalan'])->get();

        return view('monitoring.index', compact('groupedMonitorings', 'allPembangunans'));
    }

        /**
     * ADMIN - Display all monitoring data for admin
     * MENGGUNAKAN SERVER-SIDE PAGINATION
     */
    public function indexAdmin(Request $request)
    {
        if (Auth::user()->level != 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // =====================================================
        // FILTER STATUS VERIFIKASI
        // =====================================================
        $filterStatus = $request->input('status_verifikasi', 'all');

        // =====================================================
        // QUERY PROYEK YANG PUNYA MONITORING
        // =====================================================
        $query = Proyek::with(['monitorings' => function ($q) use ($filterStatus) {
            $q->orderBy('tanggal_monitoring', 'desc');

            if ($filterStatus !== 'all') {
                $q->where('status_verifikasi', $filterStatus);
            }
        }])
            ->whereHas('monitorings', function ($q) use ($filterStatus) {
                if ($filterStatus !== 'all') {
                    $q->where('status_verifikasi', $filterStatus);
                }
            })
            ->orderBy('created_at', 'desc');

        // =====================================================
        // PAGINATION: 10 PROYEK PER HALAMAN
        // =====================================================
        $proyeks = $query->paginate(10)->withQueryString();

        // =====================================================
        // GROUP DATA (untuk kompatibilitas view)
        // =====================================================
        $groupedMonitorings = [];
        foreach ($proyeks as $proyek) {
            if ($proyek->monitorings->count() > 0) {
                $groupedMonitorings[] = [
                    'pembangunan'     => $proyek,
                    'riwayat'         => $proyek->monitorings,
                    'progres_terbaru' => $proyek->monitorings->first()?->progres ?? 0,
                ];
            }
        }

        // =====================================================
        // STATISTIK UNTUK BADGE
        // =====================================================
        $pendingCount = Monitoring::pending()->count();

        return view('monitoring.admin', compact(
            'groupedMonitorings',
            'proyeks',
            'pendingCount',
            'filterStatus'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data monitoring baru dan update status proyek
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pembangunan' => 'required|exists:proyek,id_pembangunan',
            'tanggal_monitoring' => 'required|date',
            'progres' => 'required|integer|min:0|max:100',
            'status_progres' => 'required|max:50',
            'keterangan' => 'nullable',
            'foto_before' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_after' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'dana_terpakai' => 'nullable|numeric|min:0',
        ]);

        // Cek apakah rekanan berhak monitoring proyek ini
        $cek = RekananProyek::where('id_proyek', $request->id_pembangunan)
            ->where('id_rekanan', Auth::id())
            ->where('status', 'setuju')
            ->exists();

        if (! $cek) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke proyek ini!');
        }

        DB::transaction(function () use ($request) {
            $data = [
                'id_pembangunan' => $request->id_pembangunan,
                'tanggal_monitoring' => $request->tanggal_monitoring,
                'progres' => $request->progres,
                'status_progres' => $request->status_progres,
                'keterangan' => $request->keterangan,
                'dana_terpakai' => $request->dana_terpakai,
                'status_verifikasi' => Monitoring::STATUS_PENDING, // TAMBAHKAN INI
            ];

            // Upload foto before langsung ke public/uploads
            if ($request->hasFile('foto_before')) {
                $data['foto_before'] = $this->storeMonitoringPhoto(
                    $request->file('foto_before'),
                    'before'
                );
            }

            // Upload foto after langsung ke public/uploads
            if ($request->hasFile('foto_after')) {
                $data['foto_after'] = $this->storeMonitoringPhoto(
                    $request->file('foto_after'),
                    'after'
                );
            }

            // Simpan data monitoring
            Monitoring::create($data);

            // Update status proyek berdasarkan progres
            $proyek = Proyek::find($request->id_pembangunan);
            if ($request->progres == 100) {
                $proyek->update(['status' => 'selesai']);
            } elseif ($request->progres > 0 && $proyek->status == 'direncanakan') {
                $proyek->update(['status' => 'berjalan']);
            }
        });

        return redirect()->route('monitoring.index')->with('success', 'Data monitoring berhasil ditambahkan! Menunggu verifikasi Admin.');
    }

    /**
     * Display the specified resource for AJAX detail.
     * Untuk modal detail monitoring
     */
    public function show($id)
    {
        try {
            $monitoring = Monitoring::with('proyek')->findOrFail($id);

            // Cek akses rekanan
            $this->authorizeAccess($monitoring->id_pembangunan);

            return response()->json($monitoring);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * Update data monitoring dan status proyek
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pembangunan' => 'required|exists:proyek,id_pembangunan',
            'tanggal_monitoring' => 'required|date',
            'progres' => 'required|integer|min:0|max:100',
            'status_progres' => 'required|max:50',
            'keterangan' => 'nullable',
            'foto_before' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_after' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'dana_terpakai' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {
            $monitoring = Monitoring::findOrFail($id);

            // Cek akses rekanan
            $this->authorizeAccess($monitoring->id_pembangunan);

            // CEK: Tidak boleh mengedit jika sudah approved
            if ($monitoring->isApproved()) {
                throw new \Exception('Data monitoring sudah disetujui dan tidak dapat diedit!');
            }

            $data = [
                'id_pembangunan' => $request->id_pembangunan,
                'tanggal_monitoring' => $request->tanggal_monitoring,
                'progres' => $request->progres,
                'status_progres' => $request->status_progres,
                'keterangan' => $request->keterangan,
                'dana_terpakai' => $request->dana_terpakai,
            ];

            // Upload foto before baru
            if ($request->hasFile('foto_before')) {
                $this->deleteMonitoringPhoto($monitoring->foto_before);

                $data['foto_before'] = $this->storeMonitoringPhoto(
                    $request->file('foto_before'),
                    'before'
                );
            }

            // Upload foto after baru
            if ($request->hasFile('foto_after')) {
                $this->deleteMonitoringPhoto($monitoring->foto_after);

                $data['foto_after'] = $this->storeMonitoringPhoto(
                    $request->file('foto_after'),
                    'after'
                );
            }

            // Jika status sebelumnya rejected, reset ke pending setelah diupdate
            if ($monitoring->isRejected()) {
                $data['status_verifikasi'] = Monitoring::STATUS_PENDING;
                $data['catatan_verifikasi'] = null;
            }

            $monitoring->update($data);

            // Update status proyek berdasarkan progres
            $proyek = Proyek::find($request->id_pembangunan);
            if ($request->progres == 100) {
                $proyek->update(['status' => 'selesai']);
            } elseif ($request->progres > 0 && $proyek->status == 'direncanakan') {
                $proyek->update(['status' => 'berjalan']);
            }
        });

        return redirect()->route('monitoring.index')->with('success', 'Data monitoring berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     * Hapus data monitoring
     */
    public function destroy($id)
    {
        try {
            $monitoring = Monitoring::findOrFail($id);

            // Cek akses rekanan
            $this->authorizeAccess($monitoring->id_pembangunan);

            // CEK: Tidak boleh menghapus jika sudah approved
            if ($monitoring->isApproved()) {
                return redirect()->back()->with('error', 'Data monitoring sudah disetujui dan tidak dapat dihapus!');
            }

            // Hapus file foto, baik file baru di public/uploads
            // maupun foto lama yang masih memakai disk public.
            $this->deleteMonitoringPhoto($monitoring->foto_before);
            $this->deleteMonitoringPhoto($monitoring->foto_after);

            $monitoring->delete();

            return redirect()->route('monitoring.index')->with('success', 'Data monitoring berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    /**
     * Get monitoring data for edit (AJAX)
     * Untuk mengambil data monitoring yang akan diedit
     */
    public function getData($id)
    {
        try {
            $monitoring = Monitoring::with('proyek')->findOrFail($id);

            // Cek akses rekanan
            $this->authorizeAccess($monitoring->id_pembangunan);

            return response()->json([
                'id_monitoring' => $monitoring->id_monitoring,
                'id_pembangunan' => $monitoring->id_pembangunan,
                'tanggal_monitoring' => $monitoring->tanggal_monitoring,
                'tanggal_monitoring_formatted' => date('Y-m-d', strtotime($monitoring->tanggal_monitoring)),
                'progres' => $monitoring->progres,
                'status_progres' => $monitoring->status_progres,
                'keterangan' => $monitoring->keterangan,
                'komentar_admin' => $monitoring->komentar_admin,
                'foto_before' => $monitoring->foto_before,
                'foto_after' => $monitoring->foto_after,
                'dana_terpakai' => $monitoring->dana_terpakai,
                'proyek' => $monitoring->proyek,
                'status_verifikasi' => $monitoring->status_verifikasi, // TAMBAHKAN INI
                'catatan_verifikasi' => $monitoring->catatan_verifikasi, // TAMBAHKAN INI
                'is_approved' => $monitoring->isApproved(), // TAMBAHKAN INI
                'is_editable' => $monitoring->isEditable(), // TAMBAHKAN INI
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Get all monitoring for a specific proyek (AJAX)
     * Untuk menampilkan semua riwayat monitoring dari satu proyek
     * Bisa diakses oleh Admin dan Rekanan
     */
    public function getByPembangunan($id)
    {
        try {
            $proyek = Proyek::findOrFail($id);

            // Admin boleh melihat semua proyek.
            // Rekanan hanya boleh melihat proyek miliknya.
            $isAdmin = Auth::user()->level === 'admin';

            if (! $isAdmin) {
                $this->authorizeAccess($id);
            }

            // Untuk publik (non-admin), hanya tampilkan yang approved
            $monitorings = Monitoring::where('id_pembangunan', $id);

            if (! $isAdmin) {
                $monitorings->where('status_verifikasi', Monitoring::STATUS_APPROVED);
            }

            $monitorings = $monitorings->orderBy('tanggal_monitoring', 'desc')
                ->orderBy('id_monitoring', 'desc')
                ->get();

            $formattedMonitorings = $monitorings->map(function ($m) {
                return [
                    'id_monitoring' => $m->id_monitoring,
                    'tanggal_monitoring' => $m->tanggal_monitoring ? date('Y-m-d', strtotime($m->tanggal_monitoring)) : null,
                    'progres' => (int) $m->progres,
                    'status_progres' => $m->status_progres,
                    'keterangan' => $m->keterangan,
                    'komentar_admin' => $m->komentar_admin,
                    'foto_before' => $m->foto_before,
                    'foto_after' => $m->foto_after,
                    'dana_terpakai' => $m->dana_terpakai,
                    'created_at' => $m->created_at ? $m->created_at->format('Y-m-d H:i:s') : null,
                    'status_verifikasi' => $m->status_verifikasi, // TAMBAHKAN INI
                    'catatan_verifikasi' => $m->catatan_verifikasi, // TAMBAHKAN INI
                ];
            })->values();

            return response()->json([
                'success' => true,
                'pembangunan' => [
                    'id_pembangunan' => $proyek->id_pembangunan,
                    'nama_pembangunan' => $proyek->nama_pembangunan,
                    'lokasi' => $proyek->lokasi,
                    'status' => $proyek->status,
                    'progress' => $monitorings->first() ? (int) $monitorings->first()->progres : 0,
                ],
                'monitorings' => $formattedMonitorings,
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Data proyek tidak ditemukan.',
            ], 404);

        } catch (\Throwable $e) {
            \Log::error('Gagal mengambil riwayat monitoring', [
                'id_pembangunan' => $id,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengambil riwayat monitoring.',
            ], 500);
        }
    }

    /**
     * Upload image from summernote/editor
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            $image = $request->file('image');
            $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('uploads/monitoring/images', $filename, 'public');

            return response()->json([
                'success' => true,
                'url' => asset('storage/'.$path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * ADMIN - Beri komentar pada monitoring
     */
    public function komentar(Request $request, $id)
    {
        // Cek apakah user adalah admin
        if (Auth::user()->level != 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'komentar_admin' => 'required|min:5',
        ]);

        $monitoring = Monitoring::findOrFail($id);
        $monitoring->komentar_admin = $request->komentar_admin;
        $monitoring->save();

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    // ==========================================
    // METHOD VERIFIKASI (BARU)
    // ==========================================

    /**
     * ADMIN - Daftar monitoring yang pending verifikasi
     */
    public function pendingVerifikasi()
    {
        if (Auth::user()->level != 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil semua monitoring dengan status pending
        $pendingMonitorings = Monitoring::with(['proyek', 'proyek.user'])
            ->where('status_verifikasi', Monitoring::STATUS_PENDING)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        // Statistik
        $stats = [
            'pending' => Monitoring::pending()->count(),
            'approved' => Monitoring::approved()->count(),
            'rejected' => Monitoring::rejected()->count(),
            'total' => Monitoring::count(),
        ];

        return view('admin.monitoring-verifikasi', compact('pendingMonitorings', 'stats'));
    }

    /**
     * ADMIN - Setujui monitoring
     */
    public function approve(Request $request, $id)
    {
        if (Auth::user()->level != 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        try {
            $monitoring = Monitoring::findOrFail($id);

            // Cek apakah sudah diverifikasi
            if ($monitoring->isVerified()) {
                return redirect()->back()->with('error', 'Data ini sudah diverifikasi sebelumnya.');
            }

            // Approve monitoring
            $monitoring->approve($request->catatan_verifikasi);

            // Update status proyek jika perlu
            $proyek = $monitoring->proyek;
            if ($monitoring->progres == 100) {
                $proyek->update(['status' => 'selesai']);
            } elseif ($monitoring->progres > 0 && $proyek->status == 'direncanakan') {
                $proyek->update(['status' => 'berjalan']);
            }

            \Log::info('Monitoring disetujui oleh admin', [
                'id_monitoring' => $id,
                'admin' => Auth::user()->nama_user,
                'proyek' => $proyek->nama_pembangunan,
            ]);

            return redirect()->back()->with('success', 'Monitoring berhasil disetujui!');

        } catch (\Exception $e) {
            \Log::error('Gagal approve monitoring: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyetujui monitoring: '.$e->getMessage());
        }
    }

    /**
     * ADMIN - Tolak monitoring dengan catatan
     */
    public function reject(Request $request, $id)
    {
        if (Auth::user()->level != 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'catatan_verifikasi' => 'required|string|min:5|max:500',
        ]);

        try {
            $monitoring = Monitoring::findOrFail($id);

            // Cek apakah sudah diverifikasi
            if ($monitoring->isVerified()) {
                return redirect()->back()->with('error', 'Data ini sudah diverifikasi sebelumnya.');
            }

            // Reject monitoring dengan catatan
            $monitoring->reject($request->catatan_verifikasi);

            \Log::info('Monitoring ditolak oleh admin', [
                'id_monitoring' => $id,
                'admin' => Auth::user()->nama_user,
                'catatan' => $request->catatan_verifikasi,
            ]);

            return redirect()->back()->with('success', 'Monitoring berhasil ditolak! Catatan sudah dikirim ke rekanan.');

        } catch (\Exception $e) {
            \Log::error('Gagal reject monitoring: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menolak monitoring: '.$e->getMessage());
        }
    }

    /**
     * ADMIN - Get data monitoring untuk verifikasi (AJAX)
     */
    public function getVerifikasiData($id)
    {
        if (Auth::user()->level != 'admin') {
            abort(403);
        }

        try {
            $monitoring = Monitoring::with('proyek')->findOrFail($id);

            return response()->json([
                'id_monitoring' => $monitoring->id_monitoring,
                'id_pembangunan' => $monitoring->id_pembangunan,
                'nama_pembangunan' => $monitoring->proyek->nama_pembangunan ?? '-',
                'tanggal_monitoring' => $monitoring->tanggal_monitoring->format('d F Y'),
                'progres' => $monitoring->progres,
                'status_progres' => $monitoring->status_progres,
                'keterangan' => $monitoring->keterangan,
                'dana_terpakai' => $monitoring->dana_terpakai,
                'foto_before' => $monitoring->foto_before,
                'foto_after' => $monitoring->foto_after,
                'komentar_admin' => $monitoring->komentar_admin,
                'created_at' => $monitoring->created_at->format('d F Y H:i'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    /**
     * Cek akses rekanan untuk proyek tertentu
     * Method helper untuk validasi akses
     */
    private function authorizeAccess($proyekId)
    {
        $hasAccess = RekananProyek::where('id_proyek', $proyekId)
            ->where('id_rekanan', Auth::id())
            ->where('status', 'setuju')
            ->exists();

        if (! $hasAccess) {
            throw new \Exception('Anda tidak memiliki akses ke proyek ini!');
        }
    }

    /**
     * Methods yang tidak digunakan (karena menggunakan modal)
     * Tetapi tetap disediakan untuk kompatibilitas
     */
    public function create()
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    /**
     * Simpan foto monitoring langsung ke public/uploads.
     * Tidak membutuhkan storage:link.
     */
    private function storeMonitoringPhoto($file, string $folder): string
    {
        $directory = public_path('uploads/monitoring/'.$folder);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

        $file->move($directory, $filename);

        return 'uploads/monitoring/'.$folder.'/'.$filename;
    }

    /**
     * Hapus foto baik dari format baru public/uploads maupun
     * format lama Laravel public disk.
     */
    private function deleteMonitoringPhoto(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        // Format baru: uploads/monitoring/...
        if (str_starts_with($normalized, 'uploads/')) {
            $publicFile = public_path($normalized);

            if (is_file($publicFile)) {
                @unlink($publicFile);
            }

            return;
        }

        // Format lama: monitoring_before/... / monitoring_after/...
        Storage::disk('public')->delete($normalized);
    }
}
