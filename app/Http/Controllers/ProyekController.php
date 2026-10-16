<?php

namespace App\Http\Controllers;

use App\Mail\PenawaranStatusMail;
use App\Models\Notification;
use App\Models\Proyek;
use App\Models\RekananProyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mpdf\Mpdf;

class ProyekController extends Controller
{
    // =========================================================
    // ADMIN - INDEX
    // =========================================================

    public function index()
    {
        if (Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $proyeks = Proyek::with([
            'user',
            'monitorings' => function ($query) {
                $query->latest('tanggal_monitoring');
            },
            'rekananProyek.rekanan',
            'pengeluarans',
        ])
            ->latest()
            ->paginate(10);

        return view('proyek.index', compact('proyeks'));
    }

    // =========================================================
    // CREATE
    // =========================================================

    public function create()
    {
        if (Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('proyek.create');
    }

    // =========================================================
    // STORE - TAMBAH PROYEK
    // =========================================================

    public function store(Request $request)
    {
        if (Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'nama_pembangunan' => 'required|max:150',
            'lokasi' => 'required|max:150',
            'tahun_anggaran' => 'required|digits:4',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'sumber_dana' => 'required|max:100',
            'anggaran' => 'required|numeric|min:0',

            // =================================================
            // DIMENSI PROYEK
            // =================================================
            'panjang' => 'required|numeric|min:0.01',
            'lebar' => 'required|numeric|min:0.01',
            'ketebalan_tinggi' => 'required|numeric|min:0.01',
            'satuan_ketebalan' => 'required|in:m,cm',

            'satuan_volume' => 'required|max:50',

            'harga_penawaran' => 'nullable|numeric|min:0',

            'batas_penawaran' => 'nullable|date|after_or_equal:today',

            'status' => 'required|in:direncanakan,berjalan,selesai',

            'bidang_pekerjaan' => 'nullable|max:100',

            'catatan' => 'nullable|string',
        ]);

        // =====================================================
        // HITUNG VOLUME OTOMATIS
        // PANJANG × LEBAR × KETEBALAN/TINGGI
        // =====================================================

        // Ketebalan/tinggi selalu disimpan dalam METER di database.
        // Jika user memilih CM, konversi terlebih dahulu.
        $ketebalanDalamMeter = (float) $request->ketebalan_tinggi;

        if ($request->satuan_ketebalan === 'cm') {
            $ketebalanDalamMeter /= 100;
        }

        $volume = round(
            (float) $request->panjang *
            (float) $request->lebar *
            $ketebalanDalamMeter,
            2
        );

        // =====================================================
        // BUAT PROYEK
        // =====================================================

        $proyek = new Proyek;

        $proyek->id_user = Auth::id();

        $proyek->nama_pembangunan = $request->nama_pembangunan;

        $proyek->lokasi = $request->lokasi;

        $proyek->tahun_anggaran = $request->tahun_anggaran;

        $proyek->tanggal_mulai = $request->tanggal_mulai;

        $proyek->tanggal_selesai = $request->tanggal_selesai;

        $proyek->sumber_dana = $request->sumber_dana;

        $proyek->anggaran = $request->anggaran;

        // =====================================================
        // DIMENSI
        // =====================================================

        $proyek->panjang = $request->panjang;

        $proyek->lebar = $request->lebar;

        $proyek->ketebalan_tinggi = $ketebalanDalamMeter;

        // =====================================================
        // VOLUME OTOMATIS
        // =====================================================

        $proyek->volume_proyek = $volume;

        $proyek->satuan_volume = $request->satuan_volume;

        // =====================================================
        // PENAWARAN
        // =====================================================

        $proyek->harga_penawaran = $request->harga_penawaran;

        $proyek->batas_penawaran = $request->batas_penawaran;

        // =====================================================
        // DATA LAIN
        // =====================================================

        $proyek->status = $request->status;

        $proyek->bidang_pekerjaan = $request->bidang_pekerjaan;

        $proyek->catatan = $request->catatan;

        $proyek->is_published = true;

        $proyek->status_penawaran = 'open';

        $proyek->save();

        return redirect()
            ->route('proyek.index')
            ->with(
                'success',
                'Proyek berhasil ditambahkan!'
            );
    }

    // =========================================================
    // SHOW
    // =========================================================

    public function show($id)
    {
        if (Auth::user()->level !== 'admin') {
            return redirect()
                ->route('publik.show', $id);
        }

        $proyek = Proyek::with([
            'monitorings',
            'evaluasis',
            'dokumentasis',
            'user',
            'rekananProyek.rekanan',
            'pengeluarans',
        ])->findOrFail($id);

        if (request()->ajax()) {
            return response()->json($proyek);
        }

        return view(
            'proyek.show',
            compact('proyek')
        );
    }

    // =========================================================
    // EDIT
    // =========================================================

    public function edit($id)
    {
        if (Auth::user()->level !== 'admin') {
            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        $proyek = Proyek::findOrFail($id);

        if (request()->ajax()) {
            return response()->json($proyek);
        }

        return view(
            'proyek.edit',
            compact('proyek')
        );
    }

    // =========================================================
    // GET DATA AJAX EDIT
    // =========================================================

    public function getData($id)
    {
        if (Auth::user()->level !== 'admin') {
            return response()->json(
                ['error' => 'Unauthorized'],
                403
            );
        }

        $proyek = Proyek::findOrFail($id);

        return response()->json([

            'id_pembangunan' => $proyek->id_pembangunan,

            'nama_pembangunan' => $proyek->nama_pembangunan,

            'lokasi' => $proyek->lokasi,

            'tahun_anggaran' => $proyek->tahun_anggaran,

            'tanggal_mulai' => $proyek->tanggal_mulai
                ? date(
                    'Y-m-d',
                    strtotime($proyek->tanggal_mulai)
                )
                : null,

            'tanggal_selesai' => $proyek->tanggal_selesai
                ? date(
                    'Y-m-d',
                    strtotime($proyek->tanggal_selesai)
                )
                : null,

            'sumber_dana' => $proyek->sumber_dana,

            'anggaran' => $proyek->anggaran,

            // =================================================
            // DIMENSI
            // =================================================

            'panjang' => $proyek->panjang,

            'lebar' => $proyek->lebar,

            'ketebalan_tinggi' => $proyek->ketebalan_tinggi,

            // Nilai database selalu meter.
            'satuan_ketebalan' => 'm',

            'volume_proyek' => $proyek->volume_proyek,

            'satuan_volume' => $proyek->satuan_volume,

            // =================================================
            // PENAWARAN
            // =================================================

            'harga_penawaran' => $proyek->harga_penawaran,

            'batas_penawaran' => $proyek->batas_penawaran
                ? date(
                    'Y-m-d',
                    strtotime($proyek->batas_penawaran)
                )
                : null,

            // =================================================
            // LAINNYA
            // =================================================

            'status' => $proyek->status,

            'bidang_pekerjaan' => $proyek->bidang_pekerjaan,

            'catatan' => $proyek->catatan,
        ]);
    }

    // =========================================================
    // UPDATE
    // =========================================================

    public function update(
        Request $request,
        $id
    ) {
        if (Auth::user()->level !== 'admin') {
            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        $request->validate([
            'nama_pembangunan' => 'required|max:150',
            'lokasi' => 'required|max:150',
            'tahun_anggaran' => 'required|digits:4',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'sumber_dana' => 'required|max:100',
            'anggaran' => 'required|numeric|min:0',

            // DIMENSI
            'panjang' => 'required|numeric|min:0.01',
            'lebar' => 'required|numeric|min:0.01',
            'ketebalan_tinggi' => 'required|numeric|min:0.01',
            'satuan_ketebalan' => 'required|in:m,cm',

            'satuan_volume' => 'required|max:50',

            'harga_penawaran' => 'nullable|numeric|min:0',

            'batas_penawaran' => 'nullable|date|after_or_equal:today',

            'status' => 'required|in:direncanakan,berjalan,selesai',

            'bidang_pekerjaan' => 'nullable|max:100',

            'catatan' => 'nullable|string',
        ]);

        $proyek = Proyek::findOrFail($id);

        // =====================================================
        // HITUNG ULANG VOLUME
        // =====================================================

        // Ketebalan/tinggi selalu disimpan dalam METER.
        $ketebalanDalamMeter = (float) $request->ketebalan_tinggi;

        if ($request->satuan_ketebalan === 'cm') {
            $ketebalanDalamMeter /= 100;
        }

        $volume = round(
            (float) $request->panjang *
            (float) $request->lebar *
            $ketebalanDalamMeter,
            2
        );

        // =====================================================
        // UPDATE DATA
        // =====================================================

        $proyek->nama_pembangunan =
            $request->nama_pembangunan;

        $proyek->lokasi =
            $request->lokasi;

        $proyek->tahun_anggaran =
            $request->tahun_anggaran;

        $proyek->tanggal_mulai =
            $request->tanggal_mulai;

        $proyek->tanggal_selesai =
            $request->tanggal_selesai;

        $proyek->sumber_dana =
            $request->sumber_dana;

        $proyek->anggaran =
            $request->anggaran;

        // DIMENSI
        $proyek->panjang =
            $request->panjang;

        $proyek->lebar =
            $request->lebar;

        $proyek->ketebalan_tinggi =
            $ketebalanDalamMeter;

        // VOLUME
        $proyek->volume_proyek =
            $volume;

        $proyek->satuan_volume =
            $request->satuan_volume;

        // PENAWARAN
        $proyek->harga_penawaran =
            $request->harga_penawaran;

        $proyek->batas_penawaran =
            $request->batas_penawaran;

        // LAINNYA
        $proyek->status =
            $request->status;

        $proyek->bidang_pekerjaan =
            $request->bidang_pekerjaan;

        $proyek->catatan =
            $request->catatan;

        $proyek->save();

        return redirect()
            ->route('proyek.index')
            ->with(
                'success',
                'Proyek berhasil diupdate!'
            );
    }

    // =========================================================
    // DELETE
    // =========================================================

    public function destroy($id)
    {
        if (Auth::user()->level !== 'admin') {
            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        $proyek = Proyek::findOrFail($id);

        $proyek->delete();

        return redirect()
            ->route('proyek.index')
            ->with(
                'success',
                'Proyek berhasil dihapus!'
            );
    }

    // =========================================================
    // PUBLISH / UNPUBLISH
    // =========================================================

    public function publish($id)
    {
        if (Auth::user()->level !== 'admin') {
            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        $proyek = Proyek::findOrFail($id);

        $wasPublished =
            (bool) $proyek->is_published;

        $proyek->is_published =
            ! $proyek->is_published;

        if ($proyek->is_published) {

            $proyek->status_penawaran =
                'open';
        }

        $proyek->save();

        // =====================================================
        // NOTIFIKASI PROYEK BARU
        // =====================================================

        if (
            ! $wasPublished &&
            $proyek->is_published
        ) {

            $rekanans =
                User::where(
                    'level',
                    'rekanan'
                )->get([
                    'id_user',
                ]);

            foreach ($rekanans as $rekanan) {

                Notification::create([

                    'user_id' => $rekanan->id_user,

                    'proyek_id' => $proyek->id_pembangunan,

                    'title' => 'Proyek Baru Tersedia',

                    'message' =>
                        'Proyek "' .
                        $proyek->nama_pembangunan .
                        '" telah dipublikasikan dan tersedia untuk ditawar.',

                    'url' => route(
                        'rekanan.available'
                    ),

                    'is_read' => false,
                ]);
            }
        }

        $status =
            $proyek->is_published
                ? 'dipublikasikan'
                : 'ditutup publikasinya';

        return redirect()
            ->route('proyek.index')
            ->with(
                'success',
                "Proyek berhasil {$status}!"
            );
    }

    // =========================================================
    // GET DATA PENAWARAN AJAX
    // =========================================================

    public function getPenawaranData($id)
    {
        // Hanya admin yang boleh mengakses data penawaran
        if (! Auth::check() || Auth::user()->level !== 'admin') {
            return response()->json([
                'error' => 'Unauthorized',
            ], 403);
        }

        // =====================================================
        // AMBIL PROYEK + DATA REKANAN
        // =====================================================

        $proyek = Proyek::with([
            'rekananProyek.rekanan',
        ])->findOrFail($id);

        // =====================================================
        // SIAPKAN DATA PENAWARAN
        // =====================================================

        $penawaran = $proyek->rekananProyek->map(function ($item) {

            $rekanan = $item->rekanan;

            // =================================================
            // BUAT URL CV
            // =================================================

            $cvUrl = null;

            if ($rekanan && ! empty($rekanan->cv)) {

                $filename = basename($rekanan->cv);

                $fullPath = public_path(
                    'uploads/cv_rekanan/' . $filename
                );

                // Hanya berikan URL jika file benar-benar ada
                if (file_exists($fullPath)) {

                    $cvUrl = asset(
                        'uploads/cv_rekanan/' .
                        rawurlencode($filename)
                    );
                }
            }

            // =================================================
            // TAMBAHKAN DATA REKANAN
            // =================================================

            if ($rekanan) {

                $rekanan->setAttribute(
                    'cv_url',
                    $cvUrl
                );

                $rekanan->setAttribute(
                    'skor_kredibilitas',
                    $rekanan->skor_kredibilitas
                );

                $rekanan->setAttribute(
                    'predikat_kredibilitas',
                    $rekanan->predikat_kredibilitas
                );

                $rekanan->setAttribute(
                    'rata_rating',
                    $rekanan->rata_rating
                );

                $rekanan->setAttribute(
                    'persentase_tepat_waktu',
                    $rekanan->persentase_tepat_waktu
                );

                $rekanan->setAttribute(
                    'total_proyek_dievaluasi',
                    $rekanan->total_proyek_dievaluasi
                );

                $rekanan->setAttribute(
                    'total_tepat_waktu',
                    $rekanan->total_tepat_waktu
                );
            }

            return $item;
        });

        // =====================================================
        // RESPONSE
        // =====================================================

        return response()->json([
            'proyek' => $proyek,
            'penawaran' => $penawaran,
        ]);
    }

    // =========================================================
    // DAFTAR PENAWARAN
    // =========================================================

    public function daftarPenawaran($id)
    {
        if (Auth::user()->level !== 'admin') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        $proyek =
            Proyek::with([
                'rekananProyek.rekanan',
            ])
                ->findOrFail($id);

        $penawaran =
            $proyek->rekananProyek;

        return view(
            'proyek.penawaran',
            compact(
                'proyek',
                'penawaran'
            )
        );
    }

    // =========================================================
    // ADMIN - SETUJUI PENAWARAN
    // =========================================================

    public function setujuiPenawaran(
        $proyekId,
        $rekananProyekId
    ) {
        if (Auth::user()->level !== 'admin') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return $this->prosesKeputusanPenawaran(
            $proyekId,
            $rekananProyekId,
            'diterima',
            'proyek.index'
        );
    }

    // =========================================================
    // ADMIN - PERTIMBANGKAN
    // =========================================================

    public function pertimbangkanPenawaran(
        $proyekId,
        $rekananProyekId
    ) {
        if (Auth::user()->level !== 'admin') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return $this->prosesKeputusanPenawaran(
            $proyekId,
            $rekananProyekId,
            'dipertimbangkan',
            'proyek.index'
        );
    }

    // =========================================================
    // ADMIN - TOLAK
    // =========================================================

    public function tolakPenawaran(
        $proyekId,
        $rekananProyekId
    ) {
        if (Auth::user()->level !== 'admin') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return $this->prosesKeputusanPenawaran(
            $proyekId,
            $rekananProyekId,
            'ditolak',
            'proyek.index'
        );
    }

    // =========================================================
    // KEPALA DESA - SETUJUI
    // =========================================================

    public function setujuiPenawaranKades(
        $proyekId,
        $rekananProyekId
    ) {
        if (Auth::user()->level !== 'kepala_desa') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return $this->prosesKeputusanPenawaran(
            $proyekId,
            $rekananProyekId,
            'diterima',
            'kades.proyek'
        );
    }

    // =========================================================
    // KEPALA DESA - PERTIMBANGKAN
    // =========================================================

    public function pertimbangkanPenawaranKades(
        $proyekId,
        $rekananProyekId
    ) {
        if (Auth::user()->level !== 'kepala_desa') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return $this->prosesKeputusanPenawaran(
            $proyekId,
            $rekananProyekId,
            'dipertimbangkan',
            'kades.proyek'
        );
    }

    // =========================================================
    // KEPALA DESA - TOLAK
    // =========================================================

    public function tolakPenawaranKades(
        $proyekId,
        $rekananProyekId
    ) {
        if (Auth::user()->level !== 'kepala_desa') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        return $this->prosesKeputusanPenawaran(
            $proyekId,
            $rekananProyekId,
            'ditolak',
            'kades.proyek'
        );
    }

    // =========================================================
    // PROSES KEPUTUSAN PENAWARAN - FIX VERSION
    // =========================================================

    private function prosesKeputusanPenawaran(
        $proyekId,
        $rekananProyekId,
        string $statusBaru,
        string $routeKembali
    ) {

        $penawaran =
            RekananProyek::with('rekanan')
                ->where(
                    'id_proyek',
                    $proyekId
                )
                ->findOrFail(
                    $rekananProyekId
                );

        $proyek =
            Proyek::findOrFail(
                $proyekId
            );

        $statusLama =
            $penawaran->status_penawaran;

        // =====================================================
        // SETUJUI
        // =====================================================

        if ($statusBaru === 'diterima') {

            $penawaran->status_penawaran =
                'diterima';

            $penawaran->status =
                'setuju';

            $penawaran->tanggal_respon =
                now();

            $penawaran->save();

            $proyek->rekanan_terpilih =
                $penawaran->id_rekanan;

            $proyek->status_penawaran =
                'closed';

            $proyek->save();

            // Semua penawar lain ditolak
            $penawaranLain =
                RekananProyek::with('rekanan')
                    ->where(
                        'id_proyek',
                        $proyekId
                    )
                    ->where(
                        'id_rekanan_proyek',
                        '!=',
                        $rekananProyekId
                    )
                    ->get();

            foreach ($penawaranLain as $lain) {

                if (
                    $lain->status_penawaran !==
                    'ditolak'
                ) {

                    $lain->status_penawaran =
                        'ditolak';

                    $lain->status =
                        'tolak';

                    $lain->tanggal_respon =
                        now();

                    $lain->save();

                    $this->kirimEmailStatusPenawaran(
                        $lain,
                        $proyek,
                        'ditolak'
                    );
                }
            }

            if (
                $statusLama !==
                'diterima'
            ) {

                $this->kirimEmailStatusPenawaran(
                    $penawaran,
                    $proyek,
                    'diterima'
                );
            }

            return redirect()
                ->route($routeKembali)
                ->with(
                    'success',
                    'Penawaran berhasil disetujui! Rekanan terpilih: ' .
                    (
                        $penawaran->rekanan->nama_user
                        ?? 'Rekanan'
                    )
                );
        }

        // =========================================================
        // DIPERTIMBANGKAN
        // =========================================================

        if ($statusBaru === 'dipertimbangkan') {

            $penawaran->status_penawaran =
                'dipertimbangkan';

            $penawaran->tanggal_respon =
                now();

            $penawaran->save();

            // Kirim email hanya jika status sebelumnya berbeda
            if ($statusLama !== 'dipertimbangkan') {

                $this->kirimEmailStatusPenawaran(
                    $penawaran,
                    $proyek,
                    'dipertimbangkan'
                );
            }

            return redirect()
                ->route($routeKembali)
                ->with(
                    'success',
                    'Penawaran berhasil ditandai sebagai dipertimbangkan.'
                );
        }

        // =====================================================
        // DITOLAK
        // =====================================================

        if ($statusBaru === 'ditolak') {

            $penawaran->status_penawaran =
                'ditolak';

            $penawaran->status =
                'tolak';

            $penawaran->tanggal_respon =
                now();

            $penawaran->save();

            if ($statusLama !== 'ditolak') {

                $this->kirimEmailStatusPenawaran(
                    $penawaran,
                    $proyek,
                    'ditolak'
                );
            }

            return redirect()
                ->route($routeKembali)
                ->with(
                    'success',
                    'Penawaran berhasil ditolak.'
                );
        }

        abort(
            400,
            'Status penawaran tidak valid.'
        );
    }

    // =========================================================
    // EMAIL STATUS PENAWARAN
    // =========================================================

    private function kirimEmailStatusPenawaran(
        RekananProyek $penawaran,
        Proyek $proyek,
        string $status
    ): void {

        $rekanan =
            $penawaran->rekanan;

        if (! $rekanan) {

            Log::warning(
                'Email status penawaran tidak dikirim: relasi Rekanan tidak ditemukan.',
                [
                    'id_rekanan_proyek' =>
                        $penawaran->id_rekanan_proyek,

                    'id_proyek' =>
                        $proyek->id_pembangunan,

                    'status' =>
                        $status,
                ]
            );

            return;
        }

        if (
            empty(
                $rekanan->email
            )
        ) {

            Log::warning(
                'Email status penawaran tidak dikirim: email Rekanan kosong.',
                [
                    'id_rekanan' =>
                        $rekanan->id_user,

                    'nama_rekanan' =>
                        $rekanan->nama_user,

                    'id_rekanan_proyek' =>
                        $penawaran->id_rekanan_proyek,

                    'id_proyek' =>
                        $proyek->id_pembangunan,

                    'status' =>
                        $status,
                ]
            );

            return;
        }

        try {

            Mail::to(
                $rekanan->email
            )->send(
                new PenawaranStatusMail(
                    $rekanan,
                    $proyek,
                    $penawaran,
                    $status
                )
            );

        } catch (\Throwable $e) {

            Log::error(
                'Gagal mengirim email status penawaran.',
                [
                    'email' =>
                        $rekanan->email,

                    'id_rekanan_proyek' =>
                        $penawaran->id_rekanan_proyek,

                    'id_proyek' =>
                        $proyek->id_pembangunan,

                    'status' =>
                        $status,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }
    }

    // =========================================================
    // =========================================================
    // CETAK LAPORAN PENAWAR - VERSI LENGKAP
    // (PREVIEW PDF, DOWNLOAD PDF, CETAK BROWSER)
    // =========================================================
    // =========================================================

    /**
     * Data laporan penawar (dipakai bareng oleh semua method).
     */
    private function getDataLaporanPenawar()
    {
        $proyeks = Proyek::with(['rekananProyek.rekanan'])
            ->whereHas('rekananProyek')
            ->latest()
            ->get();

        $totalProyek = $proyeks->count();

        $totalPenawaran = $proyeks->sum(function ($proyek) {
            return $proyek->rekananProyek->count();
        });

        $totalDisetujui = $proyeks->sum(function ($proyek) {
            return $proyek->rekananProyek
                ->where('status_penawaran', 'diterima')
                ->count();
        });

        $totalDipertimbangkan = $proyeks->sum(function ($proyek) {
            return $proyek->rekananProyek
                ->whereIn('status_penawaran', ['pending', 'dipertimbangkan'])
                ->count();
        });

        $totalDitolak = $proyeks->sum(function ($proyek) {
            return $proyek->rekananProyek
                ->where('status_penawaran', 'ditolak')
                ->count();
        });

        $kepalaDesa = User::where('level', 'kepala_desa')->first();

        return compact(
            'proyeks',
            'totalProyek',
            'totalPenawaran',
            'totalDisetujui',
            'totalDipertimbangkan',
            'totalDitolak',
            'kepalaDesa'
        );
    }

    /**
     * Preview PDF di browser (inline).
     *
     * Route: /proyek/laporan-penawar/preview
     * Name:  proyek.laporan-penawar.preview
     */
    public function previewLaporanPenawarPdf()
    {
        if (Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $data = $this->getDataLaporanPenawar();

        $html = view('proyek.laporan-penawar-pdf', $data)->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_top' => 15,
            'margin_right' => 12,
            'margin_bottom' => 15,
            'margin_left' => 18,
            'default_font' => 'dejavusans',
            'default_font_size' => 10,
        ]);

        $mpdf->SetTitle('Laporan Daftar Penawar Proyek');
        $mpdf->WriteHTML($html);

        // 'I' = Inline (preview di browser)
        return $mpdf->Output('laporan_daftar_penawar.pdf', 'I');
    }

    /**
     * Download PDF (langsung download).
     *
     * Route: /proyek/laporan-penawar/download
     * Name:  proyek.laporan-penawar.download
     */
    public function downloadLaporanPenawarPdf()
    {
        if (Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $data = $this->getDataLaporanPenawar();

        $html = view('proyek.laporan-penawar-pdf', $data)->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_top' => 15,
            'margin_right' => 12,
            'margin_bottom' => 15,
            'margin_left' => 18,
            'default_font' => 'dejavusans',
            'default_font_size' => 10,
        ]);

        $mpdf->SetTitle('Laporan Daftar Penawar Proyek');
        $mpdf->WriteHTML($html);

        // 'D' = Download
        return $mpdf->Output(
            'laporan_daftar_penawar_' . date('Ymd_His') . '.pdf',
            'D'
        );
    }

    /**
     * Versi browser print (yang lama).
     * Tetap dipertahankan.
     *
     * Route: /proyek/laporan-penawar
     * Name:  proyek.laporan-penawar
     */
    public function cetakLaporanPenawar()
    {
        if (Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $data = $this->getDataLaporanPenawar();

        return view('proyek.laporan-penawar', $data);
    }

    // =========================================================    // KEPALA DESA
    // =========================================================

    public function allForKades()
    {
        if (Auth::user()->level !== 'kepala_desa') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        $proyeks =
            Proyek::with([
                'user',
                'rekananSetuju',
                'rekananProyek.rekanan',
            ])
                ->whereIn(
                    'status',
                    [
                        'direncanakan',
                        'berjalan',
                        'selesai',
                    ]
                )
                ->latest()
                ->paginate(10);

        return view(
            'kades.proyek',
            compact('proyeks')
        );
    }

    // =========================================================
    // DETAIL KEPALA DESA
    // =========================================================

    public function showForKades($id)
    {
        if (Auth::user()->level !== 'kepala_desa') {

            abort(
                403,
                'Anda tidak memiliki akses ke halaman ini.'
            );
        }

        if (request()->ajax()) {

            $proyek =
                Proyek::with([
                    'monitorings',
                    'evaluasis',
                    'dokumentasis',
                    'user',
                    'rekananSetuju',
                    'rekananProyek.rekanan',
                ])
                    ->findOrFail($id);

            $approvedMonitorings =
                $proyek
                    ->monitorings()
                    ->where(
                        'status_verifikasi',
                        'approved'
                    )
                    ->orderBy(
                        'tanggal_monitoring',
                        'asc'
                    )
                    ->get();

            $lastMonitoring =
                $approvedMonitorings->last();

            $progress =
                $lastMonitoring
                    ? (int) $lastMonitoring->progres
                    : 0;

            $data =
                $proyek->toArray();

            $data['progress'] =
                $progress;

            $data['monitorings'] =
                $approvedMonitorings
                    ->map(
                        function ($monitoring) {

                            return [

                                'id_monitoring' =>
                                    $monitoring->id_monitoring,

                                'tanggal_monitoring' =>
                                    $monitoring->tanggal_monitoring,

                                'progres' =>
                                    (int) $monitoring->progres,

                                'keterangan' =>
                                    $monitoring->keterangan,

                                'foto_before' =>
                                    $monitoring->foto_before,

                                'foto_after' =>
                                    $monitoring->foto_after,

                                'foto_before_url' =>
                                    $monitoring->foto_before_url,

                                'foto_after_url' =>
                                    $monitoring->foto_after_url,

                                'status_verifikasi' =>
                                    $monitoring->status_verifikasi,

                            ];
                        }
                    )
                    ->values();

            $data['dokumentasis'] =
                $proyek
                    ->dokumentasis
                    ->map(
                        function ($dokumentasi) {

                            return [

                                'id' =>
                                    $dokumentasi->id,

                                'foto' =>
                                    $dokumentasi->foto,

                                'keterangan' =>
                                    $dokumentasi->keterangan,

                                'tanggal_upload' =>
                                    $dokumentasi->tanggal_upload,

                            ];
                        }
                    )
                    ->values();

            return response()->json(
                $data
            );
        }

        $proyek =
            Proyek::with([
                'monitorings',
                'evaluasis',
                'dokumentasis',
                'user',
                'rekananSetuju',
                'rekananProyek.rekanan',
            ])
                ->findOrFail($id);

        return view(
            'kades.proyek-detail',
            compact('proyek')
        );
    }
}