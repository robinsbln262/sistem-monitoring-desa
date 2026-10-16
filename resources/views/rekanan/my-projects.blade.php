@extends('layouts.app')

@section('content')

<div class="container-fluid my-projects-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="row mb-4">

        <div class="col-12">

            <div class="my-project-header">

                <div class="d-flex align-items-center gap-3">

                    <div class="header-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>

                    <div>

                        <h4 class="mb-1 fw-bold">
                            Proyek Saya
                        </h4>

                        <p class="text-muted mb-0">
                            Daftar proyek yang telah Anda menangkan
                            dan sedang/akan Anda kerjakan.
                        </p>

                    </div>

                </div>


                <div class="project-count-badge">

                    <i class="fas fa-folder-check me-1"></i>

                    {{ $proyeks->total() }} Proyek

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show rounded-3">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        ERROR
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show rounded-3">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        PROJECT LIST
    ========================================================== --}}
    <div class="row">

        @forelse($proyeks as $proyek)

            @php

                /*
                |--------------------------------------------------------------------------
                | MONITORING TERAKHIR
                |--------------------------------------------------------------------------
                */

                $lastMonitoring =
                    $proyek->monitorings
                        ->sortByDesc('tanggal_monitoring')
                        ->first();


                $progress =
                    $lastMonitoring
                        ? (int) $lastMonitoring->progres
                        : 0;


                /*
                |--------------------------------------------------------------------------
                | DIMENSI PROYEK
                |--------------------------------------------------------------------------
                */

                $hasDimension =
                    $proyek->panjang !== null &&
                    $proyek->lebar !== null &&
                    $proyek->ketebalan_tinggi !== null;


                /*
                |--------------------------------------------------------------------------
                | VOLUME
                |--------------------------------------------------------------------------
                */

                $volume =
                    $proyek->volume_proyek;


                /*
                |--------------------------------------------------------------------------
                | FALLBACK HITUNG VOLUME
                |--------------------------------------------------------------------------
                */

                if (
                    $volume === null &&
                    $hasDimension
                ) {

                    $volume =
                        (float) $proyek->panjang *
                        (float) $proyek->lebar *
                        (float) $proyek->ketebalan_tinggi;

                }


                /*
                |--------------------------------------------------------------------------
                | STATUS PROYEK
                |--------------------------------------------------------------------------
                */

                $status =
                    strtolower(
                        $proyek->status ?? ''
                    );

            @endphp


            {{-- =====================================================
                PROJECT CARD
            ====================================================== --}}
            <div class="col-xl-4 col-lg-4 col-md-6 mb-4">

                <div class="card my-project-card h-100">


                    {{-- =================================================
                        HEADER CARD
                    ================================================== --}}
                    <div class="card-header my-project-card-header">

                        <div class="d-flex justify-content-between align-items-start gap-2">

                            <div class="project-title-wrapper">

                                <div class="project-icon">

                                    <i class="fas fa-building"></i>

                                </div>


                                <div>

                                    <h6 class="project-title mb-1">

                                        {{ Str::limit(
                                            $proyek->nama_pembangunan,
                                            38
                                        ) }}

                                    </h6>


                                    @if($proyek->bidang_pekerjaan)

                                        <small class="text-muted">

                                            <i class="fas fa-tools me-1"></i>

                                            {{ $proyek->bidang_pekerjaan }}

                                        </small>

                                    @endif

                                </div>

                            </div>


                            {{-- STATUS --}}
                            @if($status === 'selesai')

                                <span class="badge bg-success status-badge">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Selesai

                                </span>


                            @elseif($status === 'berjalan')

                                <span class="badge bg-warning text-dark status-badge">

                                    <i class="fas fa-spinner me-1"></i>

                                    Berjalan

                                </span>


                            @else

                                <span class="badge bg-secondary status-badge">

                                    <i class="fas fa-clock me-1"></i>

                                    Direncanakan

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- =================================================
                        BODY
                    ================================================== --}}
                    <div class="card-body">


                        {{-- =================================================
                            STATUS PELAKSANA
                        ================================================== --}}
                        <div class="winner-box">

                            <div class="winner-icon">

                                <i class="fas fa-user-check"></i>

                            </div>


                            <div>

                                <small>
                                    Status Rekanan
                                </small>

                                <strong>
                                    Anda adalah pelaksana proyek
                                </strong>

                            </div>

                        </div>


                        {{-- =================================================
                            LOKASI
                        ================================================== --}}
                        <div class="project-info-row">

                            <div class="info-icon location">

                                <i class="fas fa-map-marker-alt"></i>

                            </div>


                            <div>

                                <small class="info-label">
                                    Lokasi Proyek
                                </small>

                                <div class="info-value">

                                    {{ $proyek->lokasi ?? '-' }}

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            PEMBUAT
                        ================================================== --}}
                        <div class="project-info-row">

                            <div class="info-icon creator">

                                <i class="fas fa-user"></i>

                            </div>


                            <div>

                                <small class="info-label">
                                    Dibuat Oleh
                                </small>

                                <div class="info-value">

                                    {{ $proyek->user->nama_user ?? 'Unknown' }}

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            TAHUN
                        ================================================== --}}
                        <div class="project-info-row">

                            <div class="info-icon year">

                                <i class="fas fa-calendar-alt"></i>

                            </div>


                            <div>

                                <small class="info-label">
                                    Tahun Anggaran
                                </small>

                                <div class="info-value">

                                    {{ $proyek->tahun_anggaran ?? '-' }}

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            DIMENSI PROYEK
                        ================================================== --}}
                        <div class="dimension-box">

                            <div class="dimension-header">

                                <div class="d-flex align-items-center">

                                    <div class="dimension-icon">

                                        <i class="fas fa-ruler-combined"></i>

                                    </div>


                                    <div>

                                        <div class="dimension-title">

                                            Ukuran Proyek

                                        </div>

                                        <small class="text-muted">

                                            Detail pekerjaan

                                        </small>

                                    </div>

                                </div>

                            </div>


                            @if($hasDimension)

                                <div class="row g-2 mt-2">


                                    {{-- PANJANG --}}
                                    <div class="col-4">

                                        <div class="dimension-item">

                                            <small>
                                                Panjang
                                            </small>

                                            <strong>

                                                {{ rtrim(rtrim(
                                                    number_format(
                                                        (float) $proyek->panjang,
                                                        2,
                                                        ',',
                                                        '.'
                                                    ),
                                                    '0'
                                                ), ',') }}

                                                <span>m</span>

                                            </strong>

                                        </div>

                                    </div>


                                    {{-- LEBAR --}}
                                    <div class="col-4">

                                        <div class="dimension-item">

                                            <small>
                                                Lebar
                                            </small>

                                            <strong>

                                                {{ rtrim(rtrim(
                                                    number_format(
                                                        (float) $proyek->lebar,
                                                        2,
                                                        ',',
                                                        '.'
                                                    ),
                                                    '0'
                                                ), ',') }}

                                                <span>m</span>

                                            </strong>

                                        </div>

                                    </div>


                                    {{-- KETEBALAN / TINGGI --}}
                                    <div class="col-4">

                                        <div class="dimension-item">

                                            <small>
                                                Ketebalan/Tinggi
                                            </small>

                                            <strong>

                                                {{ rtrim(rtrim(
                                                    number_format(
                                                        (float) $proyek->ketebalan_tinggi,
                                                        2,
                                                        ',',
                                                        '.'
                                                    ),
                                                    '0'
                                                ), ',') }}

                                                <span>m</span>

                                            </strong>

                                        </div>

                                    </div>

                                </div>


                                {{-- VOLUME --}}
                                <div class="volume-box">

                                    <div>

                                        <small>
                                            Volume Pekerjaan
                                        </small>

                                        <strong>

                                            {{ rtrim(rtrim(
                                                number_format(
                                                    (float) $volume,
                                                    2,
                                                    ',',
                                                    '.'
                                                ),
                                                '0'
                                            ), ',') }}

                                            m³

                                        </strong>

                                    </div>


                                    <div class="volume-formula">

                                        <i class="fas fa-calculator me-1"></i>

                                        P × L × T

                                    </div>

                                </div>


                            @elseif($proyek->volume_proyek !== null)

                                {{-- DATA LAMA --}}
                                <div class="old-volume-box">

                                    <div class="old-volume-icon">

                                        <i class="fas fa-ruler-combined"></i>

                                    </div>


                                    <div>

                                        <small>
                                            Volume Proyek
                                        </small>

                                        <strong>

                                            {{ rtrim(rtrim(
                                                number_format(
                                                    (float) $proyek->volume_proyek,
                                                    2,
                                                    ',',
                                                    '.'
                                                ),
                                                '0'
                                            ), ',') }}

                                            {{ $proyek->satuan_volume ?? '' }}

                                        </strong>

                                    </div>

                                </div>

                            @else

                                <div class="empty-dimension">

                                    <i class="fas fa-ruler-combined me-1"></i>

                                    Detail ukuran belum tersedia.

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                            ANGGARAN
                        ================================================== --}}
                        <div class="project-info-row">

                            <div class="info-icon budget">

                                <i class="fas fa-money-bill-wave"></i>

                            </div>


                            <div>

                                <small class="info-label">
                                    Anggaran Proyek
                                </small>

                                <div class="info-value text-success fw-bold">

                                    Rp
                                    {{ number_format(
                                        $proyek->anggaran ?? 0,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            HARGA PENAWARAN REKANAN
                        ================================================== --}}
                        @php

                            $penawaranSaya =
                                $proyek->rekananProyek
                                    ->where(
                                        'id_rekanan',
                                        Auth::id()
                                    )
                                    ->first();

                        @endphp


                        @if($penawaranSaya)

                            <div class="my-offer-box">

                                <div class="offer-icon">

                                    <i class="fas fa-gavel"></i>

                                </div>


                                <div class="offer-content">

                                    <small>
                                        Penawaran Anda
                                    </small>

                                    <strong>

                                        Rp
                                        {{ number_format(
                                            $penawaranSaya->harga_penawaran_rekanan ?? 0,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </strong>


                                    @if($penawaranSaya->status_penawaran)

                                        <span class="offer-status">

                                            @if(
                                                $penawaranSaya->status_penawaran === 'diterima'
                                            )

                                                <span class="badge bg-success">

                                                    <i class="fas fa-check me-1"></i>

                                                    Diterima

                                                </span>

                                            @elseif(
                                                $penawaranSaya->status_penawaran === 'dipertimbangkan'
                                            )

                                                <span class="badge bg-info text-dark">

                                                    <i class="fas fa-hourglass-half me-1"></i>

                                                    Dipertimbangkan

                                                </span>

                                            @elseif(
                                                $penawaranSaya->status_penawaran === 'ditolak'
                                            )

                                                <span class="badge bg-danger">

                                                    <i class="fas fa-times me-1"></i>

                                                    Ditolak

                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">

                                                    <i class="fas fa-clock me-1"></i>

                                                    Menunggu

                                                </span>

                                            @endif

                                        </span>

                                    @endif

                                </div>

                            </div>

                        @endif


                        {{-- =================================================
                            JADWAL
                        ================================================== --}}
                        <div class="schedule-box">

                            <div class="schedule-item">

                                <small>
                                    Mulai
                                </small>

                                <strong>

                                    {{
                                        $proyek->tanggal_mulai
                                            ? $proyek->tanggal_mulai->format('d M Y')
                                            : '-'
                                    }}

                                </strong>

                            </div>


                            <div class="schedule-divider">

                                <i class="fas fa-arrow-right"></i>

                            </div>


                            <div class="schedule-item text-end">

                                <small>
                                    Selesai
                                </small>

                                <strong>

                                    {{
                                        $proyek->tanggal_selesai
                                            ? $proyek->tanggal_selesai->format('d M Y')
                                            : '-'
                                    }}

                                </strong>

                            </div>

                        </div>


                        {{-- =================================================
                            PROGRESS
                        ================================================== --}}
                        <div class="progress-section">

                            <div class="d-flex justify-content-between mb-2">

                                <span class="progress-label">

                                    <i class="fas fa-chart-line me-1"></i>

                                    Progres Pekerjaan

                                </span>


                                <strong>

                                    {{ $progress }}%

                                </strong>

                            </div>


                            <div
                                class="progress"
                                style="height:9px;"
                            >

                                <div
                                    class="progress-bar {{
                                        $progress >= 100
                                            ? 'bg-success'
                                            : 'bg-primary'
                                    }}"
                                    role="progressbar"
                                    style="width:{{ min(100, max(0, $progress)) }}%;"
                                    aria-valuenow="{{ $progress }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                ></div>

                            </div>


                            <small class="text-muted d-block mt-1">

                                @if($progress >= 100)

                                    Proyek telah selesai.

                                @elseif($progress > 0)

                                    Proyek sedang dalam proses pengerjaan.

                                @else

                                    Pekerjaan belum memiliki progres monitoring.

                                @endif

                            </small>

                        </div>


                        {{-- =================================================
                            CATATAN
                        ================================================== --}}
                        @if($proyek->catatan)

                            <div class="project-note">

                                <div class="note-title">

                                    <i class="fas fa-sticky-note me-1"></i>

                                    Catatan Proyek

                                </div>


                                <div class="note-content">

                                    {{ $proyek->catatan }}

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                        FOOTER
                    ================================================== --}}
                    <div class="card-footer my-project-footer">

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="footer-status">

                                @if($status === 'selesai')

                                    <span class="text-success">

                                        <i class="fas fa-check-circle me-1"></i>

                                        Pekerjaan Selesai

                                    </span>

                                @elseif($status === 'berjalan')

                                    <span class="text-warning">

                                        <i class="fas fa-hard-hat me-1"></i>

                                        Sedang Dikerjakan

                                    </span>

                                @else

                                    <span class="text-secondary">

                                        <i class="fas fa-clock me-1"></i>

                                        Menunggu Pelaksanaan

                                    </span>

                                @endif

                            </div>


                            <div>

                                <span class="badge bg-success rounded-pill">

                                    <i class="fas fa-user-check me-1"></i>

                                    Pelaksana

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        @empty


            {{-- =====================================================
                EMPTY STATE
            ====================================================== --}}
            <div class="col-12">

                <div class="empty-state">

                    <div class="empty-state-icon">

                        <i class="fas fa-briefcase"></i>

                    </div>


                    <h5 class="text-muted mb-2">

                        Belum Ada Proyek

                    </h5>


                    <p class="text-muted mb-0">

                        Anda belum memiliki proyek yang disetujui
                        sebagai pelaksana.

                    </p>


                    <a
                        href="{{ route('rekanan.available') }}"
                        class="btn btn-primary rounded-3 mt-3"
                    >

                        <i class="fas fa-search me-2"></i>

                        Cari Proyek Tersedia

                    </a>

                </div>

            </div>

        @endforelse

    </div>


    {{-- =========================================================
        PAGINATION
    ========================================================== --}}
    @if($proyeks->hasPages())

        <div class="d-flex justify-content-center mt-3">

            {{ $proyeks->links() }}

        </div>

    @endif

