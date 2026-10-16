@extends('layouts.app')

@section('content')
    @php
        $user = Auth::user();
        $level = $user->level;
    @endphp

    <!-- ========== STATS SUMMARY ========== -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white border-0 rounded-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">TOTAL PROYEK</small>
                            <h2 class="mb-0 mt-2">{{ $totalProyek }}</h2>
                        </div>
                        <i class="fas fa-building fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white border-0 rounded-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">SEDANG BERJALAN</small>
                            <h2 class="mb-0 mt-2">{{ $proyekBerjalan }}</h2>
                        </div>
                        <i class="fas fa-spinner fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white border-0 rounded-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">SUDAH SELESAI</small>
                            <h2 class="mb-0 mt-2">{{ $proyekSelesai }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white border-0 rounded-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">TOTAL ANGGARAN</small>
                            <h5 class="mb-0 mt-2">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h5>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== WELCOME SECTION ========== -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm"
                style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-white">
                            <h4 class="mb-1">
                                <i class="fas fa-hand-peace me-2"></i>
                                Selamat Datang, {{ $user->nama_user }}!
                            </h4>
                            <p class="mb-0 opacity-75">
                                @if ($level == 'admin')
                                    Anda memiliki akses penuh untuk mengelola proyek dan sistem.
                                @elseif($level == 'rekanan')
                                    Anda dapat melihat proyek tersedia, menyetujui, dan melakukan monitoring.
                                @else
                                    Anda dapat memantau seluruh proyek dan status rekanan.
                                @endif
                            </p>
                        </div>
                        <div class="text-white text-end">
                            <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                            <div class="small mt-1">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== CHART + RECENT PROJECTS ========== -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i> Status Proyek</h5>
                        <span class="badge bg-light text-dark rounded-pill">
                            <i class="fas fa-chart-simple me-1"></i> {{ date('Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-clock me-2 text-primary"></i> Proyek Terbaru</h5>
                        @if ($level == 'admin')
                            <a href="{{ route('proyek.index') }}" class="btn btn-sm btn-link text-decoration-none">
                                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @elseif($level == 'rekanan')
                            <a href="{{ route('rekanan.available') }}" class="btn btn-sm btn-link text-decoration-none">
                                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('kades.proyek') }}" class="btn btn-sm btn-link text-decoration-none">
                                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Proyek</th>
                                    <th>Status</th>
                                    <th>Progres</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProyek as $p)
                                    <tr>
                                        <td class="fw-bold">{{ Str::limit($p->nama_pembangunan, 30) }}</td>
                                        <td>
                                            @if ($p->status == 'direncanakan')
                                                <span class="badge bg-secondary rounded-pill px-3 py-2">Direncanakan</span>
                                            @elseif($p->status == 'berjalan')
                                                <span
                                                    class="badge bg-warning text-dark rounded-pill px-3 py-2">Berjalan</span>
                                            @else
                                                <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                                            @endif
                                        </td>
                                        <td style="width: 35%">
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar {{ $p->progress == 100 ? 'bg-success' : 'bg-primary' }}"
                                                        style="width: {{ $p->progress }}%"></div>
                                                </div>
                                                <span class="small fw-bold">{{ $p->progress }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                            Belum ada data proyek
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== ROLE-BASED SECTIONS ========== -->

    {{-- ADMIN --}}
    @if ($level == 'admin')
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i> Statistik Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <div class="col-4">
                                <div class="p-3 bg-danger bg-opacity-10 rounded-3">
                                    <i class="fas fa-shield-alt fa-2x text-danger mb-2"></i>
                                    <h4 class="mb-0">{{ $totalAdmin ?? 0 }}</h4>
                                    <small class="text-muted">Admin</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                    <i class="fas fa-user-check fa-2x text-primary mb-2"></i>
                                    <h4 class="mb-0">{{ $totalRekanan ?? 0 }}</h4>
                                    <small class="text-muted">Rekanan</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                    <i class="fas fa-user-tie fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0">{{ $totalKepalaDesa ?? 0 }}</h4>
                                    <small class="text-muted">Kepala Desa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <h5 class="mb-0"><i class="fas fa-globe me-2 text-primary"></i> Status Publikasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <div class="col-6">
                                <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0">{{ $totalProyekPublished ?? 0 }}</h4>
                                    <small class="text-muted">Dipublikasi</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-secondary bg-opacity-10 rounded-3">
                                    <i class="fas fa-eye-slash fa-2x text-secondary mb-2"></i>
                                    <h4 class="mb-0">{{ $totalProyekUnpublished ?? 0 }}</h4>
                                    <small class="text-muted">Belum Dipublikasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== WIDGET VERIFIKASI MONITORING ===== -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-shield-alt me-2 text-primary"></i>
                                Verifikasi Monitoring
                                @if ($pendingVerifikasiCount > 0)
                                    <span class="badge bg-danger ms-2 rounded-pill">
                                        <i class="fas fa-clock me-1"></i> {{ $pendingVerifikasiCount }} Menunggu
                                    </span>
                                @endif
                            </h5>
                            <a href="{{ route('admin.monitoring.verifikasi') }}"
                                class="btn btn-sm btn-primary rounded-pill">
                                <i class="fas fa-arrow-right me-1"></i> Kelola Verifikasi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Statistik Verifikasi -->
                        <div class="row text-center g-3 mb-4">
                            <div class="col-3">
                                <div class="p-3 bg-warning bg-opacity-10 rounded-3">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h4 class="mb-0">{{ $pendingVerifikasiCount ?? 0 }}</h4>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0">{{ $approvedVerifikasiCount ?? 0 }}</h4>
                                    <small class="text-muted">Disetujui</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3 bg-danger bg-opacity-10 rounded-3">
                                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                    <h4 class="mb-0">{{ $rejectedVerifikasiCount ?? 0 }}</h4>
                                    <small class="text-muted">Ditolak</small>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-3 bg-secondary bg-opacity-10 rounded-3">
                                    <i class="fas fa-list fa-2x text-secondary mb-2"></i>
                                    <h4 class="mb-0">{{ $totalVerifikasiCount ?? 0 }}</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Pending Terbaru -->
                        @if (isset($recentPendingMonitoring) && $recentPendingMonitoring->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Proyek</th>
                                            <th>Tanggal</th>
                                            <th>Progres</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentPendingMonitoring as $m)
                                            <tr>
                                                <td>
                                                    <strong>{{ $m->proyek->nama_pembangunan ?? 'Proyek' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $m->proyek->lokasi ?? '-' }}</small>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary rounded-pill">{{ $m->progres }}%</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i> Pending
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.monitoring.verifikasi') }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-check me-1"></i> Verifikasi
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($pendingVerifikasiCount > 5)
                                <div class="text-center mt-2">
                                    <a href="{{ route('admin.monitoring.verifikasi') }}" class="text-primary">
                                        Lihat semua {{ $pendingVerifikasiCount }} pending <i
                                            class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h6 class="text-success">Semua progres sudah diverifikasi!</h6>
                                <p class="text-muted small">Tidak ada progres yang menunggu verifikasi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- REKANAN --}}
    @if ($level == 'rekanan')
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-folder-open fa-2x text-primary"></i>
                        </div>
                        <h3 class="mb-0">{{ $proyekTersedia ?? 0 }}</h3>
                        <small class="text-muted">Proyek Tersedia</small>
                        <div class="mt-2">
                            <a href="{{ route('rekanan.available') }}" class="btn btn-sm btn-primary rounded-pill">
                                Lihat Proyek <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <h3 class="mb-0">{{ $proyekDisetujui ?? 0 }}</h3>
                        <small class="text-muted">Proyek Disetujui</small>
                        <div class="mt-2">
                            <a href="{{ route('rekanan.my-projects') }}" class="btn btn-sm btn-success rounded-pill">
                                Lihat Proyek <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                        <h3 class="mb-0">{{ $monitoringAktif ?? 0 }}</h3>
                        <small class="text-muted">Monitoring Aktif</small>
                        <div class="mt-2">
                            <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-warning rounded-pill">
                                Lihat Monitoring <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

    {{-- KEPALA DESA (LENGKAP DENGAN GRAFIK) --}}
    @if ($level == 'kepala_desa')
        <!-- STATISTIK UTAMA -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">TOTAL PROYEK</small>
                                <h3 class="mb-0 mt-1">{{ $totalProyek }}</h3>
                            </div>
                            <i class="fas fa-building fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">PROYEK SELESAI</small>
                                <h3 class="mb-0 mt-1">{{ $proyekSelesai }}</h3>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">REKANAN TERDAFTAR</small>
                                <h3 class="mb-0 mt-1">{{ $totalRekanan ?? 0 }}</h3>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">PENAWARAN DISETUJUI</small>
                                <h3 class="mb-0 mt-1">{{ $totalApproved ?? 0 }}</h3>
                            </div>
                            <i class="fas fa-handshake fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATISTIK PENAWARAN -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-dark border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-dark-50">MENUNGGU</small>
                                <h3 class="mb-0 mt-1">{{ $totalPending ?? 0 }}</h3>
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-danger text-white border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">DITOLAK</small>
                                <h3 class="mb-0 mt-1">{{ $totalRejected ?? 0 }}</h3>
                            </div>
                            <i class="fas fa-times-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">DISETUJUI</small>
                                <h3 class="mb-0 mt-1">{{ $totalApproved ?? 0 }}</h3>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFIK -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i> Proyek Per Tahun</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="proyekPerTahunChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i> Status Proyek</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="statusPieChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- PROGRES KESELURUHAN -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2 text-primary"></i> Capaian & Progres</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Progres Keseluruhan</span>
                                        <span class="fw-bold text-success">{{ $overallProgress ?? 0 }}%</span>
                                    </div>
                                    <div class="progress" style="height: 12px;">
                                        <div class="progress-bar bg-success" style="width: {{ $overallProgress ?? 0 }}%">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Proyek Selesai</span>
                                        <span class="fw-bold text-primary">{{ $proyekSelesai }} dari
                                            {{ $totalProyek }}</span>
                                    </div>
                                    <div class="progress" style="height: 12px;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ $totalProyek > 0 ? ($proyekSelesai / $totalProyek) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Total Anggaran</span>
                                        <span class="fw-bold text-info">Rp
                                            {{ number_format($totalAnggaran, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Proyek dengan Rekanan</span>
                                        <span class="fw-bold text-success">{{ $proyekDenganRekanan ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LAPORAN STATISTIK -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i> Laporan Tersedia</h5>
                            <a href="{{ route('kades.laporan-proyek') }}"
                                class="btn btn-sm btn-link text-decoration-none">
                                Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <div class="col-md-4 col-6">
                                <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                    <i class="fas fa-building fa-2x text-primary mb-2"></i>
                                    <h4 class="mb-0">{{ $laporanPembangunan ?? 0 }}</h4>
                                    <small class="text-muted">Laporan Pembangunan</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 bg-info bg-opacity-10 rounded-3">
                                    <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                                    <h4 class="mb-0">{{ $laporanMonitoring ?? 0 }}</h4>
                                    <small class="text-muted">Laporan Monitoring</small>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h4 class="mb-0">{{ $laporanEvaluasi ?? 0 }}</h4>
                                    <small class="text-muted">Laporan Evaluasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Chart Proyek Per Tahun
                var ctx1 = document.getElementById('proyekPerTahunChart').getContext('2d');
                var proyekPerTahun = @json($proyekPerTahun ?? []);
                var tahunLabels = proyekPerTahun.map(item => item.tahun_anggaran);
                var tahunData = proyekPerTahun.map(item => item.total);

                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: tahunLabels.length > 0 ? tahunLabels : ['Belum Ada Data'],
                        datasets: [{
                            label: 'Jumlah Proyek',
                            data: tahunData.length > 0 ? tahunData : [0],
                            backgroundColor: ['#1e3c72', '#2a5298', '#667eea', '#764ba2', '#10b981'],
                            borderRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Chart Status Proyek (Pie)
                var ctx2 = document.getElementById('statusPieChart').getContext('2d');
                var statusData = @json($statusChart ?? []);
                var statusLabels = statusData.map(item => {
                    if (item.status == 'direncanakan') return '📋 Direncanakan';
                    if (item.status == 'berjalan') return '⚡ Berjalan';
                    return '✅ Selesai';
                });
                var statusValues = statusData.map(item => item.total);
                var statusColors = ['#6c757d', '#ffc107', '#28a745'];

                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels.length > 0 ? statusLabels : ['Belum Ada Data'],
                        datasets: [{
                            data: statusValues.length > 0 ? statusValues : [1],
                            backgroundColor: statusColors,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 11
                                    },
                                    usePointStyle: true,
                                    boxWidth: 10,
                                    padding: 15
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            </script>
        @endpush
    @endif

@endsection

@push('styles')
    <style>
        .hover-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .progress {
            background-color: #e9ecef;
        }

        .card-stats {
            transition: all 0.3s ease;
        }

        .card-stats:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('statusChart').getContext('2d');
            var chartData = @json($chartData);

            var labels = chartData.map(item => {
                if (item.status == 'direncanakan') return '📋 Direncanakan';
                if (item.status == 'berjalan') return '⚡ Sedang Berjalan';
                return '✅ Selesai';
            });

            var data = chartData.map(item => item.total);
            var colors = ['#6c757d', '#ffc107', '#28a745'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 0,
                        hoverOffset: 10,
                        cutout: '60%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 12
                                },
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 15
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush