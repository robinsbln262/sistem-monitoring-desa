<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiRekanan;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EvaluasiRekananController extends Controller
{
    // =====================================================
    // REKANAN - DAFTAR PROYEK YANG PERLU DIEVALUASI
    // =====================================================
    public function index()
    {
        if (Auth::user()->level !== 'rekanan') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $proyeks = Proyek::whereHas('rekananProyek', function ($q) {
            $q->where('id_rekanan', Auth::id())
                ->where(function ($r) {
                    $r->where('status_penawaran', 'diterima')
                        ->orWhere('status', 'setuju');
                });
        })
            ->where('status', 'selesai')
            ->with(['evaluasiRekanan' => function ($q) {
                $q->where('id_rekanan', Auth::id());
            }])
            ->latest()
            ->paginate(10);

        $riwayatEvaluasi = EvaluasiRekanan::with('proyek')
            ->where('id_rekanan', Auth::id())
            ->latest()
            ->get();

        $totalEvaluasi = $riwayatEvaluasi->count();
        $totalApproved = $riwayatEvaluasi->where('status_verifikasi', 'approved')->count();
        $totalPending  = $riwayatEvaluasi->where('status_verifikasi', 'pending')->count();
        $rataRating    = $riwayatEvaluasi->where('status_verifikasi', 'approved')->avg('rating') ?? 0;

        return view('evaluasi-rekanan.index', compact(
            'proyeks',
            'riwayatEvaluasi',
            'totalEvaluasi',
            'totalApproved',
            'totalPending',
            'rataRating'
        ));
    }

    // =====================================================
    // REKANAN - FORM EVALUASI
    // =====================================================
    public function create($proyekId)
    {
        if (Auth::user()->level !== 'rekanan') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $proyek = Proyek::with(['rekananProyek.rekanan'])->findOrFail($proyekId);

        if ($proyek->status !== 'selesai') {
            return redirect()
                ->route('evaluasi-rekanan.index')
                ->with('error', 'Evaluasi hanya bisa diisi untuk proyek yang sudah selesai.');
        }

        $isPemenang = $proyek->rekananProyek
            ->where('id_rekanan', Auth::id())
            ->where('status_penawaran', 'diterima')
            ->isNotEmpty();

        if (! $isPemenang) {
            abort(403, 'Anda bukan pelaksana proyek ini.');
        }

        $existing = EvaluasiRekanan::where('id_pembangunan', $proyekId)
            ->where('id_rekanan', Auth::id())
            ->first();

        if ($existing) {
            return redirect()
                ->route('evaluasi-rekanan.edit', $existing->id_evaluasi_rekanan)
                ->with('info', 'Anda sudah pernah mengisi evaluasi untuk proyek ini.');
        }

        return view('evaluasi-rekanan.create', compact('proyek'));
    }

    // =====================================================
    // REKANAN - SIMPAN EVALUASI
    // =====================================================
    public function store(Request $request)
    {
        // Log untuk debug
        Log::info('=== EVALUASI REKANAN STORE DIPANGGIL ===', [
            'user_id' => Auth::id(),
            'level'   => Auth::user()->level ?? 'guest',
            'input'   => $request->all(),
        ]);

        if (Auth::user()->level !== 'rekanan') {
            Log::warning('Bukan rekanan, akses ditolak');
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validate([
            'id_pembangunan'          => 'required|exists:proyek,id_pembangunan',
            'tanggal_evaluasi'        => 'required|date',
            'selesai_tepat_waktu'     => 'required|in:0,1',
            'keterlambatan_hari'      => 'nullable|integer|min:0',
            'kendala'                 => 'required|string|min:10',
            'hasil_akhir'             => 'required|string|min:10',
            'kualitas_pekerjaan'      => 'required|in:sangat_baik,baik,cukup,kurang',
            'persentase_penyelesaian' => 'required|integer|min:0|max:100',
            'rating'                  => 'required|integer|min:1|max:5',
            'rekomendasi_rekanan'     => 'nullable|string',
        ]);

        Log::info('Validasi BERHASIL', $validated);

        $proyek = Proyek::findOrFail($request->id_pembangunan);

        if ($proyek->status !== 'selesai') {
            return back()->with('error', 'Proyek belum selesai.');
        }

        $isPemenang = $proyek->rekananProyek
            ->where('id_rekanan', Auth::id())
            ->where('status_penawaran', 'diterima')
            ->isNotEmpty();

        if (! $isPemenang) {
            abort(403, 'Anda bukan pelaksana proyek ini.');
        }

        try {
            $evaluasi = EvaluasiRekanan::create([
                'id_pembangunan'          => $request->id_pembangunan,
                'id_rekanan'              => Auth::id(),
                'tanggal_evaluasi'        => $request->tanggal_evaluasi,
                'selesai_tepat_waktu'     => (bool) $request->selesai_tepat_waktu,
                'keterlambatan_hari'      => $request->selesai_tepat_waktu ? 0 : ($request->keterlambatan_hari ?? 0),
                'kendala'                 => $request->kendala,
                'hasil_akhir'             => $request->hasil_akhir,
                'kualitas_pekerjaan'      => $request->kualitas_pekerjaan,
                'persentase_penyelesaian' => $request->persentase_penyelesaian,
                'rating'                  => $request->rating,
                'rekomendasi_rekanan'     => $request->rekomendasi_rekanan,
                'status_verifikasi'       => EvaluasiRekanan::STATUS_PENDING,
            ]);

            Log::info('Evaluasi BERHASIL DISIMPAN', [
                'id_evaluasi_rekanan' => $evaluasi->id_evaluasi_rekanan,
            ]);

            return redirect()
                ->route('evaluasi-rekanan.index')
                ->with('success', 'Evaluasi berhasil dikirim! Menunggu verifikasi Admin/Kepala Desa.');

        } catch (\Exception $e) {
            Log::error('GAGAL SIMPAN EVALUASI', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan evaluasi: ' . $e->getMessage());
        }
    }

    // =====================================================
    // REKANAN - FORM EDIT
    // =====================================================
    public function edit($id)
    {
        if (Auth::user()->level !== 'rekanan') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $evaluasi = EvaluasiRekanan::with('proyek')->findOrFail($id);

        if ($evaluasi->id_rekanan !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengedit evaluasi ini.');
        }

        if ($evaluasi->status_verifikasi === EvaluasiRekanan::STATUS_APPROVED) {
            return redirect()
                ->route('evaluasi-rekanan.index')
                ->with('error', 'Evaluasi yang sudah disetujui tidak dapat diedit.');
        }

        return view('evaluasi-rekanan.edit', compact('evaluasi'));
    }

    // =====================================================
    // REKANAN - UPDATE
    // =====================================================
    public function update(Request $request, $id)
    {
        if (Auth::user()->level !== 'rekanan') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $evaluasi = EvaluasiRekanan::findOrFail($id);

        if ($evaluasi->id_rekanan !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengedit evaluasi ini.');
        }

        if ($evaluasi->status_verifikasi === EvaluasiRekanan::STATUS_APPROVED) {
            return back()->with('error', 'Evaluasi yang sudah disetujui tidak dapat diedit.');
        }

        $request->validate([
            'tanggal_evaluasi'        => 'required|date',
            'selesai_tepat_waktu'     => 'required|in:0,1',
            'keterlambatan_hari'      => 'nullable|integer|min:0',
            'kendala'                 => 'required|string|min:10',
            'hasil_akhir'             => 'required|string|min:10',
            'kualitas_pekerjaan'      => 'required|in:sangat_baik,baik,cukup,kurang',
            'persentase_penyelesaian' => 'required|integer|min:0|max:100',
            'rating'                  => 'required|integer|min:1|max:5',
            'rekomendasi_rekanan'     => 'nullable|string',
        ]);

        $evaluasi->update([
            'tanggal_evaluasi'        => $request->tanggal_evaluasi,
            'selesai_tepat_waktu'     => (bool) $request->selesai_tepat_waktu,
            'keterlambatan_hari'      => $request->selesai_tepat_waktu ? 0 : ($request->keterlambatan_hari ?? 0),
            'kendala'                 => $request->kendala,
            'hasil_akhir'             => $request->hasil_akhir,
            'kualitas_pekerjaan'      => $request->kualitas_pekerjaan,
            'persentase_penyelesaian' => $request->persentase_penyelesaian,
            'rating'                  => $request->rating,
            'rekomendasi_rekanan'     => $request->rekomendasi_rekanan,
            'status_verifikasi'       => EvaluasiRekanan::STATUS_PENDING,
            'catatan_verifikasi'      => null,
        ]);

        return redirect()
            ->route('evaluasi-rekanan.index')
            ->with('success', 'Evaluasi berhasil diperbarui.');
    }

    // =====================================================
    // DETAIL EVALUASI
    // =====================================================
    public function show($id)
    {
        $evaluasi = EvaluasiRekanan::with(['proyek', 'rekanan', 'verifikator'])
            ->findOrFail($id);

        if (Auth::user()->level === 'rekanan' && $evaluasi->id_rekanan !== Auth::id()) {
            abort(403);
        }

        return view('evaluasi-rekanan.show', compact('evaluasi'));
    }

    // =====================================================
    // ADMIN / KEPALA DESA - DAFTAR SEMUA EVALUASI
    // =====================================================
    public function adminIndex(Request $request)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $query = EvaluasiRekanan::with(['proyek', 'rekanan', 'verifikator']);

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('rekanan')) {
            $query->where('id_rekanan', $request->rekanan);
        }

        if ($request->filled('tepat_waktu')) {
            $query->where('selesai_tepat_waktu', $request->tepat_waktu == '1');
        }

        $evaluasis = $query->latest()->paginate(15)->withQueryString();

        $rekananList = User::where('level', 'rekanan')->orderBy('nama_user')->get();

        $totalEvaluasi   = EvaluasiRekanan::count();
        $totalPending    = EvaluasiRekanan::pending()->count();
        $totalApproved   = EvaluasiRekanan::approved()->count();
        $totalTepatWaktu = EvaluasiRekanan::approved()->tepatWaktu()->count();
        $rataRating      = EvaluasiRekanan::approved()->avg('rating') ?? 0;

        return view('evaluasi-rekanan.admin-index', compact(
            'evaluasis',
            'rekananList',
            'totalEvaluasi',
            'totalPending',
            'totalApproved',
            'totalTepatWaktu',
            'rataRating'
        ));
    }

    // =====================================================
    // ADMIN / KEPALA DESA - VERIFIKASI
    // =====================================================
    public function verifikasi(Request $request, $id)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'status_verifikasi'  => 'required|in:approved,rejected',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $evaluasi = EvaluasiRekanan::findOrFail($id);

        $evaluasi->update([
            'status_verifikasi'  => $request->status_verifikasi,
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'diverifikasi_oleh'  => Auth::id(),
            'tanggal_verifikasi' => now(),
        ]);

        $msg = $request->status_verifikasi === 'approved'
            ? 'Evaluasi berhasil disetujui.'
            : 'Evaluasi ditolak.';

        return back()->with('success', $msg);
    }

    // =====================================================
    // ADMIN / KEPALA DESA - KINERJA REKANAN
    // =====================================================
    public function kinerjaRekanan($rekananId)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $rekanan = User::findOrFail($rekananId);

        if ($rekanan->level !== 'rekanan') {
            abort(404);
        }

        $evaluasis = EvaluasiRekanan::with('proyek')
            ->where('id_rekanan', $rekananId)
            ->where('status_verifikasi', EvaluasiRekanan::STATUS_APPROVED)
            ->latest()
            ->get();

        $statistik = [
            'total_proyek'           => $evaluasis->count(),
            'total_tepat_waktu'      => $evaluasis->where('selesai_tepat_waktu', true)->count(),
            'total_terlambat'        => $evaluasis->where('selesai_tepat_waktu', false)->count(),
            'rata_rating'            => $evaluasis->avg('rating') ?? 0,
            'skor_kredibilitas'      => $rekanan->skor_kredibilitas,
            'predikat'               => $rekanan->predikat_kredibilitas,
            'persentase_tepat_waktu' => $rekanan->persentase_tepat_waktu,
        ];

        return view('evaluasi-rekanan.kinerja-rekanan', compact(
            'rekanan',
            'evaluasis',
            'statistik'
        ));
    }

    // =====================================================
    // ADMIN / KEPALA DESA - KREDIBILITAS REKANAN
    // =====================================================
    public function kredibilitasRekanan()
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $rekanans = User::where('level', 'rekanan')
            ->withCount(['evaluasiRekananApproved as total_evaluasi'])
            ->get()
            ->map(function ($r) {
                $r->skor        = $r->skor_kredibilitas;
                $r->predikat    = $r->predikat_kredibilitas;
                $r->rata_rating = $r->rata_rating;
                $r->tepat_waktu = $r->persentase_tepat_waktu;
                return $r;
            })
            ->sortByDesc('skor')
            ->values();

        return view('evaluasi-rekanan.kredibilitas-rekanan', compact('rekanans'));
    }
}