</div>


{{-- =============================================================
    STYLE
============================================================= --}}
<style>

/* =========================================================
   PAGE HEADER
========================================================= */

.my-projects-page {
    padding-bottom: 30px;
}


.my-project-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

}


.header-icon {

    width: 46px;

    height: 46px;

    min-width: 46px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 13px;

    background: #eaf2ff;

    color: #1e3c72;

    font-size: 20px;

}


.project-count-badge {

    padding: 9px 16px;

    border-radius: 50px;

    background: #1e3c72;

    color: #fff;

    font-size: 12px;

    font-weight: 600;

}


/* =========================================================
   CARD
========================================================= */

.my-project-card {

    border: 1px solid #e6eaf0;

    border-radius: 17px;

    overflow: hidden;

    background: #fff;

    transition:
        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease;

}


.my-project-card:hover {

    transform: translateY(-5px);

    box-shadow:
        0 16px 35px rgba(30, 60, 114, .12);

    border-color: #d5e2f5;

}


.my-project-card-header {

    padding: 16px;

    background: #fff;

    border-bottom: 1px solid #edf0f4;

}


.project-title-wrapper {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    min-width: 0;

}


.project-icon {

    width: 37px;

    height: 37px;

    min-width: 37px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 10px;

    background: #edf4ff;

    color: #1e3c72;

}


