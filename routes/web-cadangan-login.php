<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\RekananController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// ==========================================
// GROUP DENGAN MIDDLEWARE AUTH
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // ==========================================
    // PROFILE (Semua role)
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    // ==========================================
    // ADMIN (level: admin)
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {
        // Users
        Route::resource('users', UserController::class);

        // Proyek (CRUD + Publikasi)
        Route::resource('proyek', ProyekController::class);
        Route::get('/proyek/publish/{id}', [ProyekController::class, 'publish'])->name('proyek.publish');
        Route::get('/proyek/{id}/get-data', [ProyekController::class, 'getData'])->name('proyek.get-data');

        // Evaluasi
        Route::resource('evaluasi', EvaluasiController::class);
        Route::get('/evaluasi/{id}/get-data', [EvaluasiController::class, 'getData'])->name('evaluasi.get-data');
        Route::get('/evaluasi/pembangunan/{id}', [EvaluasiController::class, 'getByPembangunan'])->name('evaluasi.getByPembangunan');

        // Dokumentasi
        Route::resource('dokumentasi', DokumentasiController::class);
        Route::get('/dokumentasi/group/{groupId}/get-data', [DokumentasiController::class, 'getData'])->name('dokumentasi.get-data');
        Route::post('/dokumentasi/delete-photo', [DokumentasiController::class, 'deletePhoto'])->name('dokumentasi.delete-photo');
    });

    // ==========================================
    // REKANAN (level: rekanan)
    // ==========================================
    Route::middleware(['role:rekanan'])->group(function () {
        // Proyek Tersedia & Proyek Saya
        Route::get('/rekanan/available', [RekananController::class, 'available'])->name('rekanan.available');
        Route::get('/rekanan/my-projects', [RekananController::class, 'myProjects'])->name('rekanan.my-projects');

        // Approve / Reject Proyek
        Route::post('/rekanan/approve/{id}', [RekananController::class, 'approve'])->name('rekanan.approve');
        Route::post('/rekanan/reject/{id}', [RekananController::class, 'reject'])->name('rekanan.reject');

        // Monitoring
        Route::resource('monitoring', MonitoringController::class);
        Route::get('/monitoring/{id}/get-data', [MonitoringController::class, 'getData'])->name('monitoring.get-data');
        Route::get('/monitoring/pembangunan/{id}', [MonitoringController::class, 'getByPembangunan'])->name('monitoring.getByPembangunan');

        // Upload image untuk Summernote
        Route::post('/upload-image', [MonitoringController::class, 'uploadImage'])->name('upload.image');
    });

    // ==========================================
    // KEPALA DESA (level: kepala_desa)
    // ==========================================
    Route::middleware(['role:kepala_desa'])->group(function () {
        // Lihat semua proyek
        Route::get('/kades/proyek', [ProyekController::class, 'allForKades'])->name('kades.proyek');

        // Detail Proyek untuk Kepala Desa
        Route::get('/kades/proyek/{id}', [ProyekController::class, 'showForKades'])->name('kades.proyek.detail');

        // Status Rekanan
        Route::get('/kades/rekanan-status', [RekananController::class, 'statusForKades'])->name('kades.rekanan-status');

        // Laporan
        Route::resource('laporan', LaporanController::class);
        Route::get('/laporan/cetak-pdf/{id}', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak-pdf');
        Route::get('/laporan/preview-pdf/{id}', [LaporanController::class, 'previewPdf'])->name('laporan.preview-pdf'); // TAMBAHKAN INI
    });

});
