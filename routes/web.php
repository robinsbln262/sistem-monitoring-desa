<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\EvaluasiRekananController;
use App\Http\Controllers\KomentarMasyarakatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PengeluaranProyekController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RekananController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ============================================================
// HALAMAN PUBLIK
// ============================================================

Route::get(
    '/',
    [PublikController::class, 'index']
)->name('home');

Route::get(
    '/publik/proyek/{id}',
    [PublikController::class, 'show']
)->name('publik.show');

// ============================================================
// KOMENTAR MASYARAKAT - PUBLIK
// ============================================================

Route::post(
    '/komentar-masyarakat',
    [KomentarMasyarakatController::class, 'store']
)->name('komentar-masyarakat.store');

Route::get(
    '/komentar-masyarakat/proyek/{id}',
    [KomentarMasyarakatController::class, 'getByProyek']
)->name('komentar-masyarakat.get-by-proyek');

// ============================================================
// AUTH
// ============================================================

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');

Route::post(
    '/login',
    [AuthController::class, 'login']
);

// ============================================================
// REGISTER REKANAN
// ============================================================

Route::get(
    '/register',
    [RegisterController::class, 'showRegister']
)->name('register');

Route::post(
    '/register',
    [RegisterController::class, 'register']
);

// ============================================================
// LOGOUT
// ============================================================

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)->name('logout');

// ============================================================
// GROUP AUTH
// ============================================================