.project-title {

    color: #1e3c72;

    font-size: 13px;

    line-height: 1.4;

}


.status-badge {

    white-space: nowrap;

    font-size: 10px;

    padding: 6px 8px;

}


/* =========================================================
   WINNER BOX
========================================================= */

.winner-box {

    display: flex;

    align-items: center;

    gap: 10px;

    margin-bottom: 14px;

    padding: 11px 12px;

    border-radius: 11px;

    background: #effaf3;

    border: 1px solid #d6f0df;

}


.winner-icon {

    width: 34px;

    height: 34px;

    min-width: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background: #dff5e7;

    color: #198754;

}


.winner-box small {

    display: block;

    color: #6b7280;

    font-size: 9px;

}


.winner-box strong {

    display: block;

    color: #198754;

    font-size: 11px;

}


/* =========================================================
   INFO
========================================================= */

.project-info-row {

    display: flex;

    align-items: center;

    gap: 10px;

    margin-bottom: 12px;

}


.info-icon {

    width: 32px;

    height: 32px;

    min-width: 32px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background: #f5f7fa;

    font-size: 13px;

}


.info-icon.location {
    color: #dc3545;
}

.info-icon.creator {
    color: #0d6efd;
}

.info-icon.year {
    color: #8b5cf6;
}

.info-icon.budget {
    color: #198754;
}


