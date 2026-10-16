@extends('layouts.app')

@section('content')

<div class="container-fluid project-available-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="row mb-4">

        <div class="col-12">

            <div class="available-header">

                <div>

                    <div class="d-flex align-items-center gap-2 mb-1">

                        <div class="header-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>

                        <div>

                            <h4 class="mb-0 fw-bold">
                                Proyek Tersedia
                            </h4>

                            <small class="text-muted">
                                Daftar proyek yang dapat Anda ikuti
                            </small>

                        </div>

                    </div>

                </div>

                <div class="total-project-badge">

                    <i class="fas fa-layer-group me-1"></i>

                    Total:
                    {{ $proyeks->total() }}
                    Proyek

                </div>

            </div>

            <div class="header-description">

                <i class="fas fa-info-circle me-1 text-primary"></i>

                Berikut adalah daftar proyek yang tersedia untuk Anda.
                Perhatikan detail ukuran proyek sebelum mengajukan penawaran
                agar harga yang diberikan lebih akurat.

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS
    ========================================================== --}}
    @if (session('success'))

        <div
            class="alert alert-success alert-dismissible fade show rounded-3"
            role="alert"
        >

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
    @if (session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show rounded-3"
            role="alert"
        >

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
        VALIDATION ERROR
    ========================================================== --}}
    @if ($errors->any())

        <div
            class="alert alert-danger alert-dismissible fade show rounded-3"
            role="alert"
        >

            <strong>

                <i class="fas fa-exclamation-triangle me-1"></i>

                Penawaran belum dapat dikirim.

            </strong>

            <ul class="mb-0 mt-2">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        LIST PROYEK
    ========================================================== --}}
    <div class="row">

        @forelse($proyeks as $proyek)

            @php

                /*
                |--------------------------------------------------------------------------
                | CEK APAKAH PROYEK SUDAH DIAMBIL REKANAN LAIN
                |--------------------------------------------------------------------------
                */

                $sudahDiambil =
                    $proyek->rekanan_terpilih !== null &&
                    $proyek->rekanan_terpilih != Auth::id();


                /*
                |--------------------------------------------------------------------------
                | REKANAN YANG MENGAMBIL
                |--------------------------------------------------------------------------
                */

                $rekananPengambil = null;

                if ($sudahDiambil) {

                    $rekananPengambil =
                        App\Models\User::find(
                            $proyek->rekanan_terpilih
                        );

                }


                /*
                |--------------------------------------------------------------------------
                | STATUS PENAWARAN REKANAN LOGIN
                |--------------------------------------------------------------------------
                */

                $userStatus =
                    $proyek->rekananProyek
                        ->where(
                            'id_rekanan',
                            Auth::id()
                        )
                        ->first();


                /*
                |--------------------------------------------------------------------------
                | APAKAH REKANAN INI TERPILIH
                |--------------------------------------------------------------------------
                */

                $isSelected =
                    $proyek->rekanan_terpilih == Auth::id();


                /*
                |--------------------------------------------------------------------------
                | DIMENSI
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
                | FALLBACK VOLUME UNTUK DATA LAMA
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

            @endphp


            {{-- =================================================
                PROJECT CARD
            ================================================== --}}
            <div class="col-xl-4 col-lg-4 col-md-6 mb-4">

                <div class="card project-card h-100">

                    {{-- CARD HEADER --}}
                    <div class="card-header project-card-header">

                        <div class="d-flex justify-content-between align-items-start gap-2">

                            <div class="project-title-wrapper">

                                <div class="project-title-icon">
                                    <i class="fas fa-building"></i>
                                </div>

                                <div>

                                    <h6 class="project-title mb-0">

                                        {{ Str::limit(
                                            $proyek->nama_pembangunan,
                                            35
                                        ) }}

                                    </h6>

                                    @if($proyek->bidang_pekerjaan)

                                        <small class="text-muted">
                                            {{ $proyek->bidang_pekerjaan }}
                                        </small>

                                    @endif

                                </div>

                            </div>


                            {{-- STATUS --}}
                            @if ($sudahDiambil)

                                <span class="badge bg-danger status-badge">

                                    <i class="fas fa-user-check me-1"></i>

                                    Sudah Diambil

                                </span>

                            @elseif (
                                $isSelected &&
                                $userStatus &&
                                (
                                    $userStatus->status == 'setuju' ||
                                    $userStatus->status_penawaran == 'diterima'
                                )
                            )

                                <span class="badge bg-success status-badge">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Anda Terpilih

                                </span>

                            @elseif ($userStatus)

                                @if (
                                    $userStatus->status == 'setuju' ||
                                    $userStatus->status_penawaran == 'diterima'
                                )

                                    <span class="badge bg-success status-badge">

                                        <i class="fas fa-check-circle me-1"></i>

                                        Diterima

                                    </span>

                                @elseif (
                                    $userStatus->status == 'meninjau' ||
                                    $userStatus->status_penawaran == 'pending'
                                )

                                    <span class="badge bg-warning text-dark status-badge">

                                        <i class="fas fa-clock me-1"></i>

                                        Meninjau

                                    </span>

                                @elseif (
                                    $userStatus->status == 'tolak' ||
                                    $userStatus->status_penawaran == 'ditolak'
                                )

                                    <span class="badge bg-danger status-badge">

                                        <i class="fas fa-times-circle me-1"></i>

                                        Ditolak

                                    </span>

                                @elseif (
                                    $userStatus->status_penawaran == 'dipertimbangkan'
                                )

                                    <span class="badge bg-info text-dark status-badge">

                                        <i class="fas fa-hourglass-half me-1"></i>

                                        Dipertimbangkan

                                    </span>

                                @else

                                    <span class="badge bg-info status-badge">

                                        <i class="fas fa-check me-1"></i>

                                        Tersedia

                                    </span>

                                @endif

                            @else

                                <span class="badge bg-info status-badge">

                                    <i class="fas fa-check me-1"></i>

                                    Tersedia

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- CARD BODY --}}
                    <div class="card-body">

                        {{-- LOKASI --}}
                        <div class="project-info-row">

                            <div class="info-icon location">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>

                            <div>

                                <small class="info-label">
                                    Lokasi
                                </small>

                                <div class="info-value">
                                    {{ $proyek->lokasi ?? 'Lokasi tidak tersedia' }}
                                </div>

                            </div>

                        </div>


                        {{-- PEMBUAT --}}
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


                        {{-- TANGGAL --}}
                        <div class="project-info-row">

                            <div class="info-icon date">
                                <i class="fas fa-calendar-alt"></i>
                            </div>

                            <div>

                                <small class="info-label">
                                    Dipublikasikan
                                </small>

                                <div class="info-value">

                                    {{
                                        $proyek->created_at
                                            ? $proyek->created_at->format('d M Y')
                                            : '-'
                                    }}

                                </div>

                            </div>

                        </div>


                        {{-- DIMENSI PROYEK --}}
                        <div class="dimension-box">

                            <div class="dimension-box-header">

                                <div class="d-flex align-items-center">

                                    <div class="dimension-icon">
                                        <i class="fas fa-ruler-combined"></i>
                                    </div>

                                    <div>

                                        <div class="dimension-title">
                                            Ukuran Proyek
                                        </div>

                                        <small class="text-muted">
                                            Detail dimensi pekerjaan
                                        </small>

                                    </div>

                                </div>

                            </div>


                            @if($hasDimension)

                                <div class="row g-2 mt-1">

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


                                    {{-- KETEBALAN --}}
                                    <div class="col-4">

                                        <div class="dimension-item">

                                            <small>
                                                Ketebalan / Tinggi
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
                                <div class="volume-summary">

                                    <div>

                                        <small class="text-muted d-block">
                                            Volume
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

                                    <i class="fas fa-ruler-combined me-2"></i>

                                    <div>

                                        <small class="text-muted d-block">
                                            Volume
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

                                <div class="text-muted small">

                                    <i class="fas fa-ruler-combined me-1"></i>

                                    Detail ukuran belum tersedia.

                                </div>

                            @endif

                        </div>


                        {{-- PELAKSANAAN --}}
                        <div class="project-info-row">

                            <div class="info-icon schedule">
                                <i class="fas fa-calendar-day"></i>
                            </div>

                            <div>

                                <small class="info-label">
                                    Pelaksanaan
                                </small>

                                <div class="info-value">

                                    {{
                                        $proyek->tanggal_mulai
                                            ? $proyek->tanggal_mulai->format('d M Y')
                                            : '-'
                                    }}

                                    <span class="text-muted">
                                        s/d
                                    </span>

                                    {{
                                        $proyek->tanggal_selesai
                                            ? $proyek->tanggal_selesai->format('d M Y')
                                            : '-'
                                    }}

                                </div>

                            </div>

                        </div>


                        {{-- ANGGARAN --}}
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


                        {{-- HARGA ACUAN --}}
                        @if ($proyek->harga_penawaran)

                            <div class="reference-price-box">

                                <div class="d-flex align-items-center">

                                    <div class="reference-price-icon">
                                        <i class="fas fa-tag"></i>
                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Harga Acuan Proyek
                                        </small>

                                        <strong class="text-primary">

                                            Rp
                                            {{ number_format(
                                                $proyek->harga_penawaran,
                                                0,
                                                ',',
                                                '.'
                                            ) }}

                                        </strong>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- BATAS PENAWARAN --}}
                        @if ($proyek->batas_penawaran)

                            <div class="deadline-box">

                                <i class="fas fa-clock me-2"></i>

                                <div>

                                    <small>
                                        Batas akhir penawaran
                                    </small>

                                    <strong>

                                        {{
                                            $proyek->batas_penawaran
                                                ? $proyek->batas_penawaran->format('d M Y')
                                                : '-'
                                        }}

                                    </strong>

                                </div>

                            </div>

                        @endif


                        {{-- CATATAN --}}
                        @if ($proyek->catatan)

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


                    {{-- CARD FOOTER --}}
                    <div class="card-footer project-card-footer">

                        @if ($sudahDiambil)

                            <div class="taken-project-box">

                                <i class="fas fa-user-check"></i>

                                <div>

                                    <strong>
                                        Proyek sudah diambil
                                    </strong>

                                    <small>

                                        Pelaksana:
                                        {{
                                            $rekananPengambil->nama_user
                                            ?? 'Rekanan lain'
                                        }}

                                    </small>

                                </div>

                            </div>


                        @elseif (
                            $isSelected &&
                            $userStatus &&
                            (
                                $userStatus->status == 'setuju' ||
                                $userStatus->status_penawaran == 'diterima'
                            )
                        )

                            <div class="selected-project-box">

                                <i class="fas fa-check-circle"></i>

                                <div>

                                    <strong>
                                        Anda terpilih sebagai pelaksana
                                    </strong>

                                    <small>
                                        Penawaran Anda telah disetujui Admin.
                                    </small>

                                </div>

                            </div>


                        @elseif (
                            $userStatus &&
                            (
                                $userStatus->status == 'meninjau' ||
                                $userStatus->status_penawaran == 'pending'
                            )
                        )

                            <button
                                type="button"
                                class="btn btn-warning w-100 rounded-3"
                                disabled
                            >

                                <i class="fas fa-clock me-2"></i>

                                Menunggu Persetujuan

                            </button>


                        @elseif (
                            $userStatus &&
                            $userStatus->status_penawaran == 'dipertimbangkan'
                        )

                            <button
                                type="button"
                                class="btn btn-info text-dark w-100 rounded-3"
                                disabled
                            >

                                <i class="fas fa-hourglass-half me-2"></i>

                                Sedang Dipertimbangkan

                            </button>


                        @elseif (
                            $userStatus &&
                            (
                                $userStatus->status == 'tolak' ||
                                $userStatus->status_penawaran == 'ditolak'
                            )
                        )

                            @if($proyek->status_penawaran == 'open')

                                <button
                                    type="button"
                                    class="btn btn-outline-primary w-100 rounded-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#bidModal{{ $proyek->id_pembangunan }}"
                                >

                                    <i class="fas fa-redo me-2"></i>

                                    Ajukan Ulang Penawaran

                                </button>

                            @else

                                <button
                                    type="button"
                                    class="btn btn-secondary w-100 rounded-3"
                                    disabled
                                >

                                    <i class="fas fa-lock me-2"></i>

                                    Penawaran Ditutup

                                </button>

                            @endif


                        @elseif ($proyek->status_penawaran == 'closed')

                            <button
                                type="button"
                                class="btn btn-secondary w-100 rounded-3"
                                disabled
                            >

                                <i class="fas fa-lock me-2"></i>

                                Penawaran Ditutup

                            </button>


                        @else

                            <button
                                type="button"
                                class="btn btn-success w-100 rounded-3"
                                data-bs-toggle="modal"
                                data-bs-target="#bidModal{{ $proyek->id_pembangunan }}"
                            >

                                <i class="fas fa-gavel me-2"></i>

                                Ajukan Penawaran

                            </button>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =================================================
                MODAL AJUKAN PENAWARAN
            ================================================== --}}
            @if (
                !$sudahDiambil &&
                $proyek->status_penawaran == 'open' &&
                (
                    !$userStatus ||
                    $userStatus->status != 'setuju'
                )
            )

                <div
                    class="modal fade"
                    id="bidModal{{ $proyek->id_pembangunan }}"
                    tabindex="-1"
                    aria-labelledby="bidModalLabel{{ $proyek->id_pembangunan }}"
                    aria-hidden="true"
                >

                    <div
                        class="modal-dialog modal-dialog-centered modal-dialog-scrollable bid-modal-dialog"
                    >

                        <div class="modal-content bid-modal">


                            {{-- =================================================
                                HEADER
                            ================================================== --}}
                            <div class="modal-header bid-modal-header">

                                <div>

                                    <h5
                                        class="modal-title text-white mb-1"
                                        id="bidModalLabel{{ $proyek->id_pembangunan }}"
                                    >

                                        <i class="fas fa-gavel me-2"></i>

                                        Ajukan Penawaran

                                    </h5>

                                    <small class="text-white-50">

                                        Berikan harga terbaik Anda untuk proyek ini.

                                    </small>

                                </div>


                                <button
                                    type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                                ></button>

                            </div>


                            {{-- =================================================
                                FORM
                            ================================================== --}}
                            <form
                                action="{{ route(
                                    'rekanan.bid',
                                    $proyek->id_pembangunan
                                ) }}"
                                method="POST"
                            >

                                @csrf


                                {{-- =================================================
                                    BODY
                                ================================================== --}}
                                <div class="modal-body bid-modal-body">


                                    {{-- =================================================
                                        PROJECT SUMMARY
                                    ================================================== --}}
                                    <div class="bid-project-summary">

                                        <div class="bid-summary-icon">
                                            <i class="fas fa-building"></i>
                                        </div>

                                        <div class="bid-summary-content">

                                            <small class="text-muted">
                                                Anda akan mengajukan penawaran untuk:
                                            </small>

                                            <strong class="d-block">

                                                {{ $proyek->nama_pembangunan }}

                                            </strong>

                                            <small class="text-muted">

                                                <i class="fas fa-map-marker-alt me-1"></i>

                                                {{ $proyek->lokasi }}

                                            </small>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        DETAIL UKURAN
                                    ================================================== --}}
                                    @if($hasDimension)

                                        <div class="bid-dimension-panel">

                                            <div class="bid-dimension-title">

                                                <i class="fas fa-ruler-combined me-2"></i>

                                                Detail Ukuran Pekerjaan

                                            </div>


                                            <div class="row g-2 mt-1">

                                                {{-- PANJANG --}}
                                                <div class="col-4">

                                                    <div class="bid-dimension-item">

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

                                                            m

                                                        </strong>

                                                    </div>

                                                </div>


                                                {{-- LEBAR --}}
                                                <div class="col-4">

                                                    <div class="bid-dimension-item">

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

                                                            m

                                                        </strong>

                                                    </div>

                                                </div>


                                                {{-- KETEBALAN --}}
                                                <div class="col-4">

                                                    <div class="bid-dimension-item">

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

                                                            m

                                                        </strong>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- VOLUME --}}
                                            <div class="bid-volume">

                                                <span>
                                                    Volume pekerjaan
                                                </span>

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

                                        </div>

                                    @endif


                                    {{-- =================================================
                                        INFO ANGGARAN
                                    ================================================== --}}
                                    <div class="alert alert-info rounded-3">

                                        <div class="d-flex align-items-start">

                                            <i class="fas fa-info-circle me-2 mt-1"></i>

                                            <div>

                                                <div class="mb-1">

                                                    <i class="fas fa-money-bill text-success me-1"></i>

                                                    Anggaran Proyek:

                                                    <strong>

                                                        Rp
                                                        {{ number_format(
                                                            $proyek->anggaran ?? 0,
                                                            0,
                                                            ',',
                                                            '.'
                                                        ) }}

                                                    </strong>

                                                </div>


                                                @if ($proyek->harga_penawaran)

                                                    <div>

                                                        <i class="fas fa-tag text-primary me-1"></i>

                                                        Harga Acuan:

                                                        <strong>

                                                            Rp
                                                            {{ number_format(
                                                                $proyek->harga_penawaran,
                                                                0,
                                                                ',',
                                                                '.'
                                                            ) }}

                                                        </strong>

                                                    </div>

                                                @endif

                                            </div>

                                        </div>

                                    </div>


                                    {{-- =================================================
                                        HARGA PENAWARAN
                                    ================================================== --}}
                                    <div class="mb-3">

                                        <label class="form-label fw-bold">

                                            <i class="fas fa-money-bill-wave text-success me-1"></i>

                                            Harga yang Anda Tawarkan

                                            <span class="text-danger">
                                                *
                                            </span>

                                        </label>


                                        <div class="input-group input-group-lg">

                                            <span class="input-group-text">
                                                Rp
                                            </span>

                                            <input
                                                type="number"
                                                name="harga_penawaran"
                                                class="form-control"
                                                placeholder="Masukkan harga penawaran Anda"
                                                min="1000"
                                                step="1"
                                                required
                                            >

                                        </div>


                                        <small class="text-muted">

                                            Masukkan harga yang Anda sanggupi
                                            untuk mengerjakan proyek ini.

                                        </small>

                                    </div>


                                    {{-- =================================================
                                        CATATAN
                                    ================================================== --}}
                                    <div class="mb-3">

                                        <label class="form-label fw-bold">

                                            <i class="fas fa-comment-alt text-primary me-1"></i>

                                            Catatan

                                        </label>


                                        <textarea
                                            name="catatan"
                                            class="form-control"
                                            rows="4"
                                            minlength="5"
                                            placeholder="Tambahkan catatan tentang kemampuan, metode pengerjaan, estimasi, atau informasi lain..."
                                        ></textarea>


                                        <small class="text-muted">

                                            Minimal 5 karakter jika ingin mengisi catatan.

                                        </small>

                                    </div>


                                    {{-- =================================================
                                        WARNING
                                    ================================================== --}}
                                    <div class="alert alert-warning rounded-3 mb-0">

                                        <i class="fas fa-info-circle me-2"></i>

                                        Penawaran Anda akan direview oleh Admin.
                                        Jika disetujui, Anda akan ditunjuk sebagai
                                        pelaksana proyek.

                                    </div>


                                </div>


                                {{-- =================================================
                                    FOOTER
                                ================================================== --}}
                                <div class="modal-footer bid-modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-secondary rounded-3"
                                        data-bs-dismiss="modal"
                                    >

                                        Batal

                                    </button>


                                    <button
                                        type="submit"
                                        class="btn btn-primary rounded-3"
                                    >

                                        <i class="fas fa-paper-plane me-2"></i>

                                        Kirim Penawaran

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            @endif

        @empty

            {{-- =================================================
                EMPTY STATE
            ================================================== --}}
            <div class="col-12">

                <div class="empty-project-state">

                    <div class="empty-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>

                    <h5 class="text-muted">
                        Belum Ada Proyek Tersedia
                    </h5>

                    <p class="text-muted mb-0">
                        Saat ini belum ada proyek yang tersedia untuk Anda.
                    </p>

                </div>

            </div>

        @endforelse

    </div>


    {{-- =========================================================
        PAGINATION
    ========================================================== --}}
    @if($proyeks->hasPages())

        <div class="row mt-3">

            <div class="col-12">

                <div class="pagination-wrapper">

                    {{ $proyeks->links() }}

                </div>

            </div>

        </div>

    @endif

</div>


{{-- =============================================================
    STYLE
============================================================= --}}
<style>

/* =========================================================
   PAGE
========================================================= */

