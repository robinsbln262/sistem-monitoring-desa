@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ============================================= --}}
    {{-- HEADER --}}
    {{-- ============================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-chart-bar text-primary me-2"></i>Kredibilitas Rekanan
            </h4>
            <p class="text-muted mb-0">
                Peringkat rekanan berdasarkan riwayat evaluasi yang telah disetujui
            </p>
        </div>
        <a href="{{ route('admin.evaluasi-rekanan') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- STATISTIK RINGKASAN --}}
    {{-- ============================================= --}}
    @php
        $totalRekanan   = $rekanans->count();
        $rekananAktif   = $rekanans->where('total_evaluasi', '>', 0)->count();
        $rekananTerbaik = $rekanans->first();
        $rataSkorSemua  = $totalRekanan > 0 ? $rekanans->avg('skor') : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-primary-subtle">
                            <i class="fas fa-users text-primary"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 fw-bold">{{ $totalRekanan }}</h3>
                    <small class="text-muted">Total Rekanan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-success-subtle">
                            <i class="fas fa-user-check text-success"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 fw-bold text-success">{{ $rekananAktif }}</h3>
                    <small class="text-muted">Pernah Dievaluasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-info-subtle">
                            <i class="fas fa-chart-line text-info"></i>
                        </div>
                    </div>
                    <h3 class="mb-0 fw-bold text-info">
                        {{ format_angka($rataSkorSemua) }}
                    </h3>
                    <small class="text-muted">Rata-rata Skor</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-warning-subtle">
                            <i class="fas fa-crown text-warning"></i>
                        </div>
                    </div>
                    <h6 class="mb-0 fw-bold text-truncate" title="{{ $rekananTerbaik->nama_user ?? '-' }}">
                        {{ $rekananTerbaik->nama_user ?? '-' }}
                    </h6>
                    <small class="text-muted">Skor Tertinggi</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- TABEL SEMUA REKANAN --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-list-ol text-primary me-2"></i>Peringkat Lengkap
            </h6>
            <small class="text-muted">
                Total: <strong>{{ $rekanans->count() }}</strong> rekanan
            </small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="6%" class="text-center">Rank</th>
                            <th>Rekanan</th>
                            <th>Bidang Keahlian</th>
                            <th class="text-center">Total Proyek</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Tepat Waktu</th>
                            <th width="18%">Skor Kredibilitas</th>
                            <th class="text-center">Predikat</th>
                            <th width="8%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekanans as $i => $r)
                            @php
                                $rank    = $i + 1;
                                $skor    = (float) ($r->skor ?? 0);
                                $rating  = (float) ($r->rata_rating ?? 0);
                                $tepat   = (float) ($r->tepat_waktu ?? 0);
                                $totalEv = (int)   ($r->total_evaluasi ?? 0);
                                $ratingInt = (int) round($rating);

                                // Warna progress bar
                                if ($skor >= 85) {
                                    $barClass = 'bg-success';
                                } elseif ($skor >= 70) {
                                    $barClass = 'bg-primary';
                                } elseif ($skor >= 55) {
                                    $barClass = 'bg-warning';
                                } else {
                                    $barClass = 'bg-danger';
                                }

                                // Predikat
                                if ($skor >= 85) {
                                    $predikatClass = 'bg-success';
                                    $predikatIcon  = 'fa-trophy';
                                    $predikatText  = 'Sangat Baik';
                                } elseif ($skor >= 70) {
                                    $predikatClass = 'bg-primary';
                                    $predikatIcon  = 'fa-thumbs-up';
                                    $predikatText  = 'Baik';
                                } elseif ($skor >= 55) {
                                    $predikatClass = 'bg-warning text-dark';
                                    $predikatIcon  = 'fa-chart-bar';
                                    $predikatText  = 'Cukup';
                                } else {
                                    $predikatClass = 'bg-danger';
                                    $predikatIcon  = 'fa-exclamation-triangle';
                                    $predikatText  = 'Perlu Perbaikan';
                                }
                            @endphp
                            <tr class="{{ $rank <= 3 ? 'table-light' : '' }}">

                                {{-- RANK --}}
                                <td class="text-center">
                                    @if($rank === 1)
                                        <span class="badge bg-warning text-dark rounded-circle p-2"
                                              style="width:32px;height:32px;"
                                              title="Peringkat 1">
                                            <i class="fas fa-crown"></i>
                                        </span>
                                    @elseif($rank === 2)
                                        <span class="badge bg-secondary rounded-circle p-2"
                                              style="width:32px;height:32px;"
                                              title="Peringkat 2">
                                            2
                                        </span>
                                    @elseif($rank === 3)
                                        <span class="badge rounded-circle p-2"
                                              style="width:32px;height:32px;background:#cd7f32;color:white;"
                                              title="Peringkat 3">
                                            3
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted rounded-circle p-2"
                                              style="width:32px;height:32px;border:1px solid #e5e7eb;">
                                            {{ $rank }}
                                        </span>
                                    @endif
                                </td>

                                {{-- REKANAN --}}
                                <td>
                                    <a href="{{ route('admin.evaluasi-rekanan.kinerja', $r->id_user) }}"
                                       class="d-flex align-items-center text-decoration-none">
                                        <div class="user-avatar me-2"
                                             style="width:38px;height:38px;font-size:13px;">
                                            {{ strtoupper(substr($r->nama_user, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $r->nama_user }}</div>
                                            @if($r->no_telp)
                                                <small class="text-muted">
                                                    <i class="fas fa-phone me-1"></i>{{ $r->no_telp }}
                                                </small>
                                            @endif
                                        </div>
                                    </a>
                                </td>

                                {{-- BIDANG --}}
                                <td>
                                    <small>{{ $r->bidang_keahlian ?? '-' }}</small>
                                </td>

                                {{-- TOTAL PROYEK --}}
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">
                                        {{ $totalEv }} proyek
                                    </span>
                                </td>

                                {{-- RATING — SVG --}}
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        @for($s = 1; $s <= 5; $s++)
                                            @if($s <= $ratingInt)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="14" height="14"
                                                     viewBox="0 0 24 24"
                                                     fill="#ffc107"
                                                     stroke="#ffc107"
                                                     stroke-width="1">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="14" height="14"
                                                     viewBox="0 0 24 24"
                                                     fill="none"
                                                     stroke="#d1d5db"
                                                     stroke-width="1.5">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="small fw-bold text-muted mt-1">
                                        {{ format_angka($rating) }}/5
                                    </div>
                                </td>

                                {{-- TEPAT WAKTU --}}
                                <td class="text-center">
                                    @if($tepat >= 80)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            {{ format_angka($tepat) }}%
                                        </span>
                                    @elseif($tepat >= 50)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            {{ format_angka($tepat) }}%
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            {{ format_angka($tepat) }}%
                                        </span>
                                    @endif
                                </td>

                                {{-- SKOR — Progress Bar --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2"
                                             style="height:8px; min-width:60px;">
                                            <div class="progress-bar {{ $barClass }}"
                                                 style="width: {{ $skor }}%"></div>
                                        </div>
                                        <strong class="small">{{ format_angka($skor) }}</strong>
                                    </div>
                                </td>

                                {{-- PREDIKAT --}}
                                <td class="text-center">
                                    <span class="badge {{ $predikatClass }} rounded-pill px-3 py-2">
                                        <i class="fas {{ $predikatIcon }} me-1"></i>{{ $predikatText }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.evaluasi-rekanan.kinerja', $r->id_user) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Lihat Detail Kinerja">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x d-block mb-3"></i>
                                    <h6 class="mb-1">Belum ada data evaluasi rekanan</h6>
                                    <small>
                                        Peringkat akan muncul di sini setelah rekanan menyelesaikan
                                        proyek & mengisi evaluasi.
                                    </small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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