.info-label {

    display: block;

    color: #8a94a6;

    font-size: 9px;

    text-transform: uppercase;

    letter-spacing: .3px;

}


.info-value {

    color: #3f4652;

    font-size: 12px;

    font-weight: 500;

}


/* =========================================================
   DIMENSION
========================================================= */

.dimension-box {

    margin: 15px 0;

    padding: 12px;

    border-radius: 13px;

    background: #f8fbff;

    border: 1px solid #dce8f7;

}


.dimension-header {

    display: flex;

    align-items: center;

}


.dimension-icon {

    width: 34px;

    height: 34px;

    min-width: 34px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-right: 9px;

    border-radius: 9px;

    background: #e6f0ff;

    color: #1e3c72;

}


.dimension-title {

    color: #1e3c72;

    font-size: 12px;

    font-weight: 700;

}


.dimension-item {

    padding: 8px 5px;

    border-radius: 9px;

    background: #fff;

    border: 1px solid #e7edf5;

    text-align: center;

}


.dimension-item small {

    display: block;

    margin-bottom: 3px;

    color: #8a94a6;

    font-size: 8px;

}


.dimension-item strong {

    display: block;

    color: #1e3c72;

    font-size: 12px;

}


.dimension-item span {

    color: #64748b;

    font-size: 9px;

}