.project-available-page {
    padding-bottom: 30px;
}


/* =========================================================
   HEADER
========================================================= */

.available-header {

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

}

.header-icon {

    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: #eaf2ff;
    color: #1e3c72;

}

.total-project-badge {

    padding: 9px 15px;

    border-radius: 50px;

    background: #1e3c72;
    color: #fff;

    font-size: 13px;
    font-weight: 600;

}

.header-description {

    margin-top: 14px;

    padding: 12px 15px;

    border-radius: 10px;

    background: #f8fbff;

    border: 1px solid #e5edf8;

    color: #64748b;

    font-size: 13px;

}


/* =========================================================
   PROJECT CARD
========================================================= */

.project-card {

    border: 1px solid #e7ebf0;

    border-radius: 16px;

    overflow: hidden;

    background: #fff;

    transition:
        transform .25s ease,
        box-shadow .25s ease,
        border-color .25s ease;

}

.project-card:hover {

    transform: translateY(-5px);

    box-shadow:
        0 15px 35px rgba(30, 60, 114, .12);

    border-color: #d5e2f5;

}

.project-card-header {

    padding: 16px;

    background: #fff;

    border-bottom: 1px solid #eef1f5;

}

.project-title-wrapper {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    min-width: 0;

}

.project-title-icon {

    width: 35px;
    height: 35px;
    min-width: 35px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #edf4ff;
    color: #1e3c72;

}

