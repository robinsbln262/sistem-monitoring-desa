@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ============================================= --}}
    {{-- ALERT --}}
    {{-- ============================================= --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- HEADER --}}
    {{-- ============================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-clipboard-check text-primary me-2"></i>Evaluasi Pelaksanaan Proyek
            </h4>
            <p class="text-muted mb-0">Isi evaluasi untuk proyek yang telah selesai Anda kerjakan</p>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- STATISTIK --}}
    {{-- ============================================= --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-primary-subtle">
                            <i class="fas fa-clipboard-list text-primary"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $totalEvaluasi }}</h3>
                    <small class="text-muted d-block">Total Evaluasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-success-subtle">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-success">{{ $totalApproved }}</h3>
                    <small class="text-muted d-block">Disetujui</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-warning-subtle">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-warning">{{ $totalPending }}</h3>
                    <small class="text-muted d-block">Menunggu Verifikasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-warning-subtle">
                            <i class="fas fa-star text-warning"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h3 class="mb-0 fw-bold text-warning">
                            {{ format_angka($rataRating) }}
                        </h3>
                        <div class="d-inline-flex align-items-center gap-1">
                            @php $ratingInt = (int) round($rataRating); @endphp
                            @for($s = 1; $s <= 5; $s++)
                                @if($s <= $ratingInt)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="12" height="12"
                                         viewBox="0 0 24 24"
                                         fill="#ffc107" stroke="#ffc107" stroke-width="1">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="12" height="12"
                                         viewBox="0 0 24 24"
                                         fill="none" stroke="#d1d5db" stroke-width="1.5">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <small class="text-muted d-block">Rata-rata Rating</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- DAFTAR PROYEK SELESAI --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0">
                <i class="fas fa-hard-hat text-primary me-2"></i>Proyek Selesai — Perlu Evaluasi
            </h5>
        </div>
        <div class="card-body">
            @forelse($proyeks as $p)
                @php $sudah = $p->evaluasiRekanan->isNotEmpty(); @endphp
                <div class="border rounded-3 p-3 mb-3 {{ $sudah ? 'bg-light' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $p->nama_pembangunan }}</h6>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $p->lokasi }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar me-1"></i>{{ $p->tahun_anggaran }}
                            </p>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i>Selesai
                                </span>
                                @if($sudah)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="fas fa-check me-1"></i>Sudah Dievaluasi
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        <i class="fas fa-clock me-1"></i>Belum Dievaluasi
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-end">
                            @if($sudah)
                                <a href="{{ route('evaluasi-rekanan.show', $p->evaluasiRekanan->first()->id_evaluasi_rekanan) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Lihat Evaluasi
                                </a>
                            @else
                                <a href="{{ route('evaluasi-rekanan.create', $p->id_pembangunan) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i>Isi Evaluasi
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h6 class="mb-1">Belum ada proyek selesai yang perlu dievaluasi</h6>
                    <small class="text-muted">
                        Proyek akan muncul di sini setelah statusnya diubah menjadi "Selesai".
                    </small>
                </div>
            @endforelse

            <div class="mt-3">
                {{ $proyeks->links() }}
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- RIWAYAT EVALUASI --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-history text-primary me-2"></i>Riwayat Evaluasi Saya
            </h5>
            <span class="badge bg-light text-dark border">
                {{ $riwayatEvaluasi->count() }} evaluasi
            </span>
        </div>
        <div class="card-body p-0">
            @if($riwayatEvaluasi->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Proyek</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Tepat Waktu</th>
                                <th class="text-center">Kualitas</th>
                                <th class="text-center">Rating</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatEvaluasi as $e)
                                @php $ratingRow = (int) ($e->rating ?? 0); @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $e->proyek->nama_pembangunan ?? '-' }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $e->proyek->lokasi ?? '-' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div>{{ $e->tanggal_evaluasi->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $e->tanggal_evaluasi->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">{!! $e->tepat_waktu_badge !!}</td>
                                    <td class="text-center">{!! $e->kualitas_badge !!}</td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $ratingRow)
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         width="16" height="16"
                                                         viewBox="0 0 24 24"
                                                         fill="#ffc107"
                                                         stroke="#ffc107"
                                                         stroke-width="1"
                                                         stroke-linecap="round"
                                                         stroke-linejoin="round">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         width="16" height="16"
                                                         viewBox="0 0 24 24"
                                                         fill="none"
                                                         stroke="#d1d5db"
                                                         stroke-width="1.5"
                                                         stroke-linecap="round"
                                                         stroke-linejoin="round">
                                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="small fw-bold text-muted mt-1">{{ $ratingRow }}/5</div>
                                    </td>
                                    <td class="text-center">{!! $e->status_badge !!}</td>
                                    <td class="text-center">
                                        <a href="{{ route('evaluasi-rekanan.show', $e->id_evaluasi_rekanan) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($e->status_verifikasi !== 'approved')
                                            <a href="{{ route('evaluasi-rekanan.edit', $e->id_evaluasi_rekanan) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               title="Edit Evaluasi">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard fa-3x text-muted mb-3"></i>
                    <h6 class="mb-1">Belum ada riwayat evaluasi</h6>
                    <small class="text-muted">
                        Riwayat akan muncul di sini setelah Anda mengisi evaluasi.
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* ============================================= */
    /* STAT CARDS */
    /* ============================================= */
    .stat-card {
        transition: transform .25s ease, box-shadow .25s ease;
        border-radius: 14px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, .06) !important;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* ============================================= */
    /* SOFT BACKGROUNDS */
    /* ============================================= */
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, .10) !important;
    }

    .bg-success-subtle {
        background-color: rgba(25, 135, 84, .10) !important;
    }

    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, .14) !important;
    }

    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, .10) !important;
    }

    .bg-info-subtle {
        background-color: rgba(13, 202, 240, .10) !important;
    }
</style>
@endpush