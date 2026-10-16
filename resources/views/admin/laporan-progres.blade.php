@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h4><i class="fas fa-chart-line text-primary me-2"></i> Laporan Progres Proyek</h4>
        <div>
            <a href="{{ route('admin.laporan-proyek') }}" class="btn btn-secondary btn-sm rounded-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
            <a href="{{ route('admin.cetak-laporan-proyek', $proyek->id_pembangunan) }}" class="btn btn-danger btn-sm rounded-3" target="_blank">
                <i class="fas fa-file-pdf me-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <!-- ===== INFO PROYEK ===== -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold">{{ $proyek->nama_pembangunan }}</h5>
                    <p class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>{{ $proyek->lokasi }}</p>
                    <div class="row mt-3">
                        <div class="col-md-3 col-6">
                            <small class="text-muted">Status</small>
                            <div>
                                @if($proyek->status == 'direncanakan')
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Direncanakan</span>
                                @elseif($proyek->status == 'berjalan')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Berjalan</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-2">Selesai</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted">Progres</small>
                            <h5 class="fw-bold text-primary">{{ $proyek->progress }}%</h5>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted">Rekanan</small>
                            <div>
                                @if(isset($rekanan) && $rekanan)
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="fas fa-user-check me-1"></i> {{ $rekanan->nama_user }}
                                    </span>
                                @elseif($proyek->rekananSetuju->count() > 0)
                                    @foreach($proyek->rekananSetuju as $r)
                                        <span class="badge bg-primary rounded-pill px-3 py-2">{{ $r->nama_user }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted">Anggaran</small>
                            <h6 class="fw-bold text-success">Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                    @if(isset($hargaDeal) && $hargaDeal)
                        <div class="mt-2 pt-2 border-top">
                            <small class="text-muted">Harga Deal dengan Rekanan</small>
                            <h6 class="fw-bold text-primary">Rp {{ number_format($hargaDeal, 0, ',', '.') }}</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body text-center">
                    <canvas id="progressChart" width="150" height="150"></canvas>
                    <h6 class="mt-2 fw-bold">{{ $proyek->progress }}% Selesai</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RINCIAN ANGGARAN ===== -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                <div class="card-body text-center py-3">
                    <small class="text-white-50">TOTAL ANGGARAN</small>
                    <h4 class="mb-0 fw-bold">Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body text-center py-3">
                    <small class="text-white-50">DANA TERPAKAI (Approved)</small>
                    <h4 class="mb-0 fw-bold">Rp {{ number_format($totalDanaTerpakai ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 {{ ($sisaAnggaran ?? 0) > 0 ? 'bg-warning text-dark' : 'bg-danger text-white' }}">
                <div class="card-body text-center py-3">
                    <small class="{{ ($sisaAnggaran ?? 0) > 0 ? 'text-dark-50' : 'text-white-50' }}">SISA ANGGARAN</small>
                    <h4 class="mb-0 fw-bold">Rp {{ number_format($sisaAnggaran ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RINCIAN PER TAHAP MONITORING ===== -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-list-ul me-2 text-primary"></i> Rincian Penggunaan Anggaran per Tahap</h6>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                <i class="fas fa-check-circle me-1"></i> 
                {{ $proyek->monitorings->where('status_verifikasi', 'approved')->count() }} Tahap Terverifikasi
            </span>
        </div>
        <div class="card-body">
            @php
                $approvedMonitorings = $proyek->monitorings->where('status_verifikasi', 'approved')->sortBy('tanggal_monitoring');
            @endphp

            @if($approvedMonitorings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Tanggal</th>
                                <th width="10%">Progres</th>
                                <th width="20%">Status Pekerjaan</th>
                                <th width="20%">Dana Terpakai</th>
                                <th width="20%">% dari Anggaran</th>
                                <th width="10%">Kumulatif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kumulatif = 0;
                            @endphp
                            @foreach($approvedMonitorings as $index => $m)
                                @php
                                    $kumulatif += $m->dana_terpakai ?? 0;
                                    $persentase = $proyek->anggaran > 0 ? round(($m->dana_terpakai / $proyek->anggaran) * 100, 2) : 0;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">{{ $m->progres }}%</span>
                                    </td>
                                    <td>
                                        <strong>{{ $m->status_progres }}</strong>
                                        @if($m->keterangan)
                                            <br>
                                            <small class="text-muted">{{ Str::limit(strip_tags($m->keterangan), 50) }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold text-success">
                                        Rp {{ number_format($m->dana_terpakai ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">{{ $persentase }}%</span>
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp {{ number_format($kumulatif, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th colspan="4" class="text-end fw-bold">TOTAL</th>
                                <th class="text-end fw-bold text-success">
                                    Rp {{ number_format($totalDanaTerpakai ?? 0, 0, ',', '.') }}
                                </th>
                                <th class="text-center fw-bold">
                                    {{ $proyek->anggaran > 0 ? round(($totalDanaTerpakai / $proyek->anggaran) * 100, 2) : 0 }}%
                                </th>
                                <th class="text-end fw-bold text-primary">
                                    Rp {{ number_format($totalDanaTerpakai ?? 0, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Progress Bar Anggaran -->
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold">Penggunaan Anggaran</span>
                        <span class="fw-semibold text-primary">
                            {{ $proyek->anggaran > 0 ? round(($totalDanaTerpakai / $proyek->anggaran) * 100, 2) : 0 }}%
                        </span>
                    </div>
                    <div class="progress" style="height: 20px;">
                        @php
                            $persentaseAnggaran = $proyek->anggaran > 0 ? ($totalDanaTerpakai / $proyek->anggaran) * 100 : 0;
                            $progressClass = $persentaseAnggaran >= 100 ? 'bg-danger' : ($persentaseAnggaran >= 80 ? 'bg-warning' : 'bg-success');
                        @endphp
                        <div class="progress-bar {{ $progressClass }} fw-bold" 
                             style="width: {{ min($persentaseAnggaran, 100) }}%;">
                            {{ number_format($persentaseAnggaran, 1) }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Rp 0</small>
                        <small class="text-muted">Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</small>
                    </div>
                </div>

            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Belum ada tahap monitoring yang terverifikasi</h6>
                    <p class="text-muted small">Data monitoring akan muncul setelah disetujui oleh Admin</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ===== TIMELINE MONITORING (Semua Status) ===== -->
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-history me-2 text-primary"></i> Riwayat Monitoring (Semua Status)</h6>
            <div class="d-flex gap-2">
                <span class="badge bg-warning text-dark rounded-pill">
                    <i class="fas fa-clock me-1"></i> Pending: {{ $proyek->monitorings->where('status_verifikasi', 'pending')->count() }}
                </span>
                <span class="badge bg-success rounded-pill">
                    <i class="fas fa-check-circle me-1"></i> Approved: {{ $proyek->monitorings->where('status_verifikasi', 'approved')->count() }}
                </span>
                <span class="badge bg-danger rounded-pill">
                    <i class="fas fa-times-circle me-1"></i> Rejected: {{ $proyek->monitorings->where('status_verifikasi', 'rejected')->count() }}
                </span>
            </div>
        </div>
        <div class="card-body">
            @if($proyek->monitorings->count() > 0)
                <div class="timeline">
                    @foreach($proyek->monitorings->sortBy('tanggal_monitoring') as $m)
                        <div class="timeline-item mb-3 p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <strong class="text-primary">
                                        {{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}
                                    </strong>
                                    <span class="badge bg-primary ms-2">{{ $m->progres }}%</span>
                                    <span class="badge ms-2 
                                        @if($m->status_verifikasi == 'pending') bg-warning text-dark
                                        @elseif($m->status_verifikasi == 'approved') bg-success
                                        @else bg-danger
                                        @endif">
                                        @if($m->status_verifikasi == 'pending') ⏳ Pending
                                        @elseif($m->status_verifikasi == 'approved') ✅ Approved
                                        @else ❌ Rejected
                                        @endif
                                    </span>
                                    @if($m->status_verifikasi == 'rejected' && $m->catatan_verifikasi)
                                        <span class="badge bg-secondary" style="cursor:pointer;" 
                                              onclick="alert('Catatan Penolakan:\n\n{{ addslashes($m->catatan_verifikasi) }}')">
                                            <i class="fas fa-info-circle me-1"></i> Lihat Catatan
                                        </span>
                                    @endif
                                </div>
                                @if($m->dana_terpakai)
                                    <span class="badge bg-success">
                                        Rp {{ number_format($m->dana_terpakai, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $m->progres }}%"></div>
                            </div>
                            <p class="mb-0"><strong>{{ $m->status_progres }}</strong></p>
                            @if($m->keterangan)
                                <p class="text-muted small mb-0">{{ strip_tags($m->keterangan) }}</p>
                            @endif
                            @if($m->komentar_admin)
                                <div class="mt-1 p-2 bg-warning bg-opacity-10 rounded-3 border-start border-3 border-warning">
                                    <small class="text-warning"><i class="fas fa-comment me-1"></i> <strong>Komentar Admin:</strong></small>
                                    <p class="mb-0 small">{{ $m->komentar_admin }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center py-4">Belum ada data monitoring</p>
            @endif
        </div>
    </div>
</div>

<style>
    .timeline-item {
        border-left: 3px solid #1e3c72;
        padding-left: 20px;
        position: relative;
        transition: all 0.3s ease;
    }
    .timeline-item:hover {
        background: #f8f9fa !important;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 20px;
        width: 10px;
        height: 10px;
        background: #1e3c72;
        border-radius: 50%;
    }
    .timeline-item:last-child {
        border-left-color: #10b981;
    }
    .timeline-item:last-child::before {
        background: #10b981;
    }
    .table tfoot {
        font-weight: 600;
    }
    .progress-bar {
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('progressChart').getContext('2d');
    var progress = {{ $proyek->progress }};
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Progres', 'Sisa'],
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#10b981', '#e5e7eb'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            cutout: '70%'
        }
    });
</script>
@endpush
@endsection