.project-title {

    color: #1e3c72;

    line-height: 1.35;

}

.status-badge {

    white-space: nowrap;

    font-size: 10px;

    padding: 6px 8px;

}


/* =========================================================
   INFO ROW
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

.info-icon.date {
    color: #f0ad00;
}

.info-icon.schedule {
    color: #8b5cf6;
}

.info-icon.budget {
    color: #198754;
}

.info-label {

    display: block;

    color: #8a94a6;

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: .3px;

}

.info-value {

    color: #394150;

    font-size: 12px;

    font-weight: 500;

}


/* =========================================================
   DIMENSION BOX
========================================================= */

.dimension-box {

    margin: 15px 0;

    padding: 12px;

    border-radius: 13px;

    background: #f8fbff;

    border: 1px solid #dce8f7;

}

.dimension-box-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

}

.dimension-icon {

    width: 32px;
    height: 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #e6f0ff;
    color: #1e3c72;

    margin-right: 9px;

}

.dimension-title {

    font-size: 12px;

    font-weight: 700;

    color: #1e3c72;

}

.dimension-item {

    padding: 8px;

    border-radius: 9px;

    background: #fff;

    border: 1px solid #e9eef5;

    text-align: center;

}

.dimension-item small {

    display: block;

    color: #8a94a6;

    font-size: 9px;

    margin-bottom: 3px;

}

