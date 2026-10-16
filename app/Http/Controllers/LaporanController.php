<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiRekanan;
use App\Models\Laporan;
use App\Models\Monitoring;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class LaporanController extends Controller
{
    public function index()
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $laporans = Laporan::with(['proyek', 'user'])->latest()->paginate(10);
        $pembangunans = Proyek::all();

        $totalLaporan = Laporan::count();
        $laporanPembangunan = Laporan::where('jenis_laporan', 'pembangunan')->count();
        $laporanMonitoring = Laporan::where('jenis_laporan', 'monitoring')->count();
        $laporanEvaluasi = Laporan::where('jenis_laporan', 'evaluasi')->count();
        $laporanBulanIni = Laporan::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $laporanMingguIni = Laporan::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        return view('laporan.index', compact('laporans', 'pembangunans', 'totalLaporan', 'laporanPembangunan', 'laporanMonitoring', 'laporanEvaluasi', 'laporanBulanIni', 'laporanMingguIni'));
    }

    public function store(Request $request)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'id_pembangunan' => 'required|exists:proyek,id_pembangunan',
            'jenis_laporan' => 'required|in:pembangunan,monitoring,evaluasi',
            'periode' => 'required|max:50',
        ]);

        Laporan::create([
            'id_pembangunan' => $request->id_pembangunan,
            'jenis_laporan' => $request->jenis_laporan,
            'periode' => $request->periode,
            'tanggal_cetak' => date('Y-m-d'),
            'dibuat_oleh' => Auth::id(),
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dibuat!');
    }

    public function show($id)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (request()->ajax()) {
            $laporan = Laporan::with(['proyek', 'user'])->findOrFail($id);

            return response()->json($laporan);
        }

        return redirect()->route('laporan.index');
    }

    public function cetakPdf($id)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $laporan = Laporan::with(['proyek', 'user'])->findOrFail($id);
        $pembangunan = $laporan->proyek;
        $kepalaDesa = User::where('level', 'kepala_desa')->first();
        $jenisLaporan = $laporan->jenis_laporan;

        $data = [
            'laporan' => $laporan,
            'pembangunan' => $pembangunan,
            'kepalaDesa' => $kepalaDesa,
            'jenis_laporan' => $jenisLaporan,
        ];

        if ($jenisLaporan == 'monitoring' || $jenisLaporan == 'pembangunan') {
            $data['monitorings'] = $pembangunan->monitorings;
        }

        if ($jenisLaporan == 'evaluasi' || $jenisLaporan == 'pembangunan') {
            $data['evaluasis'] = $pembangunan->evaluasis;
        }

        if ($jenisLaporan == 'pembangunan') {
            $data['dokumentasis'] = $pembangunan->dokumentasis;
        }

        $html = view('laporan.pdf', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan '.ucfirst($jenisLaporan));
        $mpdf->WriteHTML($html);

        return $mpdf->Output('laporan_'.$jenisLaporan.'_'.date('Ymd').'.pdf', 'D');
    }

    public function previewPdf($id)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $laporan = Laporan::with(['proyek', 'user'])->findOrFail($id);
        $pembangunan = $laporan->proyek;
        $kepalaDesa = User::where('level', 'kepala_desa')->first();
        $jenisLaporan = $laporan->jenis_laporan;

        $data = [
            'laporan' => $laporan,
            'pembangunan' => $pembangunan,
            'kepalaDesa' => $kepalaDesa,
            'jenis_laporan' => $jenisLaporan,
        ];

        if ($jenisLaporan == 'monitoring' || $jenisLaporan == 'pembangunan') {
            $data['monitorings'] = $pembangunan->monitorings;
        }

        if ($jenisLaporan == 'evaluasi' || $jenisLaporan == 'pembangunan') {
            $data['evaluasis'] = $pembangunan->evaluasis;
        }

        if ($jenisLaporan == 'pembangunan') {
            $data['dokumentasis'] = $pembangunan->dokumentasis;
        }

        $html = view('laporan.pdf', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan '.ucfirst($jenisLaporan));
        $mpdf->WriteHTML($html);

        return $mpdf->Output('laporan_'.$jenisLaporan.'_'.date('Ymd').'.pdf', 'I');
    }

    public function destroy($id)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $laporan = Laporan::findOrFail($id);
        $laporan->delete();

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus!');
    }

    public function create()
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function update(Request $request, $id)
    {
        abort(404);
    }

    // ==================== CETAK LAPORAN PROYEK LANGSUNG ====================

    public function cetakLaporanProyek($id)
    {
        if (! Auth::check() || ! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $proyek = Proyek::with([
            'user',
            'monitorings' => function ($query) {
                $query->where('status_verifikasi', Monitoring::STATUS_APPROVED)
                    ->orderBy('tanggal_monitoring', 'asc');
            },
            'dokumentasis',
            'rekananSetuju',
            'rekananProyek.rekanan',
            'pengeluarans',
            'evaluasis' => function ($query) {
                $query->orderBy('tanggal_evaluasi', 'desc');
            },
            'evaluasiRekanan' => function ($query) {
                $query->where('status_verifikasi', EvaluasiRekanan::STATUS_APPROVED)
                    ->orderBy('tanggal_evaluasi', 'desc');
            },
        ])->findOrFail($id);

        $kepalaDesa = User::where('level', 'kepala_desa')->first();

        $hargaDeal = null;
        $rekanan = null;

        if ($proyek->rekanan_terpilih) {
            $rekananProyek = $proyek->rekananProyek
                ->where('id_rekanan', $proyek->rekanan_terpilih)
                ->first();

            if ($rekananProyek) {
                $hargaDeal = (float) ($rekananProyek->harga_penawaran_rekanan ?? 0);
                $rekanan = $rekananProyek->rekanan;
            }
        }

        $totalDanaTerpakai = $proyek->pengeluarans->sum(function ($pengeluaran) {
            return (float) ($pengeluaran->jumlah ?? 0);
        });

        $totalDanaMonitoring = $proyek->monitorings->sum(function ($monitoring) {
            return (float) ($monitoring->dana_terpakai ?? 0);
        });

        $anggaran = (float) ($proyek->anggaran ?? 0);
        $sisaAnggaran = $anggaran - $totalDanaTerpakai;

        $persentaseTerpakai = $anggaran > 0
            ? ($totalDanaTerpakai / $anggaran) * 100
            : 0;

        $persentaseTerpakai = min(100, max(0, $persentaseTerpakai));

        $selisihAnggaran = null;

        if ($hargaDeal !== null) {
            $selisihAnggaran = $anggaran - $hargaDeal;
        }

        $evaluasis = $proyek->evaluasis ?? collect();
        $evaluasiTerbaru = $evaluasis->first();
        $totalEvaluasi = $evaluasis->count();

        $statusEvaluasi = 'Belum Dievaluasi';
        if ($totalEvaluasi > 0) {
            $successCount = 0;
            $warningCount = 0;

            foreach ($evaluasis as $e) {
                $hasil = strtolower(strip_tags($e->hasil_evaluasi ?? ''));
                if (strpos($hasil, 'berhasil') !== false || strpos($hasil, 'sukses') !== false) {
                    $successCount++;
                } elseif (strpos($hasil, 'kurang') !== false || strpos($hasil, 'gagal') !== false) {
                    $warningCount++;
                }
            }

            if ($successCount == $totalEvaluasi) {
                $statusEvaluasi = 'Sangat Baik';
            } elseif ($successCount > $warningCount) {
                $statusEvaluasi = 'Baik';
            } elseif ($warningCount > $successCount) {
                $statusEvaluasi = 'Perlu Perbaikan';
            } else {
                $statusEvaluasi = 'Cukup';
            }
        }

        $evaluasiRekanan = $proyek->evaluasiRekanan ?? collect();
        $totalEvaluasiRekanan = $evaluasiRekanan->count();
        $rataRating = $totalEvaluasiRekanan > 0
            ? $evaluasiRekanan->avg('rating')
            : 0;

        $data = [
            'proyek' => $proyek,
            'kepalaDesa' => $kepalaDesa,
            'monitorings' => $proyek->monitorings,
            'dokumentasis' => $proyek->dokumentasis,
            'pengeluarans' => $proyek->pengeluarans,
            'rekanan' => $rekanan,
            'hargaDeal' => $hargaDeal,
            'selisihAnggaran' => $selisihAnggaran,
            'totalDanaTerpakai' => $totalDanaTerpakai,
            'totalDanaMonitoring' => $totalDanaMonitoring,
            'sisaAnggaran' => $sisaAnggaran,
            'persentaseTerpakai' => $persentaseTerpakai,
            'evaluasis' => $evaluasis,
            'evaluasiTerbaru' => $evaluasiTerbaru,
            'totalEvaluasi' => $totalEvaluasi,
            'statusEvaluasi' => $statusEvaluasi,
            'evaluasiRekanan' => $evaluasiRekanan,
            'totalEvaluasiRekanan' => $totalEvaluasiRekanan,
            'rataRating' => $rataRating,
        ];

        $html = view('laporan.cetak-proyek', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan Proyek - '.$proyek->nama_pembangunan);
        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'laporan_proyek_'.$proyek->id_pembangunan.'_'.date('Ymd_His').'.pdf',
            'D'
        );
    }

    // ==================== ADMIN - LAPORAN PER PROYEK ====================

    public function adminLaporanProyek(Request $request)
    {
        if (! Auth::check() || Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with([
            'user',
            'rekananSetuju',
            'monitorings' => function ($query) {
                $query->where('status_verifikasi', Monitoring::STATUS_APPROVED)
                    ->orderBy('tanggal_monitoring', 'asc');
            },
        ]);

        if ($request->filled('tahun')) {
            $query->where('tahun_anggaran', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $proyeks = $query
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $tahunList = Proyek::select('tahun_anggaran')
            ->whereNotNull('tahun_anggaran')
            ->distinct()
            ->orderByDesc('tahun_anggaran')
            ->pluck('tahun_anggaran');

        $totalProyek = Proyek::count();
        $totalAnggaran = Proyek::sum('anggaran');
        $proyekSelesai = Proyek::where('status', 'selesai')->count();
        $proyekBerjalan = Proyek::where('status', 'berjalan')->count();
        $proyekDirencanakan = Proyek::where('status', 'direncanakan')->count();

        return view('admin.laporan-proyek', compact(
            'proyeks',
            'tahunList',
            'totalProyek',
            'totalAnggaran',
            'proyekSelesai',
            'proyekBerjalan',
            'proyekDirencanakan'
        ));
    }

    public function adminLaporanProgres($id)
    {
        if (! Auth::check() || Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $proyek = Proyek::with([
            'monitorings' => function ($query) {
                $query->where('status_verifikasi', Monitoring::STATUS_APPROVED)
                    ->orderBy('tanggal_monitoring', 'asc');
            },
            'dokumentasis',
            'rekananSetuju',
            'rekananProyek',
        ])->findOrFail($id);

        $totalDanaTerpakai = $proyek->monitorings->sum('dana_terpakai');

        // FIX: Operator minus (-)
        $sisaAnggaran = $proyek->anggaran - $totalDanaTerpakai;

        $rekanan = null;
        $hargaDeal = null;

        if ($proyek->rekanan_terpilih) {
            $rekananProyek = $proyek->rekananProyek
                ->where('id_rekanan', $proyek->rekanan_terpilih)
                ->first();

            if ($rekananProyek) {
                $hargaDeal = $rekananProyek->harga_penawaran_rekanan;
                $rekanan = $rekananProyek->rekanan;
            }
        }

        return view('admin.laporan-progres', compact(
            'proyek',
            'totalDanaTerpakai',
            'sisaAnggaran',
            'rekanan',
            'hargaDeal'
        ));
    }

    // ==================== KEPALA DESA - LAPORAN PROYEK ====================

    public function laporanProyekKades(Request $request)
    {
        if (Auth::user()->level != 'kepala_desa') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with(['user', 'rekananSetuju', 'monitorings']);

        if ($request->tahun) {
            $query->where('tahun_anggaran', $request->tahun);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $proyeks = $query->latest()->paginate(10);
        $tahunList = Proyek::select('tahun_anggaran')->distinct()->pluck('tahun_anggaran');

        $totalProyek = Proyek::count();
        $totalAnggaran = Proyek::sum('anggaran');
        $proyekSelesai = Proyek::where('status', 'selesai')->count();
        $proyekBerjalan = Proyek::where('status', 'berjalan')->count();
        $proyekDirencanakan = Proyek::where('status', 'direncanakan')->count();

        return view('kades.laporan-proyek', compact('proyeks', 'tahunList', 'totalProyek', 'totalAnggaran', 'proyekSelesai', 'proyekBerjalan', 'proyekDirencanakan'));
    }

    // ==================== KEPALA DESA - LAPORAN PROGRES (DETAIL) ====================

    public function laporanProgresKades($id)
    {
        if (Auth::user()->level != 'kepala_desa') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $proyek = Proyek::with([
            'monitorings' => function ($query) {
                $query->where('status_verifikasi', Monitoring::STATUS_APPROVED)
                    ->orderBy('tanggal_monitoring', 'asc');
            },
            'dokumentasis',
            'rekananSetuju',
            'rekananProyek',
        ])->findOrFail($id);

        $totalDanaTerpakai = $proyek->monitorings->sum('dana_terpakai');

        // FIX: Operator minus (-)
        $sisaAnggaran = $proyek->anggaran - $totalDanaTerpakai;

        $rekanan = null;
        $hargaDeal = null;
        if ($proyek->rekanan_terpilih) {
            $rekananProyek = $proyek->rekananProyek->where('id_rekanan', $proyek->rekanan_terpilih)->first();
            if ($rekananProyek) {
                $hargaDeal = $rekananProyek->harga_penawaran_rekanan;
                $rekanan = $rekananProyek->rekanan;
            }
        }

        return view('kades.laporan-progres', compact('proyek', 'totalDanaTerpakai', 'sisaAnggaran', 'rekanan', 'hargaDeal'));
    }

    // ==================== KEPALA DESA - LAPORAN TAHUNAN ====================

    public function kadesLaporanTahunan(Request $request)
    {
        if (Auth::user()->level != 'kepala_desa') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with(['user', 'rekananSetuju']);

        if ($request->tahun_dari && $request->tahun_sampai) {
            $query->whereBetween('tahun_anggaran', [$request->tahun_dari, $request->tahun_sampai]);
        } elseif ($request->tahun_dari) {
            $query->where('tahun_anggaran', '>=', $request->tahun_dari);
        } elseif ($request->tahun_sampai) {
            $query->where('tahun_anggaran', '<=', $request->tahun_sampai);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $proyeks = $query->latest()->paginate(15);
        $tahunList = Proyek::select('tahun_anggaran')->distinct()->orderBy('tahun_anggaran')->pluck('tahun_anggaran');
        $statusList = ['direncanakan', 'berjalan', 'selesai'];

        $totalAnggaran = $query->get()->sum('anggaran');
        $totalProyek = $query->count();

        $statusChart = Proyek::selectRaw('status, count(*) as total')->groupBy('status')->get();
        $proyekPerTahun = Proyek::selectRaw('tahun_anggaran, count(*) as total')->groupBy('tahun_anggaran')->get();

        return view('kades.laporan-tahunan', compact('proyeks', 'tahunList', 'statusList', 'totalAnggaran', 'totalProyek', 'statusChart', 'proyekPerTahun'));
    }

    public function kadesPreviewLaporanTahunan(Request $request)
    {
        if (! Auth::check() || Auth::user()->level !== 'kepala_desa') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with([
            'user',
            'rekananSetuju',
            'pengeluarans',
        ]);

        $this->applyLaporanTahunanFilter($query, $request);

        $proyeks = $query
            ->orderBy('tahun_anggaran', 'asc')
            ->orderBy('nama_pembangunan', 'asc')
            ->get();

        $kepalaDesa = User::where('level', 'kepala_desa')->first();
        $statistik = $this->hitungStatistikLaporanTahunan($proyeks);
        $periode = $this->buatPeriodeLaporanTahunan($request);

        $data = [
            'proyeks' => $proyeks,
            'kepalaDesa' => $kepalaDesa,
            'periode' => $periode,
            'tahun_dari' => $request->tahun_dari,
            'tahun_sampai' => $request->tahun_sampai,
            'status' => $request->status,
            'totalProyek' => $statistik['totalProyek'],
            'totalAnggaran' => $statistik['totalAnggaran'],
            'totalDanaTerpakai' => $statistik['totalDanaTerpakai'],
            'totalSisaAnggaran' => $statistik['totalSisaAnggaran'],
            'totalSelesai' => $statistik['totalSelesai'],
            'totalBerjalan' => $statistik['totalBerjalan'],
            'totalDirencanakan' => $statistik['totalDirencanakan'],
        ];

        $html = view('admin.pdf-laporan-tahunan', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan Tahunan Proyek Desa Kutabuluh - '.$periode);
        $mpdf->WriteHTML($html);

        return $mpdf->Output('laporan_tahunan_kades.pdf', 'I');
    }

    public function kadesDownloadLaporanTahunan(Request $request)
    {
        if (! Auth::check() || Auth::user()->level !== 'kepala_desa') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with([
            'user',
            'rekananSetuju',
            'pengeluarans',
        ]);

        $this->applyLaporanTahunanFilter($query, $request);

        $proyeks = $query
            ->orderBy('tahun_anggaran', 'asc')
            ->orderBy('nama_pembangunan', 'asc')
            ->get();

        $kepalaDesa = User::where('level', 'kepala_desa')->first();
        $statistik = $this->hitungStatistikLaporanTahunan($proyeks);
        $periode = $this->buatPeriodeLaporanTahunan($request);

        $data = [
            'proyeks' => $proyeks,
            'kepalaDesa' => $kepalaDesa,
            'periode' => $periode,
            'tahun_dari' => $request->tahun_dari,
            'tahun_sampai' => $request->tahun_sampai,
            'status' => $request->status,
            'totalProyek' => $statistik['totalProyek'],
            'totalAnggaran' => $statistik['totalAnggaran'],
            'totalDanaTerpakai' => $statistik['totalDanaTerpakai'],
            'totalSisaAnggaran' => $statistik['totalSisaAnggaran'],
            'totalSelesai' => $statistik['totalSelesai'],
            'totalBerjalan' => $statistik['totalBerjalan'],
            'totalDirencanakan' => $statistik['totalDirencanakan'],
        ];

        $html = view('admin.pdf-laporan-tahunan', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan Tahunan Proyek Desa Kutabuluh - '.$periode);
        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'laporan_tahunan_kades_'.date('Ymd_His').'.pdf',
            'D'
        );
    }

    // ==================== ADMIN - LAPORAN TAHUNAN ====================

    public function adminLaporanTahunan(Request $request)
    {
        if (! Auth::check() || Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with([
            'user',
            'rekananSetuju',
            'pengeluarans',
        ]);

        $this->applyLaporanTahunanFilter($query, $request);

        $proyeks = $query
            ->latest('tahun_anggaran')
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $tahunList = Proyek::select('tahun_anggaran')
            ->whereNotNull('tahun_anggaran')
            ->distinct()
            ->orderBy('tahun_anggaran', 'desc')
            ->pluck('tahun_anggaran');

        $statusList = [
            'direncanakan',
            'berjalan',
            'selesai',
        ];

        $statQuery = Proyek::with(['pengeluarans']);
        $this->applyLaporanTahunanFilter($statQuery, $request);
        $statProyeks = $statQuery->get();

        $statistik = $this->hitungStatistikLaporanTahunan($statProyeks);

        return view('admin.laporan-tahunan', [
            'proyeks' => $proyeks,
            'tahunList' => $tahunList,
            'statusList' => $statusList,
            'totalAnggaran' => $statistik['totalAnggaran'],
            'totalDanaTerpakai' => $statistik['totalDanaTerpakai'],
            'totalSisaAnggaran' => $statistik['totalSisaAnggaran'],
            'totalProyek' => $statistik['totalProyek'],
            'totalSelesai' => $statistik['totalSelesai'],
            'totalBerjalan' => $statistik['totalBerjalan'],
            'totalDirencanakan' => $statistik['totalDirencanakan'],
            'tahun_dari' => $request->tahun_dari,
            'tahun_sampai' => $request->tahun_sampai,
            'status' => $request->status,
        ]);
    }

    public function adminPreviewLaporanTahunan(Request $request)
    {
        if (! Auth::check() || Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with([
            'user',
            'rekananSetuju',
            'pengeluarans',
        ]);

        $this->applyLaporanTahunanFilter($query, $request);

        $proyeks = $query
            ->orderBy('tahun_anggaran', 'asc')
            ->orderBy('nama_pembangunan', 'asc')
            ->get();

        $kepalaDesa = User::where('level', 'kepala_desa')->first();

        $statistik = $this->hitungStatistikLaporanTahunan($proyeks);
        $periode = $this->buatPeriodeLaporanTahunan($request);

        $data = [
            'proyeks' => $proyeks,
            'kepalaDesa' => $kepalaDesa,
            'tahun_dari' => $request->tahun_dari,
            'tahun_sampai' => $request->tahun_sampai,
            'status' => $request->status,
            'periode' => $periode,
            'totalProyek' => $statistik['totalProyek'],
            'totalAnggaran' => $statistik['totalAnggaran'],
            'totalDanaTerpakai' => $statistik['totalDanaTerpakai'],
            'totalSisaAnggaran' => $statistik['totalSisaAnggaran'],
            'totalSelesai' => $statistik['totalSelesai'],
            'totalBerjalan' => $statistik['totalBerjalan'],
            'totalDirencanakan' => $statistik['totalDirencanakan'],
        ];

        $html = view('admin.pdf-laporan-tahunan', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan Tahunan Proyek Desa Kutabuluh');
        $mpdf->WriteHTML($html);

        return $mpdf->Output('laporan_tahunan.pdf', 'I');
    }

    public function adminDownloadLaporanTahunan(Request $request)
    {
        if (! Auth::check() || Auth::user()->level !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $query = Proyek::with([
            'user',
            'rekananSetuju',
            'pengeluarans',
        ]);

        $this->applyLaporanTahunanFilter($query, $request);

        $proyeks = $query
            ->orderBy('tahun_anggaran', 'asc')
            ->orderBy('nama_pembangunan', 'asc')
            ->get();

        $kepalaDesa = User::where('level', 'kepala_desa')->first();

        $statistik = $this->hitungStatistikLaporanTahunan($proyeks);
        $periode = $this->buatPeriodeLaporanTahunan($request);

        $data = [
            'proyeks' => $proyeks,
            'kepalaDesa' => $kepalaDesa,
            'tahun_dari' => $request->tahun_dari,
            'tahun_sampai' => $request->tahun_sampai,
            'status' => $request->status,
            'periode' => $periode,
            'totalProyek' => $statistik['totalProyek'],
            'totalAnggaran' => $statistik['totalAnggaran'],
            'totalDanaTerpakai' => $statistik['totalDanaTerpakai'],
            'totalSisaAnggaran' => $statistik['totalSisaAnggaran'],
            'totalSelesai' => $statistik['totalSelesai'],
            'totalBerjalan' => $statistik['totalBerjalan'],
            'totalDirencanakan' => $statistik['totalDirencanakan'],
        ];

        $html = view('admin.pdf-laporan-tahunan', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan Tahunan Proyek Desa Kutabuluh');
        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'laporan_tahunan_'.date('Ymd_His').'.pdf',
            'D'
        );
    }

    private function buatPeriodeLaporanTahunan(Request $request)
    {
        $dari = $request->tahun_dari;
        $sampai = $request->tahun_sampai;

        if ($dari && $sampai) {
            return $dari.' - '.$sampai;
        }

        if ($dari) {
            return $dari.' - Sekarang';
        }

        if ($sampai) {
            return 'Sampai '.$sampai;
        }

        return 'Semua Tahun';
    }

    private function applyLaporanTahunanFilter($query, Request $request)
    {
        if ($request->filled('tahun_dari') && $request->filled('tahun_sampai')) {
            $query->whereBetween('tahun_anggaran', [
                $request->tahun_dari,
                $request->tahun_sampai,
            ]);
        } elseif ($request->filled('tahun_dari')) {
            $query->where(
                'tahun_anggaran',
                '>=',
                $request->tahun_dari
            );
        } elseif ($request->filled('tahun_sampai')) {
            $query->where(
                'tahun_anggaran',
                '<=',
                $request->tahun_sampai
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    private function hitungStatistikLaporanTahunan($proyeks)
    {
        $totalAnggaran = $proyeks->sum(function ($proyek) {
            return (float) ($proyek->anggaran ?? 0);
        });

        $totalDanaTerpakai = $proyeks->sum(function ($proyek) {
            if (! $proyek->relationLoaded('pengeluarans')) {
                return 0;
            }

            return $proyek->pengeluarans->sum(function ($pengeluaran) {
                return (float) ($pengeluaran->jumlah ?? 0);
            });
        });

        return [
            'totalProyek' => $proyeks->count(),
            'totalAnggaran' => $totalAnggaran,
            'totalDanaTerpakai' => $totalDanaTerpakai,

            // FIX: Operator minus (-)
            'totalSisaAnggaran' => max(0, $totalAnggaran - $totalDanaTerpakai),

            'totalSelesai' => $proyeks
                ->where('status', 'selesai')
                ->count(),

            'totalBerjalan' => $proyeks
                ->where('status', 'berjalan')
                ->count(),

            'totalDirencanakan' => $proyeks
                ->where('status', 'direncanakan')
                ->count(),
        ];
    }

    // ==================== KEPALA DESA - CETAK LAPORAN PDF ====================

    public function cetakLaporanKades($id)
    {
        if (Auth::user()->level != 'kepala_desa') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $proyek = Proyek::with([
            'monitorings' => function ($query) {
                $query->where('status_verifikasi', Monitoring::STATUS_APPROVED)
                    ->orderBy('tanggal_monitoring', 'asc');
            },
            'dokumentasis',
            'rekananSetuju',
        ])->findOrFail($id);

        $kepalaDesa = User::where('level', 'kepala_desa')->first();

        $data = [
            'proyek' => $proyek,
            'kepalaDesa' => $kepalaDesa,
            'monitorings' => $proyek->monitorings,
            'dokumentasis' => $proyek->dokumentasis,
            'rekanan' => $proyek->rekananSetuju->first(),
            'totalDanaTerpakai' => $proyek->monitorings->sum('dana_terpakai'),

            // FIX: Operator minus (-)
            'sisaAnggaran' => $proyek->anggaran - $proyek->monitorings->sum('dana_terpakai'),
        ];

        $html = view('kades.cetak-laporan', $data)->render();

        $mpdf = $this->buatMpdf([
            'margin_top' => 42,
            'margin_bottom' => 20,
        ]);

        $mpdf->SetTitle('Laporan Proyek - '.$proyek->nama_pembangunan);
        $mpdf->WriteHTML($html);

        return $mpdf->Output('laporan_proyek_'.$proyek->id_pembangunan.'.pdf', 'D');
    }

    // ==================== API / AJAX UNTUK LAPORAN ====================

    public function getProyekData($id)
    {
        if (! in_array(Auth::user()->level, ['admin', 'kepala_desa'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $proyek = Proyek::with([
                'monitorings' => function ($query) {
                    $query->where('status_verifikasi', Monitoring::STATUS_APPROVED)
                        ->orderBy('tanggal_monitoring', 'asc');
                },
                'dokumentasis',
                'rekananProyek.rekanan',
            ])->findOrFail($id);

            $totalDana = $proyek->monitorings->sum('dana_terpakai');

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $proyek->id_pembangunan,
                    'nama' => $proyek->nama_pembangunan,
                    'lokasi' => $proyek->lokasi,
                    'status' => $proyek->status,
                    'anggaran' => $proyek->anggaran,
                    'total_dana_terpakai' => $totalDana,

                    // FIX: Operator minus (-)
                    'sisa_anggaran' => $proyek->anggaran - $totalDana,

                    'progress' => $proyek->progress,
                    'monitoring_count' => $proyek->monitorings->count(),
                    'monitorings' => $proyek->monitorings->map(function ($m) {
                        return [
                            'tanggal' => $m->tanggal_monitoring->format('Y-m-d'),
                            'progres' => $m->progres,
                            'status_progres' => $m->status_progres,
                            'dana_terpakai' => $m->dana_terpakai,
                            'keterangan' => $m->keterangan,
                        ];
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ==================== HELPER: INSTANSI MPDF DENGAN FOOTER OTOMATIS ====================

    /**
     * Membuat instance Mpdf dengan konfigurasi standar
     * yang mendukung <htmlpageheader> dan <htmlpagefooter>.
     *
     * Opsi kunci:
     *  - setAutoTopMargin    => 'stretch' : tinggi header HTML otomatis dihitung
     *  - setAutoBottomMargin => 'stretch' : tinggi footer HTML otomatis dihitung
     *  - defaultfooterline   => 0         : hilangkan garis default mPDF di atas footer
     *  - defaultheaderline   => 0         : hilangkan garis default mPDF di bawah header
     *
     * @param  array  $overrides  Opsi tambahan / override (mis. margin_top, margin_bottom)
     * @return Mpdf
     */
    private function buatMpdf(array $overrides = []): Mpdf
    {
        $defaults = [
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_top' => 42,
            'margin_right' => 12,
            'margin_bottom' => 20,
            'margin_left' => 18,
            'default_font' => 'dejavusans',
            'default_font_size' => 10,

            // ==== KUNCI AGAR <htmlpageheader>/<htmlpagefooter> BEKERJA OTOMATIS ====
            'setAutoTopMargin' => 'stretch',
            'setAutoBottomMargin' => 'stretch',
            'defaultfooterline' => 0,
            'defaultheaderline' => 0,
        ];

        return new Mpdf(array_merge($defaults, $overrides));
    }
}