.volume-box {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    margin-top: 9px;

    padding: 10px 12px;

    border-radius: 9px;

    background: #fff;

    border: 1px solid #dce8f7;

}


.volume-box small {

    display: block;

    color: #64748b;

    font-size: 9px;

}


.volume-box strong {

    color: #198754;

    font-size: 15px;

}


.volume-formula {

    padding: 5px 8px;

    border-radius: 50px;

    background: #edf4ff;

    color: #1e3c72;

    font-size: 9px;

    font-weight: 700;

}


.old-volume-box {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 10px;

    background: #fff;

    border-radius: 9px;

    border: 1px solid #e7edf5;

}


.old-volume-icon {

    width: 32px;

    height: 32px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 8px;

    background: #edf4ff;

    color: #1e3c72;

}


.old-volume-box small {

    display: block;

    color: #8a94a6;

    font-size: 9px;

}


.old-volume-box strong {

    color: #1e3c72;

    font-size: 13px;

}


.empty-dimension {

    padding: 8px;

    color: #8a94a6;

    font-size: 10px;

}


/* =========================================================
   OFFER
========================================================= */

.my-offer-box {

    display: flex;

    align-items: center;

    gap: 10px;

    margin-top: 12px;

    padding: 11px;

    border-radius: 11px;

    background: #f4f8ff;

    border: 1px solid #dce8f7;

}


