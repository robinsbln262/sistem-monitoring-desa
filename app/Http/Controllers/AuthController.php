<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Monitoring;
use App\Models\Proyek;
use App\Models\RekananProyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $level = $user->level;

        // ==========================================
        // DATA UMUM UNTUK SEMUA ROLE
        // ==========================================
        $totalProyek = Proyek::count();
        $proyekBerjalan = Proyek::where('status', 'berjalan')->count();
        $proyekSelesai = Proyek::where('status', 'selesai')->count();
        $totalAnggaran = Proyek::sum('anggaran');

        $recentProyek = Proyek::with('user')->latest()->take(5)->get();

        $chartData = Proyek::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        // ==========================================
        // DATA KHUSUS ADMIN
        // ==========================================
        if ($level == 'admin') {
            $totalAdmin = User::where('level', 'admin')->count();
            $totalRekanan = User::where('level', 'rekanan')->count();
            $totalKepalaDesa = User::where('level', 'kepala_desa')->count();
            $totalProyekPublished = Proyek::where('is_published', true)->count();
            $totalProyekUnpublished = Proyek::where('is_published', false)->count();

            // ===== DATA VERIFIKASI MONITORING =====
            $pendingVerifikasiCount = Monitoring::pending()->count();
            $approvedVerifikasiCount = Monitoring::approved()->count();
            $rejectedVerifikasiCount = Monitoring::rejected()->count();
            $totalVerifikasiCount = Monitoring::count();

            // Monitoring pending terbaru (untuk ditampilkan di dashboard)
            $recentPendingMonitoring = Monitoring::with(['proyek', 'proyek.user'])
                ->pending()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'totalProyek', 'proyekBerjalan', 'proyekSelesai', 'totalAnggaran',
                'recentProyek', 'chartData',
                'totalAdmin', 'totalRekanan', 'totalKepalaDesa',
                'totalProyekPublished', 'totalProyekUnpublished',
                'pendingVerifikasiCount', 'approvedVerifikasiCount',
                'rejectedVerifikasiCount', 'totalVerifikasiCount',
                'recentPendingMonitoring'
            ));
        }

        // ==========================================
        // DATA KHUSUS REKANAN
        // ==========================================
        if ($level == 'rekanan') {
            $userId = $user->id_user;

            // Proyek yang tersedia (dipublikasi)
            $proyekTersedia = Proyek::where('is_published', true)
                ->where('status_penawaran', 'open')
                ->count();

            // Proyek yang disetujui rekanan
            $proyekDisetujui = RekananProyek::where('id_rekanan', $userId)
                ->where('status_penawaran', 'diterima')
                ->count();

            // Proyek yang ditinjau
            $proyekDitinjau = RekananProyek::where('id_rekanan', $userId)
                ->where('status', 'meninjau')
                ->count();

            // Monitoring aktif
            $monitoringAktif = Proyek::whereHas('rekananProyek', function ($q) use ($userId) {
                $q->where('id_rekanan', $userId)->where('status_penawaran', 'diterima');
            })->where('status', 'berjalan')->count();

            // Proyek yang dipublikasi untuk rekanan (preview di dashboard)
            $publishedProyek = Proyek::where('is_published', true)
                ->where('status_penawaran', 'open')
                ->with(['user', 'rekananProyek' => function ($q) use ($userId) {
                    $q->where('id_rekanan', $userId);
                }])
                ->latest()
                ->take(5)
                ->get();

            return view('dashboard', compact(
                'totalProyek', 'proyekBerjalan', 'proyekSelesai', 'totalAnggaran',
                'recentProyek', 'chartData',
                'proyekTersedia', 'proyekDisetujui', 'proyekDitinjau',
                'monitoringAktif', 'publishedProyek'
            ));
        }

        // ==========================================
        // DATA KHUSUS KEPALA DESA (LENGKAP)
        // ==========================================
        if ($level == 'kepala_desa') {
            // Statistik Rekanan
            $totalRekanan = User::where('level', 'rekanan')->count();

            // Statistik Penawaran
            $totalApproved = RekananProyek::where('status_penawaran', 'diterima')->count();
            $totalRejected = RekananProyek::where('status_penawaran', 'ditolak')->count();
            $totalPending = RekananProyek::where('status_penawaran', 'pending')->count();

            // Progres keseluruhan
            $totalProgress = 0;
            $proyeks = Proyek::all();
            foreach ($proyeks as $p) {
                $totalProgress += $p->progress;
            }
            $overallProgress = $proyeks->count() > 0 ? round($totalProgress / $proyeks->count()) : 0;

            // Laporan statistik
            $laporanPembangunan = Laporan::where('jenis_laporan', 'pembangunan')->count();
            $laporanMonitoring = Laporan::where('jenis_laporan', 'monitoring')->count();
            $laporanEvaluasi = Laporan::where('jenis_laporan', 'evaluasi')->count();

            // Proyek per tahun (untuk grafik)
            $proyekPerTahun = Proyek::selectRaw('tahun_anggaran, count(*) as total')
                ->groupBy('tahun_anggaran')
                ->get();

            // Anggaran per tahun (untuk grafik)
            $anggaranPerTahun = Proyek::selectRaw('tahun_anggaran, sum(anggaran) as total')
                ->groupBy('tahun_anggaran')
                ->get();

            // Status chart data
            $statusChart = Proyek::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get();

            // Proyek dengan rekanan terpilih
            $proyekDenganRekanan = Proyek::whereNotNull('rekanan_terpilih')->count();

            return view('dashboard', compact(
                'totalProyek',
                'proyekBerjalan',
                'proyekSelesai',
                'totalAnggaran',
                'recentProyek',
                'chartData',
                'totalRekanan',
                'totalApproved',
                'totalRejected',
                'totalPending',
                'overallProgress',
                'laporanPembangunan',
                'laporanMonitoring',
                'laporanEvaluasi',
                'proyekPerTahun',
                'anggaranPerTahun',
                'statusChart',
                'proyekDenganRekanan'
            ));
        }

        // ==========================================
        // FALLBACK
        // ==========================================
        return view('dashboard', compact(
            'totalProyek', 'proyekBerjalan', 'proyekSelesai', 'totalAnggaran',
            'recentProyek', 'chartData'
        ));
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
