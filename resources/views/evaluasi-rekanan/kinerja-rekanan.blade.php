@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @php
        $skor = (float) ($statistik['skor_kredibilitas'] ?? 0);

        // Predikat
        if ($skor >= 85) {
            $predikatClass = 'bg-success';
            $predikatIcon  = 'fa-trophy';
            $predikatText  = 'Sangat Baik';
            $skorColor     = 'text-success';
        } elseif ($skor >= 70) {
            $predikatClass = 'bg-primary';
            $predikatIcon  = 'fa-thumbs-up';
            $predikatText  = 'Baik';
            $skorColor     = 'text-primary';
        } elseif ($skor >= 55) {
            $predikatClass = 'bg-warning text-dark';
            $predikatIcon  = 'fa-chart-bar';
            $predikatText  = 'Cukup';
            $skorColor     = 'text-warning';
        } else {
            $predikatClass = 'bg-danger';
            $predikatIcon  = 'fa-exclamation-triangle';
            $predikatText  = 'Perlu Perbaikan';
            $skorColor     = 'text-danger';
        }

        // Breakdown skor (untuk progress bar)
        $total     = $statistik['total_proyek'] ?? 0;
        $tepat     = $statistik['total_tepat_waktu'] ?? 0;
        $terlambat = $statistik['total_terlambat'] ?? 0;

        // Rating komponen (40%)
        $skorRatingKomponen = $total > 0
            ? ($statistik['rata_rating'] / 5) * 40
            : 0;

        // Ketepatan waktu (30%)
        $skorWaktuKomponen = $total > 0
            ? ($tepat / $total) * 30
            : 0;

        // Kualitas (30%) - hitung dari koleksi evaluasi
        $bobotKualitas = [
            'sangat_baik' => 1.0,
            'baik'        => 0.75,
            'cukup'       => 0.5,
            'kurang'      => 0.25,
        ];
        $totalKualitas = $evaluasis->sum(function ($e) use ($bobotKualitas) {
            return $bobotKualitas[$e->kualitas_pekerjaan] ?? 0.5;
        });
        $skorKualitasKomponen = $total > 0 ? ($totalKualitas / $total) * 30 : 0;

        // Ringkasan kualitas
        $kualitasBreakdown = [
            'sangat_baik' => $evaluasis->where('kualitas_pekerjaan', 'sangat_baik')->count(),
            'baik'        => $evaluasis->where('kualitas_pekerjaan', 'baik')->count(),
            'cukup'       => $evaluasis->where('kualitas_pekerjaan', 'cukup')->count(),
            'kurang'      => $evaluasis->where('kualitas_pekerjaan', 'kurang')->count(),
        ];
    @endphp

    {{-- ============================================= --}}
    {{-- HEADER --}}
    {{-- ============================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-chart-line text-primary me-2"></i>Kinerja Rekanan
            </h4>
            <p class="text-muted mb-0">{{ $rekanan->nama_user }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- PROFILE CARD PREMIUM --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm mb-4 profile-hero">
        <div class="card-body p-4">
            <div class="row align-items-center g-4">

                {{-- Kiri: Info Rekanan --}}
                <div class="col-lg-7">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar me-4 profile-avatar"
                             style="width:80px;height:80px;font-size:28px;">
                            {{ strtoupper(substr($rekanan->nama_user, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">{{ $rekanan->nama_user }}</h4>
                            <p class="text-muted mb-1">
                                <i class="fas fa-tools me-1"></i>
                                {{ $rekanan->bidang_keahlian ?? 'Belum ada bidang keahlian' }}
                            </p>
                            <div class="d-flex flex-wrap gap-3 small text-muted">
                                @if($rekanan->no_telp)
                                    <span>
                                        <i class="fas fa-phone me-1"></i>{{ $rekanan->no_telp }}
                                    </span>
                                @endif
                                @if($rekanan->email)
                                    <span>
                                        <i class="fas fa-envelope me-1"></i>{{ $rekanan->email }}
                                    </span>
                                @endif
                                @if($rekanan->alamat)
                                    <span>
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $rekanan->alamat }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Skor Besar --}}
                <div class="col-lg-5">
                    <div class="text-center text-lg-end">
                        <small class="text-muted d-block mb-1">SKOR KREDIBILITAS</small>
                        <div class="d-flex align-items-center justify-content-center justify-content-lg-end gap-3">
                            <div class="display-3 fw-bold {{ $skorColor }} mb-0" style="line-height:1;">
                                {{ format_angka($skor) }}
                            </div>
                            <div class="text-start">
                                <span class="badge {{ $predikatClass }} rounded-pill px-3 py-2 d-block">
                                    <i class="fas {{ $predikatIcon }} me-1"></i>{{ $predikatText }}
                                </span>
                                <small class="text-muted d-block mt-1">
                                    dari 100
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- STATISTIK UTAMA --}}
    {{-- ============================================= --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-primary-subtle">
                            <i class="fas fa-clipboard-check text-primary"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $total }}</h3>
                    <small class="text-muted d-block">Total Proyek Dievaluasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-success-subtle">
                            <i class="fas fa-clock text-success"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-success">{{ $tepat }}</h3>
                    <small class="text-muted d-block">Tepat Waktu</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-danger-subtle">
                            <i class="fas fa-hourglass-end text-danger"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-danger">{{ $terlambat }}</h3>
                    <small class="text-muted d-block">Terlambat</small>
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
                            {{ format_angka($statistik['rata_rating']) }}
                        </h3>
                        <div class="d-inline-flex align-items-center gap-1">
                            @php $ratingInt = (int) round($statistik['rata_rating']); @endphp
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
    {{-- BREAKDOWN SKOR KREDIBILITAS --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-chart-pie text-primary me-2"></i>Rincian Skor Kredibilitas
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-4">

                {{-- Rating (40%) --}}
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold small">
                            <i class="fas fa-star text-warning me-1"></i>Rating
                        </span>
                        <span class="badge bg-warning-subtle text-warning">
                            Bobot 40%
                        </span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h4 class="mb-0 text-warning">
                            {{ format_angka($skorRatingKomponen) }}
                        </h4>
                        <small class="text-muted">/ 40</small>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar bg-warning"
                             style="width: {{ ($skorRatingKomponen / 40) * 100 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Rata-rata rating {{ format_angka($statistik['rata_rating']) }}/5 dari {{ $total }} evaluasi
                    </small>
                </div>

                {{-- Ketepatan Waktu (30%) --}}
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold small">
                            <i class="fas fa-clock text-success me-1"></i>Ketepatan Waktu
                        </span>
                        <span class="badge bg-success-subtle text-success">
                            Bobot 30%
                        </span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h4 class="mb-0 text-success">
                            {{ format_angka($skorWaktuKomponen) }}
                        </h4>
                        <small class="text-muted">/ 30</small>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar bg-success"
                             style="width: {{ ($skorWaktuKomponen / 30) * 100 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        {{ $tepat }} dari {{ $total }} proyek selesai tepat waktu
                    </small>
                </div>

                {{-- Kualitas (30%) --}}
                <div class="col-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-semibold small">
                            <i class="fas fa-award text-info me-1"></i>Kualitas
                        </span>
                        <span class="badge bg-info-subtle text-info">
                            Bobot 30%
                        </span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h4 class="mb-0 text-info">
                            {{ format_angka($skorKualitasKomponen) }}
                        </h4>
                        <small class="text-muted">/ 30</small>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar bg-info"
                             style="width: {{ ($skorKualitasKomponen / 30) * 100 }}%"></div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Berdasarkan klasifikasi kualitas pekerjaan
                    </small>
                </div>
            </div>

            {{-- Ringkasan Kualitas --}}
            <div class="row g-2 mt-4 pt-3 border-top">
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center gap-2 p-2 bg-success-subtle rounded-3">
                        <i class="fas fa-medal text-success"></i>
                        <div>
                            <div class="fw-bold small">{{ $kualitasBreakdown['sangat_baik'] }}</div>
                            <small class="text-muted" style="font-size:11px;">Sangat Baik</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center gap-2 p-2 bg-primary-subtle rounded-3">
                        <i class="fas fa-thumbs-up text-primary"></i>
                        <div>
                            <div class="fw-bold small">{{ $kualitasBreakdown['baik'] }}</div>
                            <small class="text-muted" style="font-size:11px;">Baik</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center gap-2 p-2 bg-warning-subtle rounded-3">
                        <i class="fas fa-chart-bar text-warning"></i>
                        <div>
                            <div class="fw-bold small">{{ $kualitasBreakdown['cukup'] }}</div>
                            <small class="text-muted" style="font-size:11px;">Cukup</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center gap-2 p-2 bg-danger-subtle rounded-3">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        <div>
                            <div class="fw-bold small">{{ $kualitasBreakdown['kurang'] }}</div>
                            <small class="text-muted" style="font-size:11px;">Kurang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- PROGRESS KETEPATAN WAKTU (BESAR) --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="fw-semibold">
                        <i class="fas fa-clock text-success me-1"></i>Persentase Ketepatan Waktu
                    </span>
                    <small class="text-muted d-block">
                        {{ $tepat }} tepat waktu dari {{ $total }} proyek
                    </small>
                </div>
                <div class="text-end">
                    <h3 class="mb-0 fw-bold text-success">
                        {{ format_angka($statistik['persentase_tepat_waktu']) }}%
                    </h3>
                </div>
            </div>
            <div class="progress" style="height:14px;">
                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                     style="width: {{ $statistik['persentase_tepat_waktu'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- RIWAYAT EVALUASI --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-history text-primary me-2"></i>Riwayat Evaluasi
            </h6>
            <span class="badge bg-light text-dark border">
                {{ $evaluasis->count() }} evaluasi
            </span>
        </div>
        <div class="card-body p-0">
            @if($evaluasis->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Proyek</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Tepat Waktu</th>
                                <th class="text-center">Kualitas</th>
                                <th class="text-center">Rating</th>
                                <th width="15%">Penyelesaian</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluasis as $e)
                                @php
                                    $ratingRow = (int) ($e->rating ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $e->proyek->nama_pembangunan ?? '-' }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $e->proyek->lokasi ?? '-' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">
                                            {{ $e->proyek->tahun_anggaran ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div>{{ $e->tanggal_evaluasi->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $e->tanggal_evaluasi->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">{!! $e->tepat_waktu_badge !!}</td>
                                    <td class="text-center">{!! $e->kualitas_badge !!}</td>

                                    {{-- RATING — SVG --}}
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            @for($s = 1; $s <= 5; $s++)
                                                @if($s <= $ratingRow)
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
                                        <div class="small fw-bold text-muted mt-1">{{ $ratingRow }}/5</div>
                                    </td>

                                    {{-- PENYELESAIAN --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2"
                                                 style="height:6px; min-width:50px;">
                                                <div class="progress-bar bg-info"
                                                     style="width: {{ $e->persentase_penyelesaian }}%"></div>
                                            </div>
                                            <small class="fw-bold">{{ $e->persentase_penyelesaian }}%</small>
                                        </div>
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="text-center">
                                        <a href="{{ route('evaluasi-rekanan.show', $e->id_evaluasi_rekanan) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                        Riwayat akan muncul di sini setelah rekanan menyelesaikan proyek
                        & evaluasi disetujui.
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
    /* PROFILE HERO */
    /* ============================================= */
    .profile-hero {
        background:
            radial-gradient(circle at 100% 0%, rgba(102, 126, 234, .06), transparent 45%),
            radial-gradient(circle at 0% 100%, rgba(255, 193, 7, .05), transparent 45%),
            linear-gradient(145deg, #ffffff 0%, #f8f9ff 100%);
        border-radius: 16px;
    }

    .profile-avatar {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        box-shadow: 0 8px 24px rgba(30, 60, 114, .25);
    }

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

    /* ============================================= */
    /* PROGRESS BAR ANIMATED */
    /* ============================================= */
    .progress-bar-animated {
        background-size: 1rem 1rem;
    }

    /* ============================================= */
    /* RESPONSIVE */
    /* ============================================= */
    @media (max-width: 767.98px) {
        .profile-avatar {
            width: 60px !important;
            height: 60px !important;
            font-size: 20px !important;
        }

        .display-3 {
            font-size: 2.5rem;
        }
    }
</style>
@endpush