.offer-icon {

    width: 35px;

    height: 35px;

    min-width: 35px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    background: #e4efff;

    color: #0d6efd;

}


.offer-content small {

    display: block;

    color: #8a94a6;

    font-size: 9px;

}


.offer-content strong {

    display: block;

    color: #0d6efd;

    font-size: 13px;

}


.offer-status {

    display: block;

    margin-top: 3px;

}


/* =========================================================
   SCHEDULE
========================================================= */

.schedule-box {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    margin-top: 12px;

    padding: 11px 12px;

    border-radius: 11px;

    background: #f8f9fb;

    border: 1px solid #e8ebf0;

}


.schedule-item small {

    display: block;

    color: #8a94a6;

    font-size: 9px;

}


.schedule-item strong {

    display: block;

    color: #394150;

    font-size: 11px;

}


.schedule-divider {

    color: #adb5bd;

    font-size: 11px;

}


/* =========================================================
   PROGRESS
========================================================= */

.progress-section {

    margin-top: 15px;

}


.progress-label {

    color: #64748b;

    font-size: 10px;

    font-weight: 600;

}


.progress-section strong {

    color: #1e3c72;

    font-size: 11px;

}


/* =========================================================
   NOTE
========================================================= */

.project-note {

    margin-top: 12px;

    padding: 10px 12px;

    border-left: 3px solid #1e3c72;

    border-radius: 8px;

    background: #f8f9fa;

}


.note-title {

    margin-bottom: 4px;

    color: #1e3c72;

    font-size: 10px;

    font-weight: 700;

}


.note-content {

    color: #64748b;

    font-size: 10px;

    line-height: 1.5;

}


/* =========================================================
   FOOTER
========================================================= */

.my-project-footer {

    padding: 12px 16px;

    background: #fff;

    border-top: 1px solid #edf0f4;

}


.footer-status {

    font-size: 10px;

    font-weight: 600;

}


/* =========================================================
   EMPTY
========================================================= */

.empty-state {

    padding: 75px 20px;

    text-align: center;

}


.empty-state-icon {

    width: 85px;

    height: 85px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin: 0 auto 20px;

    border-radius: 50%;

    background: #f3f5f8;

    color: #adb5bd;

    font-size: 35px;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .my-project-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .project-count-badge {

        align-self: flex-start;

    }


    .my-project-card {

        border-radius: 14px;

    }


    .dimension-item small {

        font-size: 7px;

    }


    .dimension-item strong {

        font-size: 10px;

    }


    .volume-box {

        align-items: flex-start;

        flex-direction: column;

    }


    .volume-formula {

        align-self: flex-start;

    }


    .schedule-box {

        align-items: flex-start;

    }

}

</style>

@endsection