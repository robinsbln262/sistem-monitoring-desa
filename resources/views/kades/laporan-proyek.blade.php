@extends('layouts.app')

@section('content')
<div class="table-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i> Laporan Proyek</h5>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-primary rounded-pill px-3 py-2">
                <i class="fas fa-building me-1"></i> Total Proyek: {{ $totalProyek ?? 0 }}
            </span>
            <span class="badge bg-success rounded-pill px-3 py-2">
                <i class="fas fa-check-circle me-1"></i> Selesai: {{ $proyekSelesai ?? 0 }}
            </span>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                <i class="fas fa-spinner me-1"></i> Berjalan: {{ $proyekBerjalan ?? 0 }}
            </span>
            <span class="badge bg-secondary rounded-pill px-3 py-2">
                <i class="fas fa-clock me-1"></i> Direncanakan: {{ $proyekDirencanakan ?? 0 }}
            </span>
        </div>
    </div>

    <!-- ===== STATISTIK ===== -->
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="card bg-primary text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">TOTAL PROYEK</small>
                            <h3 class="mb-0">{{ $totalProyek ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-building fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card bg-success text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">PROYEK SELESAI</small>
                            <h3 class="mb-0">{{ $proyekSelesai ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card bg-warning text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">SEDANG BERJALAN</small>
                            <h3 class="mb-0">{{ $proyekBerjalan ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-spinner fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card bg-info text-white border-0 rounded-3 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-white-50">TOTAL ANGGARAN</small>
                            <h5 class="mb-0">Rp {{ number_format($totalAnggaran ?? 0, 0, ',', '.') }}</h5>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FILTER ===== -->
    <div class="row mb-3">
        <div class="col-md-8">
            <form method="GET" action="{{ route('kades.laporan-proyek') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Filter Tahun</label>
                    <select name="tahun" class="form-select form-select-sm rounded-3">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($tahunList ?? [] as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Filter Status</label>
                    <select name="status" class="form-select form-select-sm rounded-3">
                        <option value="">-- Semua Status --</option>
                        <option value="direncanakan" {{ request('status') == 'direncanakan' ? 'selected' : '' }}>📋 Direncanakan</option>
                        <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>⚡ Berjalan</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 w-100">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            @if(request()->has('tahun') || request()->has('status'))
                <a href="{{ route('kades.laporan-proyek') }}" class="btn btn-secondary btn-sm rounded-3">
                    <i class="fas fa-undo me-1"></i> Reset Filter
                </a>
            @endif
        </div>
    </div>

    <!-- ===== TABEL ===== -->
    <div class="table-responsive">
        <table class="table table-hover" id="dataTable">
            <thead class="table-light">
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Proyek</th>
                    <th>Lokasi</th>
                    <th>Anggaran</th>
                    <th>Dana Terpakai</th>
                    <th>Status</th>
                    <th>Rekanan</th>
                    <th>Progres</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proyeks as $index => $p)
                @php
                    // Hitung dana terpakai dari monitoring approved
                    $danaTerpakai = $p->monitorings->where('status_verifikasi', 'approved')->sum('dana_terpakai');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $p->nama_pembangunan }}</strong>
                        <br>
                        <small class="text-muted">{{ $p->tahun_anggaran }}</small>
                    </td>
                    <td>{{ $p->lokasi }}</td>
                    <td>Rp {{ number_format($p->anggaran, 0, ',', '.') }}</td>
                    <td>
                        @if($danaTerpakai > 0)
                            <span class="text-success fw-semibold">
                                Rp {{ number_format($danaTerpakai, 0, ',', '.') }}
                            </span>
                            <br>
                            <small class="text-muted">
                                {{ $p->anggaran > 0 ? round(($danaTerpakai / $p->anggaran) * 100, 1) : 0 }}% terpakai
                            </small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status == 'direncanakan')
                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                <i class="fas fa-clock me-1"></i> Direncanakan
                            </span>
                        @elseif($p->status == 'berjalan')
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                <i class="fas fa-spinner fa-pulse me-1"></i> Berjalan
                            </span>
                        @else
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($p->rekananSetuju && $p->rekananSetuju->count() > 0)
                            @foreach($p->rekananSetuju as $rekanan)
                                <span class="badge bg-primary rounded-pill">
                                    <i class="fas fa-user-check me-1"></i> {{ $rekanan->nama_user }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                <div class="progress-bar {{ $p->progress == 100 ? 'bg-success' : 'bg-primary' }}" 
                                     style="width: {{ $p->progress }}%"></div>
                            </div>
                            <span class="small fw-bold">{{ $p->progress }}%</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('kades.laporan-progres', $p->id_pembangunan) }}" 
                               class="btn btn-sm btn-info btn-action" 
                               title="Lihat Detail Laporan">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('kades.cetak-laporan', $p->id_pembangunan) }}" 
                               class="btn btn-sm btn-success btn-action" 
                               title="Cetak PDF"
                               target="_blank">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <h5>Belum ada data proyek</h5>
                        <p>Belum ada proyek yang tercatat di sistem.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $proyeks->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-action {
        padding: 4px 10px;
        margin: 2px;
        font-size: 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .table td {
        vertical-align: middle;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    $('#dataTable').DataTable({
        language: { 
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" 
        },
        pageLength: 10,
        responsive: true,
        order: [[0, 'asc']]
    });
});
</script>
@endpush