.dimension-item strong {

    display: block;

    color: #1e3c72;

    font-size: 13px;

}

.dimension-item strong span {

    font-size: 10px;

    color: #64748b;

}

.volume-summary {

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

.volume-summary small {

    font-size: 10px;

}

.volume-summary strong {

    color: #198754;

    font-size: 15px;

}

.volume-formula {

    padding: 5px 8px;

    border-radius: 50px;

    background: #edf4ff;

    color: #1e3c72;

    font-size: 10px;

    font-weight: 600;

}

.old-volume-box {

    display: flex;

    align-items: center;

    padding: 10px;

    background: #fff;

    border-radius: 9px;

    border: 1px solid #e9eef5;

}


/* =========================================================
   PRICE
========================================================= */

.reference-price-box {

    margin-top: 12px;

    padding: 11px;

    border-radius: 11px;

    background: #f4f8ff;

    border: 1px solid #dce8f7;

}

.reference-price-icon {

    width: 32px;
    height: 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: #e4efff;
    color: #0d6efd;

    margin-right: 10px;

}


/* =========================================================
   DEADLINE
========================================================= */

.deadline-box {

    display: flex;

    align-items: center;

    gap: 8px;

    padding: 10px 12px;

    margin-top: 10px;

    border-radius: 10px;

    background: #fff9e8;

    color: #a16207;

    border: 1px solid #f7e7b0;

}

.deadline-box small {

    display: block;

    font-size: 9px;

}

.deadline-box strong {

    display: block;

    font-size: 11px;

}


/* =========================================================
   NOTE
========================================================= */

.project-note {

    margin-top: 12px;

    padding: 10px 12px;

    border-radius: 10px;

    background: #f8f9fa;

    border-left: 3px solid #1e3c72;

}

.note-title {

    font-size: 11px;

    font-weight: 700;

    color: #1e3c72;

    margin-bottom: 4px;

}

.note-content {

    font-size: 11px;

    color: #64748b;

    line-height: 1.5;

}


/* =========================================================
   CARD FOOTER
========================================================= */

.project-card-footer {

    padding: 13px 16px;

    background: #fff;

    border-top: 1px solid #eef1f5;

}

.taken-project-box,
.selected-project-box {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 10px;

    border-radius: 10px;

}

.taken-project-box {

    background: #fff5f5;

    color: #dc3545;

}

.selected-project-box {

    background: #effaf3;

    color: #198754;

}

.taken-project-box i,
.selected-project-box i {

    font-size: 20px;

}

.taken-project-box strong,
.selected-project-box strong {

    display: block;

    font-size: 11px;

}

.taken-project-box small,
.selected-project-box small {

    display: block;

    font-size: 10px;

    color: #64748b;

}


/* =========================================================
   =========================================================
   BID MODAL
   =========================================================
========================================================= */

.bid-modal-dialog {

    /*
     * Modal tidak boleh memenuhi seluruh layar.
     */
    max-width: 600px;

    margin: 1.75rem auto;

}


/* =========================================================
   MODAL CONTENT
========================================================= */

.bid-modal {

    border: 0;

    border-radius: 17px;

    overflow: hidden;

    background: #fff;

    /*
     * Tinggi maksimal modal.
     */
    max-height: 90vh;

    /*
     * Flex supaya header,
     * body dan footer tersusun dengan benar.
     */
    display: flex;

    flex-direction: column;

}


/* =========================================================
   MODAL HEADER
========================================================= */

.bid-modal-header {

    flex-shrink: 0;

    background:
        linear-gradient(
            135deg,
            #1e3c72 0%,
            #2a5298 100%
        );

    border: 0;

    padding: 18px 20px;

}


/* =========================================================
   MODAL FORM
========================================================= */

.bid-modal > form {

    display: flex;

    flex-direction: column;

    min-height: 0;

    flex: 1;

}


/* =========================================================
   MODAL BODY
========================================================= */

.bid-modal-body {

    /*
     * INI BAGIAN PENTING.
     *
     * Hanya body yang melakukan scroll.
     */
    overflow-y: auto;

    overflow-x: hidden;

    /*
     * Flex agar body mengambil ruang yang tersedia.
     */
    flex: 1;

    min-height: 0;

    /*
     * Scrollbar Firefox.
     */
    scrollbar-width: thin;

    scrollbar-color:
        #b8c4d6
        transparent;

    /*
     * Padding.
     */
    padding: 20px !important;

}


/* =========================================================
   SCROLLBAR CHROME / EDGE / SAFARI
========================================================= */

.bid-modal-body::-webkit-scrollbar {

    width: 7px;

}

.bid-modal-body::-webkit-scrollbar-track {

    background: transparent;

}

.bid-modal-body::-webkit-scrollbar-thumb {

    background: #b8c4d6;

    border-radius: 20px;

}

.bid-modal-body::-webkit-scrollbar-thumb:hover {

    background: #8fa1bb;

}


/* =========================================================
   PROJECT SUMMARY
========================================================= */

.bid-project-summary {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 13px;

    margin-bottom: 15px;

    border-radius: 12px;

    background: #f7f9fc;

    border: 1px solid #e8edf3;

}

.bid-summary-icon {

    width: 42px;
    height: 42px;
    min-width: 42px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #eaf2ff;

    color: #1e3c72;

}

.bid-project-summary strong {

    color: #1e3c72;

    font-size: 13px;

}

.bid-summary-content {

    min-width: 0;

    overflow-wrap: anywhere;

}


/* =========================================================
   BID DIMENSION
========================================================= */

.bid-dimension-panel {

    padding: 13px;

    margin-bottom: 15px;

    border-radius: 12px;

    background: #f8fbff;

    border: 1px solid #dce8f7;

}

.bid-dimension-title {

    font-size: 12px;

    font-weight: 700;

    color: #1e3c72;

}

.bid-dimension-item {

    padding: 8px;

    text-align: center;

    background: #fff;

    border: 1px solid #e6ebf2;

    border-radius: 9px;

}

.bid-dimension-item small {

    display: block;

    font-size: 9px;

    color: #8a94a6;

}

.bid-dimension-item strong {

    display: block;

    font-size: 12px;

    color: #1e3c72;

}

.bid-volume {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    margin-top: 8px;

    padding: 9px 10px;

    background: #fff;

    border-radius: 8px;

}

.bid-volume span {

    font-size: 10px;

    color: #64748b;

}

.bid-volume strong {

    color: #198754;

    font-size: 14px;

}


/* =========================================================
   MODAL FOOTER
========================================================= */

.bid-modal-footer {

    flex-shrink: 0;

    background: #fff;

    border-top: 1px solid #e9edf3;

    padding: 12px 20px;

}


/* =========================================================
   BUTTON FOOTER
========================================================= */

.bid-modal-footer .btn {

    min-height: 42px;

}


/* =========================================================
   EMPTY
========================================================= */

.empty-project-state {

    padding: 70px 20px;

    text-align: center;

}

.empty-icon {

    width: 80px;
    height: 80px;

    margin: 0 auto 20px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #f3f5f8;

    color: #adb5bd;

    font-size: 34px;

}


/* =========================================================
   PAGINATION
========================================================= */

.pagination-wrapper {

    display: flex;

    justify-content: center;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .available-header {

        align-items: flex-start;

        flex-direction: column;

    }


    .total-project-badge {

        align-self: flex-start;

    }


    .project-card {

        border-radius: 14px;

    }


    .project-title {

        font-size: 13px;

    }


    .dimension-item strong {

        font-size: 11px;

    }


    .dimension-item small {

        font-size: 8px;

    }


    .volume-summary {

        align-items: flex-start;

        flex-direction: column;

    }


    .volume-formula {

        align-self: flex-start;

    }


    /* =====================================================
       MODAL MOBILE
    ===================================================== */

    .bid-modal-dialog {

        max-width: none;

        margin: .75rem;

    }


    .bid-modal {

        max-height: 94vh;

        border-radius: 14px;

    }


    .bid-modal-header {

        padding: 15px 16px;

    }


    .bid-modal-body {

        padding: 16px !important;

    }


    .bid-modal-footer {

        padding: 10px 14px;

        gap: 8px;

    }


    .bid-modal-footer .btn {

        flex: 1;

    }

}


/* =========================================================
   EXTRA SMALL DEVICE
========================================================= */

@media (max-width: 400px) {

    .bid-modal-dialog {

        margin: .5rem;

    }


    .bid-modal {

        max-height: 96vh;

    }


    .bid-modal-header h5 {

        font-size: 16px;

    }


    .bid-modal-header small {

        font-size: 10px;

    }


    .bid-modal-body {

        padding: 13px !important;

    }


    .bid-modal-footer {

        padding: 9px 12px;

    }


    .bid-modal-footer .btn {

        font-size: 12px;

        padding: 8px 10px;

    }

}

</style>

@endsection