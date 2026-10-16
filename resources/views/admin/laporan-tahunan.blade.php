@extends('layouts.app')

@section('title', 'Laporan Tahunan')
@section('page_title', 'Laporan Tahunan')
@section('page_subtitle', 'Rekap proyek dan penggunaan anggaran pembangunan desa')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>
                    <h4 class="fw-bold mb-1">
                        <i class="fas fa-chart-column text-primary me-2"></i>
                        Laporan Tahunan Proyek
                    </h4>

                    <p class="text-muted mb-0">
                        Rekap proyek, anggaran, pengeluaran dan sisa anggaran.
                    </p>
                </div>

                <div class="d-flex gap-2">

                    {{-- PREVIEW PDF — SKIP LOADER --}}
                    <a
                        href="{{ route('admin.laporan-tahunan.preview', request()->query()) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-outline-primary"
                        data-no-page-loader
                    >
                        <i class="fas fa-eye me-1"></i>
                        Preview PDF
                    </a>

                    {{-- DOWNLOAD PDF — SKIP LOADER --}}
                    <a
                        href="{{ route('admin.laporan-tahunan.download', request()->query()) }}"
                        class="btn btn-primary"
                        data-no-page-loader
                    >
                        <i class="fas fa-file-pdf me-1"></i>
                        Download PDF
                    </a>

                </div>

            </div>

        </div>
    </div>


    {{-- FILTER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-1">
                <i class="fas fa-filter text-primary me-2"></i>
                Filter Laporan
            </h5>

            <small class="text-muted">
                Pilih periode dan status proyek yang ingin ditampilkan.
            </small>

        </div>

        <div class="card-body px-4 pb-4">

            <form
                action="{{ route('admin.laporan-tahunan') }}"
                method="GET"
            >

                <div class="row g-3">

                    {{-- TAHUN DARI --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Tahun Dari
                        </label>

                        <select
                            name="tahun_dari"
                            class="form-select"
                        >

                            <option value="">
                                Semua Tahun
                            </option>

                            @foreach($tahunList as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ request('tahun_dari') == $tahun ? 'selected' : '' }}
                                >
                                    {{ $tahun }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- TAHUN SAMPAI --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Tahun Sampai
                        </label>

                        <select
                            name="tahun_sampai"
                            class="form-select"
                        >

                            <option value="">
                                Semua Tahun
                            </option>

                            @foreach($tahunList as $tahun)

                                <option
                                    value="{{ $tahun }}"
                                    {{ request('tahun_sampai') == $tahun ? 'selected' : '' }}
                                >
                                    {{ $tahun }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Status Proyek
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            @foreach($statusList as $status)

                                <option
                                    value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}
                                >
                                    {{ ucfirst($status) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTON --}}
                    <div class="col-12">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="fas fa-search me-1"></i>
                                Tampilkan
                            </button>

                            <a
                                href="{{ route('admin.laporan-tahunan') }}"
                                class="btn btn-outline-secondary"
                            >
                                <i class="fas fa-rotate-left me-1"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Total Proyek
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $totalProyek }}
                            </h3>
                        </div>

                        <div class="text-primary fs-2">
                            <i class="fas fa-building"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Total Anggaran
                            </small>

                            <h5 class="fw-bold mb-0">
                                Rp {{ number_format($totalAnggaran, 0, ',', '.') }}
                            </h5>
                        </div>

                        <div class="text-success fs-2">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Dana Terpakai
                            </small>

                            <h5 class="fw-bold mb-0 text-danger">
                                Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}
                            </h5>
                        </div>

                        <div class="text-danger fs-2">
                            <i class="fas fa-arrow-trend-down"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Sisa Anggaran
                            </small>

                            <h5 class="fw-bold mb-0 text-success">
                                Rp {{ number_format($totalSisaAnggaran, 0, ',', '.') }}
                            </h5>
                        </div>

                        <div class="text-success fs-2">
                            <i class="fas fa-wallet"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- STATUS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <span>
                            <i class="fas fa-circle-check text-success me-2"></i>
                            Selesai
                        </span>

                        <strong>
                            {{ $totalSelesai }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <span>
                            <i class="fas fa-spinner text-warning me-2"></i>
                            Berjalan
                        </span>

                        <strong>
                            {{ $totalBerjalan }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <span>
                            <i class="fas fa-clock text-secondary me-2"></i>
                            Direncanakan
                        </span>

                        <strong>
                            {{ $totalDirencanakan }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- DATA PROYEK --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>

                    <h5 class="fw-bold mb-1">
                        <i class="fas fa-list-check text-primary me-2"></i>
                        Rekap Proyek
                    </h5>

                    <small class="text-muted">
                        Menampilkan proyek berdasarkan filter yang dipilih.
                    </small>

                </div>

                <span class="badge bg-primary">
                    {{ $proyeks->total() }} Proyek
                </span>

            </div>

        </div>


        <div class="card-body px-4">

            @if($proyeks->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="50">
                                    No
                                </th>

                                <th>
                                    Proyek
                                </th>

                                <th>
                                    Tahun
                                </th>

                                <th>
                                    Lokasi
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Rekanan / Mandor
                                </th>

                                <th class="text-end">
                                    Anggaran
                                </th>

                                <th class="text-end">
                                    Terpakai
                                </th>

                                <th class="text-end">
                                    Sisa
                                </th>

                                <th width="80">
                                    Detail
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($proyeks as $proyek)

                                @php

                                    $totalPengeluaran =
                                        $proyek->pengeluarans->sum('jumlah');

                                    $sisaAnggaran =
                                        max(
                                            0,
                                            $proyek->anggaran - $totalPengeluaran
                                        );

                                @endphp

                                <tr>

                                    <td>
                                        {{ $proyeks->firstItem() + $loop->index }}
                                    </td>


                                    <td>

                                        <div class="fw-semibold">
                                            {{ $proyek->nama_pembangunan }}
                                        </div>

                                    </td>


                                    <td>
                                        {{ $proyek->tahun_anggaran }}
                                    </td>


                                    <td>
                                        {{ $proyek->lokasi ?? '-' }}
                                    </td>


                                    <td>

                                        @if($proyek->status === 'selesai')

                                            <span class="badge bg-success">
                                                Selesai
                                            </span>

                                        @elseif($proyek->status === 'berjalan')

                                            <span class="badge bg-warning text-dark">
                                                Berjalan
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Direncanakan
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($proyek->rekananSetuju && $proyek->rekananSetuju->count())

                                            @foreach($proyek->rekananSetuju as $rekanan)

                                                <div>
                                                    {{ $rekanan->nama_user ?? '-' }}
                                                </div>

                                            @endforeach

                                        @else

                                            <span class="text-muted">
                                                Belum ditentukan
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-end">

                                        Rp
                                        {{ number_format(
                                            $proyek->anggaran,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>


                                    <td class="text-end text-danger">

                                        Rp
                                        {{ number_format(
                                            $totalPengeluaran,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>


                                    <td class="text-end text-success">

                                        Rp
                                        {{ number_format(
                                            $sisaAnggaran,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </td>


                                    <td>

                                        <a
                                            href="{{ route(
                                                'admin.proyek.pengeluaran.index',
                                                $proyek->id_pembangunan
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Lihat Pengeluaran"
                                        >
                                            <i class="fas fa-receipt"></i>
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- PAGINATION --}}
                <div class="mt-4">

                    {{ $proyeks->links() }}

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fas fa-folder-open fs-1 text-muted mb-3"></i>

                    <h5 class="fw-bold">
                        Tidak Ada Data Proyek
                    </h5>

                    <p class="text-muted mb-0">
                        Tidak ditemukan proyek berdasarkan filter yang dipilih.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection