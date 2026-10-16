@extends('layouts.app')

@section('content')
<div class="table-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fas fa-calendar-alt me-2 text-primary"></i> Laporan Tahunan Proyek
        </h5>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-primary rounded-pill px-3 py-2">
                <i class="fas fa-file me-1"></i> Total: {{ $totalProyek }} Proyek
            </span>
            <span class="badge bg-success rounded-pill px-3 py-2">
                <i class="fas fa-check-circle me-1"></i> Selesai: {{ $totalSelesai ?? $proyeks->where('status', 'selesai')->count() }}
            </span>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                <i class="fas fa-spinner me-1"></i> Berjalan: {{ $totalBerjalan ?? $proyeks->where('status', 'berjalan')->count() }}
            </span>
            <span class="badge bg-secondary rounded-pill px-3 py-2">
                <i class="fas fa-clock me-1"></i> Direncanakan: {{ $totalDirencanakan ?? $proyeks->where('status', 'direncanakan')->count() }}
            </span>
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    <div class="card border-0 rounded-3 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('kades.laporan-tahunan') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Tahun Dari</label>
                    <select name="tahun_dari" class="form-select form-select-sm rounded-3">
                        <option value="">Semua</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ request('tahun_dari') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Tahun Sampai</label>
                    <select name="tahun_sampai" class="form-select form-select-sm rounded-3">
                        <option value="">Semua</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ request('tahun_sampai') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Status</label>
                    <select name="status" class="form-select form-select-sm rounded-3">
                        <option value="">Semua Status</option>
                        <option value="direncanakan" {{ request('status') == 'direncanakan' ? 'selected' : '' }}>📋 Direncanakan</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>⚡ Sedang Berjalan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 flex-grow-1">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                    <a href="{{ route('kades.laporan-tahunan') }}" class="btn btn-secondary btn-sm rounded-3">
                        <i class="fas fa-undo me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== STATISTIK ===== -->
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="card bg-primary text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">TOTAL ANGGARAN</small>
                            <h4 class="mb-0">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card bg-success text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">RATA-RATA ANGGARAN</small>
                            <h4 class="mb-0">Rp {{ $totalProyek > 0 ? number_format($totalAnggaran / $totalProyek, 0, ',', '.') : '0' }}</h4>
                        </div>
                        <i class="fas fa-chart-bar fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card bg-warning text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">TOTAL PROYEK</small>
                            <h4 class="mb-0">{{ $totalProyek }}</h4>
                        </div>
                        <i class="fas fa-building fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card bg-info text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">PERIODE</small>
                            <h5 class="mb-0">
                                {{ request('tahun_dari') ?: 'Semua' }} - {{ request('tahun_sampai') ?: 'Semua' }}
                            </h5>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== REKAP ANGGARAN ===== -->
    <div class="row mb-4">
        <div class="col-md-4 mb-2">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted">TOTAL DANA TERPAKAI</small>
                    <h4 class="mb-0 text-danger">
                        Rp {{ number_format($totalDanaTerpakai ?? 0, 0, ',', '.') }}
                    </h4>
                    <small class="text-muted">Berdasarkan seluruh pengeluaran proyek</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted">SISA ANGGARAN</small>
                    <h4 class="mb-0 text-success">
                        Rp {{ number_format($totalSisaAnggaran ?? ($totalAnggaran - ($totalDanaTerpakai ?? 0)), 0, ',', '.') }}
                    </h4>
                    <small class="text-muted">Anggaran dikurangi pengeluaran</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card border-0 rounded-3 shadow-sm h-100">
                <div class="card-body py-3">
                    <small class="text-muted">REALISASI ANGGARAN</small>
                    <h4 class="mb-0 text-primary">
                        Rp {{ number_format($totalAnggaran > 0 ? (($totalDanaTerpakai ?? 0) / $totalAnggaran) * 100 : 0, 2, ',', '.') }}%
                    </h4>
                    <small class="text-muted">Persentase penggunaan anggaran</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== GRAFIK ===== -->

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i> Status Proyek</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i> Proyek Per Tahun</h6>
                </div>
                <div class="card-body">
                    <canvas id="tahunChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABEL PROYEK + RINCIAN PENGELUARAN ===== -->
    <div class="mb-3">
        <h5 class="fw-bold mb-1">
            <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
            Rekap Penggunaan Anggaran Proyek
        </h5>
        <small class="text-muted">
            Setiap proyek dilengkapi rincian pengeluaran untuk transparansi penggunaan anggaran.
        </small>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" id="dataTable">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Proyek</th>
                    <th>Lokasi</th>
                    <th>Tahun</th>
                    <th>Anggaran</th>
                    <th>Terpakai</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th>Rekanan</th>
                    <th>Progres</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proyeks as $p)
                    @php
                        $pengeluarans = $p->pengeluarans ?? collect();
                        $danaTerpakai = $pengeluarans->sum('jumlah');
                        $sisaAnggaran = max(0, (float) $p->anggaran - (float) $danaTerpakai);
                        $persentaseAnggaran = (float) $p->anggaran > 0
                            ? min(100, ($danaTerpakai / $p->anggaran) * 100)
                            : 0;
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration + ($proyeks->currentPage() - 1) * $proyeks->perPage() }}</td>
                        <td>
                            <strong>{{ $p->nama_pembangunan }}</strong>
                            <br>
                            <small class="text-muted">{{ $p->bidang_pekerjaan ?: 'Umum' }}</small>
                        </td>
                        <td>{{ $p->lokasi }}</td>
                        <td>{{ $p->tahun_anggaran }}</td>
                        <td>Rp {{ number_format($p->anggaran, 0, ',', '.') }}</td>
                        <td class="text-danger fw-semibold">
                            Rp {{ number_format($danaTerpakai, 0, ',', '.') }}
                        </td>
                        <td class="text-success fw-semibold">
                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($p->status == 'direncanakan')
                                <span class="badge bg-secondary rounded-pill">Direncanakan</span>
                            @elseif($p->status == 'berjalan')
                                <span class="badge bg-warning text-dark rounded-pill">Berjalan</span>
                            @else
                                <span class="badge bg-success rounded-pill">Selesai</span>
                            @endif
                        </td>
                        <td>
                            @if($p->rekananSetuju && $p->rekananSetuju->count())
                                @foreach($p->rekananSetuju as $rekanan)
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $rekanan->nama_user }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-bold mb-1">{{ $p->progress }}%</div>
                            <div class="progress" style="height:7px;">
                                <div class="progress-bar {{ $p->progress == 100 ? 'bg-success' : 'bg-primary' }}"
                                     style="width: {{ min(100, max(0, $p->progress)) }}%"></div>
                            </div>
                        </td>
                    </tr>

                    <tr class="table-light">
                        <td colspan="10" class="p-0">
                            <div class="p-3 border-top border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>
                                        <i class="fas fa-receipt me-2 text-primary"></i>
                                        Rincian Pengeluaran: {{ $p->nama_pembangunan }}
                                    </strong>
                                    <span class="badge bg-danger">
                                        Total Rp {{ number_format($danaTerpakai, 0, ',', '.') }}
                                    </span>
                                </div>

                                @if($pengeluarans->count())
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0 bg-white">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th width="15%">Kategori</th>
                                                    <th>Uraian</th>
                                                    <th width="10%">Volume</th>
                                                    <th width="15%">Harga Satuan</th>
                                                    <th width="15%">Jumlah</th>
                                                    <th width="12%">Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pengeluarans as $pengeluaran)
                                                    @php
                                                        $labelKategori = [
                                                            'upah_tenaga_kerja' => 'Upah Tenaga Kerja',
                                                            'material' => 'Material',
                                                            'sewa_alat' => 'Sewa Alat',
                                                            'transportasi' => 'Transportasi',
                                                            'administrasi' => 'Administrasi',
                                                            'lainnya' => 'Lainnya',
                                                        ];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $labelKategori[$pengeluaran->kategori] ?? ucfirst(str_replace('_', ' ', $pengeluaran->kategori)) }}</td>
                                                        <td>
                                                            <strong>{{ $pengeluaran->uraian }}</strong>
                                                            @if($pengeluaran->keterangan)
                                                                <br><small class="text-muted">{{ $pengeluaran->keterangan }}</small>
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($pengeluaran->volume, 2, ',', '.') }} {{ $pengeluaran->satuan }}</td>
                                                        <td>Rp {{ number_format($pengeluaran->harga_satuan, 0, ',', '.') }}</td>
                                                        <td class="fw-bold text-danger">Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                                                        <td>{{ optional($pengeluaran->tanggal_pengeluaran)->format('d/m/Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-light border mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Belum ada rincian penggunaan anggaran untuk proyek ini.
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Belum ada data proyek
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $proyeks->links() }}
    </div>

    <!-- ===== TOMBOL PDF & CETAK ===== -->
    <div class="mt-3 d-flex justify-content-end gap-2 flex-wrap">
        <form action="{{ route('kades.laporan-tahunan.preview') }}" method="GET" class="d-inline" target="_blank">
            @foreach(['tahun_dari', 'tahun_sampai', 'status'] as $param)
                @if(request($param))
                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                @endif
            @endforeach
            <button type="submit" class="btn btn-info btn-sm rounded-3">
                <i class="fas fa-eye me-2"></i> Preview PDF
            </button>
        </form>
        <form action="{{ route('kades.laporan-tahunan.download') }}" method="GET" class="d-inline">
            @foreach(['tahun_dari', 'tahun_sampai', 'status'] as $param)
                @if(request($param))
                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                @endif
            @endforeach
            <button type="submit" class="btn btn-danger btn-sm rounded-3">
                <i class="fas fa-file-pdf me-2"></i> Download PDF
            </button>
        </form>
        <button class="btn btn-secondary btn-sm rounded-3" onclick="window.print()">
            <i class="fas fa-print me-2"></i> Cetak
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .table td {
        vertical-align: middle;
    }
    .progress {
        background-color: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function () {
    // ==========================================
    // CHART STATUS PROYEK (Pie)
    // ==========================================
    var statusCtx = document.getElementById('statusChart').getContext('2d');
    var statusData = @json($statusChart ?? []);
    var statusLabels = statusData.map(item => {
        if (item.status == 'direncanakan') return '📋 Direncanakan';
        if (item.status == 'berjalan') return '⚡ Sedang Berjalan';
        return '✅ Selesai';
    });
    var statusValues = statusData.map(item => item.total);
    var statusColors = ['#6c757d', '#ffc107', '#28a745'];

    new Chart(statusCtx, {
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
                        font: { size: 11 },
                        usePointStyle: true,
                        boxWidth: 10,
                        padding: 15
                    }
                }
            },
            cutout: '60%'
        }
    });

    // ==========================================
    // CHART PROYEK PER TAHUN (Bar)
    // ==========================================
    var tahunCtx = document.getElementById('tahunChart').getContext('2d');
    var tahunData = @json($proyekPerTahun ?? []);
    var tahunLabels = tahunData.map(item => item.tahun_anggaran);
    var tahunValues = tahunData.map(item => item.total);

    new Chart(tahunCtx, {
        type: 'bar',
        data: {
            labels: tahunLabels.length > 0 ? tahunLabels : ['Belum Ada Data'],
            datasets: [{
                label: 'Jumlah Proyek',
                data: tahunValues.length > 0 ? tahunValues : [0],
                backgroundColor: ['#1e3c72', '#2a5298', '#667eea', '#764ba2', '#10b981'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endpush