Route::middleware(['auth'])->group(function () {

    // ========================================================
    // DASHBOARD
    // ========================================================

    Route::get(
        '/dashboard',
        [AuthController::class, 'dashboard']
    )->name('dashboard');

    // ========================================================
    // PROFILE
    // ========================================================

    Route::get(
        '/profile',
        [ProfileController::class, 'index']
    )->name('profile.index');

    Route::put(
        '/profile/update',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::put(
        '/profile/change-password',
        [ProfileController::class, 'changePassword']
    )->name('profile.change-password');

    // ========================================================
    // NOTIFIKASI
    // ========================================================

    Route::post(
        '/notifications/{id}/read',
        [NotificationController::class, 'read']
    )->name('notifications.read');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'readAll']
    )->name('notifications.read-all');

    // ========================================================
    // LAPORAN UMUM
    // ADMIN & KEPALA DESA
    // ========================================================

    Route::get(
        '/laporan/cetak-pdf/{id}',
        [LaporanController::class, 'cetakPdf']
    )->name('laporan.cetak-pdf');

    Route::get(
        '/laporan/preview-pdf/{id}',
        [LaporanController::class, 'previewPdf']
    )->name('laporan.preview-pdf');

    Route::get(
        '/laporan/cetak-proyek/{id}',
        [LaporanController::class, 'cetakLaporanProyek']
    )->name('laporan.cetak-proyek');

    Route::resource(
        'laporan',
        LaporanController::class
    );

    // ========================================================
    // AJAX RIWAYAT MONITORING
    // ========================================================

    Route::get(
        '/monitoring-riwayat/proyek/{id}',
        [MonitoringController::class, 'getByPembangunan']
    )->name('monitoring.riwayat.proyek');

    Route::get(
        '/monitoring/pembangunan/{id}',
        [MonitoringController::class, 'getByPembangunan']
    )->name('monitoring.pembangunan');

    // ========================================================
    // ========================================================
    // ADMIN
    // ========================================================
    // ========================================================

    Route::middleware(['role:admin'])->group(function () {

        // ====================================================
        // USERS
        // ====================================================

        Route::resource(
            'users',
            UserController::class
        );

        // ====================================================
        // PROYEK ADMIN
        // ====================================================

        // ====================================================
        // CETAK DAFTAR PENAWAR - PREVIEW PDF
        // ====================================================
        //
        // Diletakkan SEBELUM route laporan-penawar biasa
        // agar tidak bentrok dengan segment URL.
        // ====================================================

        Route::get(
            '/proyek/laporan-penawar/preview',
            [ProyekController::class, 'previewLaporanPenawarPdf']
        )->name('proyek.laporan-penawar.preview');

        // ====================================================
        // CETAK DAFTAR PENAWAR - DOWNLOAD PDF
        // ====================================================

        Route::get(
            '/proyek/laporan-penawar/download',
            [ProyekController::class, 'downloadLaporanPenawarPdf']
        )->name('proyek.laporan-penawar.download');

        // ====================================================
        // CETAK DAFTAR PENAWAR - BROWSER PRINT (LAMA)
        // ====================================================
        //
        // HARUS sebelum Route::resource('proyek')
        // agar "laporan-penawar" tidak dianggap sebagai {id}.
        // ====================================================

        Route::get(
            '/proyek/laporan-penawar',
            [ProyekController::class, 'cetakLaporanPenawar']
        )->name('proyek.laporan-penawar');

        // ====================================================
        // PUBLISH PROYEK
        // ====================================================

        Route::get(
            '/proyek/publish/{id}',
            [ProyekController::class, 'publish']
        )->name('proyek.publish');

        // ====================================================
        // GET DATA PROYEK UNTUK EDIT
        // ====================================================

        Route::get(
            '/proyek/{id}/get-data',
            [ProyekController::class, 'getData']
        )->name('proyek.get-data');

        // ====================================================
        // PENGELUARAN PROYEK
        // ====================================================

        Route::get(
            '/proyek/{id}/pengeluaran',
            [PengeluaranProyekController::class, 'index']
        )->name('admin.proyek.pengeluaran.index');

        Route::post(
            '/proyek/{id}/pengeluaran',
            [PengeluaranProyekController::class, 'store']
        )->name('admin.proyek.pengeluaran.store');

        Route::put(
            '/proyek/{id}/pengeluaran/{pengeluaranId}',
            [PengeluaranProyekController::class, 'update']
        )->name('admin.proyek.pengeluaran.update');

        Route::delete(
            '/proyek/{id}/pengeluaran/{pengeluaranId}',
            [PengeluaranProyekController::class, 'destroy']
        )->name('admin.proyek.pengeluaran.destroy');

        // ====================================================
        // PENAWARAN
        // ====================================================

        Route::get(
            '/proyek/{id}/penawaran',
            [ProyekController::class, 'daftarPenawaran']
        )->name('proyek.daftar-penawaran');

        Route::get(
            '/proyek/{id}/penawaran-data',
            [ProyekController::class, 'getPenawaranData']
        )->name('proyek.penawaran-data');

        // ====================================================
        // ROUTE PENAWARAN LAMA
        // TETAP DIPERTAHANKAN
        // ====================================================

        Route::post(
            '/proyek/{proyekId}/setujui-penawaran/{rekananProyekId}',
            [ProyekController::class, 'setujuiPenawaran']
        )->name('proyek.setujui-penawaran');

        Route::post(
            '/proyek/{proyekId}/pertimbangkan-penawaran/{rekananProyekId}',
            [ProyekController::class, 'pertimbangkanPenawaran']
        )->name('proyek.pertimbangkan-penawaran');

        Route::post(
            '/proyek/{proyekId}/tolak-penawaran/{rekananProyekId}',
            [ProyekController::class, 'tolakPenawaran']
        )->name('proyek.tolak-penawaran');

        // ====================================================
        // ROUTE FIX PENAWARAN
        // SESUAI URL YANG KAMU PAKAI
        // ====================================================

        Route::post(
            '/proyek/{proyekId}/penawaran/{rekananProyekId}/setujui',
            [ProyekController::class, 'setujuiPenawaran']
        )->name('proyek.penawaran.setujui');

        Route::post(
            '/proyek/{proyekId}/penawaran/{rekananProyekId}/pertimbangkan',
            [ProyekController::class, 'pertimbangkanPenawaran']
        )->name('proyek.penawaran.pertimbangkan');

        Route::post(
            '/proyek/{proyekId}/penawaran/{rekananProyekId}/tolak',
            [ProyekController::class, 'tolakPenawaran']
        )->name('proyek.penawaran.tolak');

        // ====================================================
        // RESOURCE PROYEK
        // ====================================================

        Route::resource(
            'proyek',
            ProyekController::class
        );

        // ====================================================
        // EVALUASI
        // ====================================================

        Route::resource(
            'evaluasi',
            EvaluasiController::class
        );

        Route::get(
            '/evaluasi/{id}/get-data',
            [EvaluasiController::class, 'getData']
        )->name('evaluasi.get-data');

        Route::get(
            '/evaluasi/pembangunan/{id}',
            [EvaluasiController::class, 'getByPembangunan']
        )->name('evaluasi.getByPembangunan');

        // ====================================================
        // DOKUMENTASI
        // ====================================================

        Route::resource(
            'dokumentasi',
            DokumentasiController::class
        );

        Route::get(
            '/dokumentasi/group/{groupId}/get-data',
            [DokumentasiController::class, 'getData']
        )->name('dokumentasi.get-data');

        Route::delete(
            '/dokumentasi/group/{groupId}',
            [DokumentasiController::class, 'destroyGroup']
        )->name('dokumentasi.destroy-group');

        Route::post(
            '/dokumentasi/delete-photo',
            [DokumentasiController::class, 'deletePhoto']
        )->name('dokumentasi.delete-photo');

        // ====================================================
        // LAPORAN PER PROYEK ADMIN
        // ====================================================

        Route::get(
            '/admin/laporan-proyek',
            [LaporanController::class, 'adminLaporanProyek']
        )->name('admin.laporan-proyek');

        Route::get(
            '/admin/laporan-progres/{id}',
            [LaporanController::class, 'adminLaporanProgres']
        )->name('admin.laporan-progres');

        Route::get(
            '/admin/cetak-laporan-proyek/{id}',
            [LaporanController::class, 'cetakLaporanProyek']
        )->name('admin.cetak-laporan-proyek');

        // ====================================================
        // LAPORAN TAHUNAN ADMIN
        // ====================================================

        Route::get(
            '/admin/laporan-tahunan',
            [LaporanController::class, 'adminLaporanTahunan']
        )->name('admin.laporan-tahunan');

        Route::get(
            '/admin/laporan-tahunan/preview',
            [LaporanController::class, 'adminPreviewLaporanTahunan']
        )->name('admin.laporan-tahunan.preview');

        Route::get(
            '/admin/laporan-tahunan/download',
            [LaporanController::class, 'adminDownloadLaporanTahunan']
        )->name('admin.laporan-tahunan.download');

        // ====================================================
        // MONITORING ADMIN
        // ====================================================

        Route::get(
            '/admin/monitoring',
            [MonitoringController::class, 'indexAdmin']
        )->name('admin.monitoring');

        Route::get(
            '/monitoring/{id}/get-data',
            [MonitoringController::class, 'getData']
        )->name('monitoring.get-data');

        Route::post(
            '/monitoring/{id}/komentar',
            [MonitoringController::class, 'komentar']
        )->name('monitoring.komentar');

        // ====================================================
        // VERIFIKASI MONITORING
        // ====================================================

        Route::get(
            '/admin/monitoring/verifikasi',
            [MonitoringController::class, 'pendingVerifikasi']
        )->name('admin.monitoring.verifikasi');

        Route::get(
            '/admin/monitoring/verifikasi/{id}/data',
            [MonitoringController::class, 'getVerifikasiData']
        )->name('admin.monitoring.verifikasi.data');

        Route::post(
            '/admin/monitoring/approve/{id}',
            [MonitoringController::class, 'approve']
        )->name('admin.monitoring.approve');

        Route::post(
            '/admin/monitoring/reject/{id}',
            [MonitoringController::class, 'reject']
        )->name('admin.monitoring.reject');

        // ====================================================
        // KOMENTAR MASYARAKAT ADMIN
        // ====================================================

        Route::get(
            '/admin/komentar-masyarakat',
            [KomentarMasyarakatController::class, 'indexAdmin']
        )->name('admin.komentar-masyarakat');

        Route::post(
            '/admin/komentar-masyarakat/approve/{id}',
            [KomentarMasyarakatController::class, 'approve']
        )->name('admin.komentar-masyarakat.approve');

        Route::post(
            '/admin/komentar-masyarakat/reject/{id}',
            [KomentarMasyarakatController::class, 'reject']
        )->name('admin.komentar-masyarakat.reject');

        Route::delete(
            '/admin/komentar-masyarakat/{id}',
            [KomentarMasyarakatController::class, 'destroy']
        )->name('admin.komentar-masyarakat.destroy');

    });

    // ========================================================
    // ========================================================
    // REKANAN
    // ========================================================
    // ========================================================

    Route::middleware(['role:rekanan'])->group(function () {

        // ====================================================
        // DASHBOARD REKANAN
        // ====================================================

        Route::get(
            '/rekanan/dashboard',
            [RekananController::class, 'dashboard']
        )->name('rekanan.dashboard');

        // ====================================================
        // PROYEK TERSEDIA
        // ====================================================

        Route::get(
            '/rekanan/available',
            [RekananController::class, 'available']
        )->name('rekanan.available');

        // ====================================================
        // PROYEK SAYA
        // ====================================================

        Route::get(
            '/rekanan/my-projects',
            [RekananController::class, 'myProjects']
        )->name('rekanan.my-projects');

        // ====================================================
        // AJUKAN PENAWARAN
        // ====================================================

        Route::post(
            '/rekanan/bid/{id}',
            [RekananController::class, 'apply']
        )->name('rekanan.bid');

        // ====================================================
        // TERIMA PROYEK
        // ====================================================

        Route::post(
            '/rekanan/approve/{id}',
            [RekananController::class, 'approve']
        )->name('rekanan.approve');

        // ====================================================
        // TOLAK PROYEK
        // ====================================================

        Route::post(
            '/rekanan/reject/{id}',
            [RekananController::class, 'reject']
        )->name('rekanan.reject');

        // ====================================================
        // MONITORING REKANAN
        // ====================================================

        Route::resource(
            'monitoring',
            MonitoringController::class
        );

        Route::get(
            '/monitoring/{id}/get-data',
            [MonitoringController::class, 'getData']
        )->name('monitoring.get-data');

        Route::post(
            '/upload-image',
            [MonitoringController::class, 'uploadImage']
        )->name('upload.image');

        // ====================================================
        // CV REKANAN
        // ====================================================

        Route::post(
            '/rekanan/upload-cv',
            [RekananController::class, 'uploadCv']
        )->name('rekanan.upload-cv');

        Route::delete(
            '/rekanan/delete-cv',
            [RekananController::class, 'deleteCv']
        )->name('rekanan.delete-cv');

        // ====================================================
        // EVALUASI REKANAN
        // REKANAN
        // ====================================================

        // Statis dulu agar tidak tertangkap oleh parameter {id}
        Route::get(
            '/evaluasi-rekanan',
            [EvaluasiRekananController::class, 'index']
        )->name('evaluasi-rekanan.index');

        Route::get(
            '/evaluasi-rekanan/create/{proyekId}',
            [EvaluasiRekananController::class, 'create']
        )->where('proyekId', '[0-9]+')
            ->name('evaluasi-rekanan.create');

        Route::post(
            '/evaluasi-rekanan',
            [EvaluasiRekananController::class, 'store']
        )->name('evaluasi-rekanan.store');

        Route::get(
            '/evaluasi-rekanan/{id}/edit',
            [EvaluasiRekananController::class, 'edit']
        )->where('id', '[0-9]+')
            ->name('evaluasi-rekanan.edit');

        Route::put(
            '/evaluasi-rekanan/{id}',
            [EvaluasiRekananController::class, 'update']
        )->where('id', '[0-9]+')
            ->name('evaluasi-rekanan.update');

    });

    // ========================================================
    // EVALUASI REKANAN - DETAIL
    // SEMUA ROLE YANG BERHAK
    // ========================================================

    // Diletakkan SETELAH route create/edit/update
    // agar tidak bentrok dengan parameter {id}.

    Route::get(
        '/evaluasi-rekanan/{id}',
        [EvaluasiRekananController::class, 'show']
    )->where('id', '[0-9]+')
        ->name('evaluasi-rekanan.show');

    // ========================================================
    // EVALUASI REKANAN
    // ADMIN & KEPALA DESA
    // ========================================================

    Route::middleware(['role:admin,kepala_desa'])->group(function () {

        Route::get(
            '/admin/evaluasi-rekanan',
            [EvaluasiRekananController::class, 'adminIndex']
        )->name('admin.evaluasi-rekanan');

        Route::post(
            '/admin/evaluasi-rekanan/{id}/verifikasi',
            [EvaluasiRekananController::class, 'verifikasi']
        )->whereNumber('id')
            ->name('admin.evaluasi-rekanan.verifikasi');

        Route::get(
            '/admin/evaluasi-rekanan/kinerja/{rekananId}',
            [EvaluasiRekananController::class, 'kinerjaRekanan']
        )->whereNumber('rekananId')
            ->name('admin.evaluasi-rekanan.kinerja');

        Route::get(
            '/admin/kredibilitas-rekanan',
            [EvaluasiRekananController::class, 'kredibilitasRekanan']
        )->name('admin.kredibilitas-rekanan');

    });

    // ========================================================
    // ========================================================
    // KEPALA DESA
    // ========================================================
    // ========================================================

    Route::middleware(['role:kepala_desa'])->group(function () {

        // ====================================================
        // DAFTAR PROYEK KEPALA DESA
        // ====================================================

        Route::get(
            '/kades/proyek',
            [ProyekController::class, 'allForKades']
        )->name('kades.proyek');

        // ====================================================
        // DETAIL PROYEK KEPALA DESA
        // ====================================================

        Route::get(
            '/kades/proyek/{id}',
            [ProyekController::class, 'showForKades']
        )->name('kades.proyek.detail');

        // ====================================================
        // PENAWARAN REKANAN - KEPALA DESA
        // ====================================================

        Route::post(
            '/kades/proyek/{proyekId}/penawaran/{rekananProyekId}/setujui',
            [ProyekController::class, 'setujuiPenawaranKades']
        )->name('kades.penawaran.setujui');

        Route::post(
            '/kades/proyek/{proyekId}/penawaran/{rekananProyekId}/pertimbangkan',
            [ProyekController::class, 'pertimbangkanPenawaranKades']
        )->name('kades.penawaran.pertimbangkan');

        Route::post(
            '/kades/proyek/{proyekId}/penawaran/{rekananProyekId}/tolak',
            [ProyekController::class, 'tolakPenawaranKades']
        )->name('kades.penawaran.tolak');

        // ====================================================
        // STATUS REKANAN KEPALA DESA
        // ====================================================

        Route::get(
            '/kades/rekanan-status',
            [RekananController::class, 'statusForKades']
        )->name('kades.rekanan-status');

        // ====================================================
        // LAPORAN PROYEK KEPALA DESA
        // ====================================================

        Route::get(
            '/kades/laporan-proyek',
            [LaporanController::class, 'laporanProyekKades']
        )->name('kades.laporan-proyek');

        Route::get(
            '/kades/laporan-progres/{id}',
            [LaporanController::class, 'laporanProgresKades']
        )->name('kades.laporan-progres');

        // ====================================================
        // LAPORAN TAHUNAN KEPALA DESA
        // ====================================================

        Route::get(
            '/kades/laporan-tahunan',
            [LaporanController::class, 'kadesLaporanTahunan']
        )->name('kades.laporan-tahunan');

        Route::get(
            '/kades/laporan-tahunan/preview',
            [LaporanController::class, 'kadesPreviewLaporanTahunan']
        )->name('kades.laporan-tahunan.preview');

        Route::get(
            '/kades/laporan-tahunan/download',
            [LaporanController::class, 'kadesDownloadLaporanTahunan']
        )->name('kades.laporan-tahunan.download');

        // ====================================================
        // CETAK LAPORAN KEPALA DESA
        // ====================================================

        Route::get(
            '/kades/cetak-laporan/{id}',
            [LaporanController::class, 'cetakLaporanKades']
        )->name('kades.cetak-laporan');

    });

});