@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- =====================================================
         HEADER — STYLE SEPERTI LAPORAN TAHUNAN
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                {{-- KIRI: JUDUL --}}

                <div>

                    <h4 class="fw-bold mb-1">

                        <i class="fas fa-building text-primary me-2"></i>

                        Kelola Proyek

                    </h4>

                    <p class="text-muted mb-0">

                        Kelola proyek, dimensi pekerjaan, penawaran,
                        progres dan pengeluaran.

                    </p>

                </div>


                {{-- KANAN: TOMBOL --}}

                <div class="d-flex flex-wrap gap-2">

                    {{-- PREVIEW PDF — SKIP LOADER --}}

                    <a
                        href="{{ route('proyek.laporan-penawar.preview') }}"
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
                        href="{{ route('proyek.laporan-penawar.download') }}"
                        class="btn btn-danger"
                        data-no-page-loader
                    >

                        <i class="fas fa-file-pdf me-1"></i>

                        Download PDF

                    </a>


                    {{-- TAMBAH PROYEK --}}

                    <button
                        type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#tambahModal"
                    >

                        <i class="fas fa-plus me-1"></i>

                        Tambah Proyek

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ALERT
    ====================================================== --}}

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


    @if($errors->any())

        <div class="alert alert-danger rounded-3">

            <strong>
                <i class="fas fa-exclamation-triangle me-1"></i>
                Periksa kembali input:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         TABEL
    ====================================================== --}}

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                    id="dataTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th class="text-center">
                                No
                            </th>

                            <th>
                                Proyek
                            </th>

                            <th>
                                Lokasi
                            </th>

                            <th>
                                Dimensi / Volume
                            </th>

                            <th>
                                Anggaran
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Publikasi
                            </th>

                            <th>
                                Progres
                            </th>

                            <th>
                                Rekanan
                            </th>

                            <th>
                                Penawaran
                            </th>

                            <th>
                                Pengeluaran
                            </th>

                            <th class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($proyeks as $index => $p)

                            @php

                                $lastMonitoring =
                                    $p->monitorings->first();

                                $progress =
                                    $lastMonitoring
                                        ? (int) $lastMonitoring->progres
                                        : 0;

                                $totalPengeluaran =
                                    isset($p->pengeluarans)
                                        ? $p->pengeluarans->sum('jumlah')
                                        : 0;

                                $sisaAnggaran =
                                    max(
                                        0,
                                        (float) $p->anggaran -
                                        (float) $totalPengeluaran
                                    );

                                $persentasePengeluaran =
                                    $p->anggaran > 0
                                        ? min(
                                            100,
                                            (
                                                $totalPengeluaran /
                                                $p->anggaran
                                            ) * 100
                                        )
                                        : 0;

                                $penawaranCount =
                                    $p->rekananProyek
                                        ? $p->rekananProyek->count()
                                        : 0;

                            @endphp


                            <tr>


                                {{-- NO --}}

                                <td class="text-center">

                                    {{ $proyeks->firstItem() + $index }}

                                </td>


                                {{-- PROYEK --}}

                                <td>

                                    <div class="fw-bold">

                                        {{ $p->nama_pembangunan }}

                                    </div>

                                    <small class="text-muted">

                                        Tahun
                                        {{ $p->tahun_anggaran }}

                                    </small>

                                </td>


                                {{-- LOKASI --}}

                                <td>

                                    <i class="fas fa-location-dot text-danger me-1"></i>

                                    {{ $p->lokasi }}

                                </td>


                                {{-- DIMENSI --}}

                                <td>

                                    @if(
                                        $p->panjang !== null &&
                                        $p->lebar !== null &&
                                        $p->ketebalan_tinggi !== null
                                    )

                                        <div class="fw-bold text-primary">

                                            {{
                                                rtrim(
                                                    rtrim(
                                                        number_format(
                                                            (float) $p->panjang,
                                                            2,
                                                            ',',
                                                            '.'
                                                        ),
                                                        '0'
                                                    ),
                                                    ','
                                                )
                                            }}

                                            ×

                                            {{
                                                rtrim(
                                                    rtrim(
                                                        number_format(
                                                            (float) $p->lebar,
                                                            2,
                                                            ',',
                                                            '.'
                                                        ),
                                                        '0'
                                                    ),
                                                    ','
                                                )
                                            }}

                                            ×

                                            {{
                                                rtrim(
                                                    rtrim(
                                                        number_format(
                                                            (float) $p->ketebalan_tinggi,
                                                            2,
                                                            ',',
                                                            '.'
                                                        ),
                                                        '0'
                                                    ),
                                                    ','
                                                )
                                            }}

                                            m

                                        </div>

                                        <small class="text-muted">

                                            Panjang × Lebar × Tinggi

                                        </small>

                                    @endif


                                    @if($p->volume_proyek !== null)

                                        <div class="mt-1">

                                            <span class="badge bg-primary-subtle text-primary">

                                                Volume:

                                                {{
                                                    rtrim(
                                                        rtrim(
                                                            number_format(
                                                                (float) $p->volume_proyek,
                                                                2,
                                                                ',',
                                                                '.'
                                                            ),
                                                            '0'
                                                        ),
                                                        ','
                                                    )
                                                }}

                                                {{ $p->satuan_volume }}

                                            </span>

                                        </div>

                                    @endif

                                </td>


                                {{-- ANGGARAN --}}

                                <td>

                                    <strong class="text-success">

                                        Rp
                                        {{ number_format(
                                            $p->anggaran,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </strong>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($p->status === 'direncanakan')

                                        <span class="badge bg-secondary">

                                            Direncanakan

                                        </span>

                                    @elseif($p->status === 'berjalan')

                                        <span class="badge bg-warning text-dark">

                                            Berjalan

                                        </span>

                                    @else

                                        <span class="badge bg-success">

                                            Selesai

                                        </span>

                                    @endif

                                </td>


                                {{-- PUBLIKASI --}}

                                <td>

                                    @if($p->is_published)

                                        <span class="badge bg-success">

                                            <i class="fas fa-globe me-1"></i>

                                            Dipublikasi

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            <i class="fas fa-eye-slash me-1"></i>

                                            Belum

                                        </span>

                                    @endif

                                </td>


                                {{-- PROGRES --}}

                                <td>

                                    <div class="d-flex align-items-center">

                                        <div
                                            class="progress flex-grow-1 me-2"
                                            style="height:6px; min-width:70px;"
                                        >

                                            <div
                                                class="progress-bar {{
                                                    $progress >= 100
                                                        ? 'bg-success'
                                                        : 'bg-primary'
                                                }}"
                                                style="width:{{ min(100, max(0, $progress)) }}%"
                                            ></div>

                                        </div>

                                        <small>

                                            {{ $progress }}%

                                        </small>

                                    </div>

                                </td>


                                {{-- REKANAN --}}

                                <td>

                                    @if($p->rekanan_terpilih)

                                        @php

                                            $rekananTerpilih =
                                                $p->rekananProyek
                                                    ->where(
                                                        'id_rekanan',
                                                        $p->rekanan_terpilih
                                                    )
                                                    ->first();

                                        @endphp


                                        @if(
                                            $rekananTerpilih &&
                                            $rekananTerpilih->rekanan
                                        )

                                            <span class="badge bg-primary rounded-pill">

                                                <i class="fas fa-user-check me-1"></i>

                                                {{
                                                    $rekananTerpilih
                                                        ->rekanan
                                                        ->nama_user
                                                }}

                                            </span>

                                            <br>

                                            <small class="text-muted">

                                                Rp
                                                {{
                                                    number_format(
                                                        $rekananTerpilih
                                                            ->harga_penawaran_rekanan
                                                        ?? 0,
                                                        0,
                                                        ',',
                                                        '.'
                                                    )
                                                }}

                                            </small>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    @else

                                        <span class="text-muted small">

                                            Belum ada

                                        </span>

                                    @endif

                                </td>


                                {{-- PENAWARAN --}}

                                <td>

                                    @if($penawaranCount > 0)

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-primary rounded-3"
                                            onclick="showPenawaran({{ $p->id_pembangunan }})"
                                        >

                                            <i class="fas fa-list me-1"></i>

                                            {{ $penawaranCount }}

                                        </button>

                                    @else

                                        <span class="text-muted small">

                                            Belum ada

                                        </span>

                                    @endif

                                </td>


                                {{-- PENGELUARAN --}}

                                <td>

                                    @if($totalPengeluaran > 0)

                                        <div class="small fw-bold text-danger">

                                            Rp
                                            {{
                                                number_format(
                                                    $totalPengeluaran,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}

                                        </div>

                                        <div class="small text-success">

                                            Sisa Rp
                                            {{
                                                number_format(
                                                    $sisaAnggaran,
                                                    0,
                                                    ',',
                                                    '.'
                                                )
                                            }}

                                        </div>

                                        <div
                                            class="progress mt-1"
                                            style="height:5px;"
                                        >

                                            <div
                                                class="progress-bar {{
                                                    $persentasePengeluaran >= 90
                                                        ? 'bg-danger'
                                                        : (
                                                            $persentasePengeluaran >= 70
                                                                ? 'bg-warning'
                                                                : 'bg-success'
                                                        )
                                                }}"
                                                style="width:{{ $persentasePengeluaran }}%"
                                            ></div>

                                        </div>

                                    @else

                                        <span class="text-muted small">

                                            Belum ada

                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}

                                <td class="text-center">

                                    <div class="d-flex justify-content-center flex-wrap gap-1">


                                        {{-- DETAIL --}}

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-info text-white"
                                            onclick="showDetail({{ $p->id_pembangunan }})"
                                            title="Detail"
                                        >

                                            <i class="fas fa-eye"></i>

                                        </button>


                                        {{-- EDIT --}}

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning"
                                            onclick="editData({{ $p->id_pembangunan }}, this)"
                                            title="Edit"
                                        >

                                            <i class="fas fa-edit"></i>

                                        </button>


                                        {{-- PUBLISH --}}

                                        <a
                                            href="{{ route(
                                                'proyek.publish',
                                                $p->id_pembangunan
                                            ) }}"
                                            class="btn btn-sm {{
                                                $p->is_published
                                                    ? 'btn-secondary'
                                                    : 'btn-success'
                                            }}"
                                            title="{{
                                                $p->is_published
                                                    ? 'Tutup Publikasi'
                                                    : 'Publikasikan'
                                            }}"
                                        >

                                            <i class="fas {{
                                                $p->is_published
                                                    ? 'fa-eye-slash'
                                                    : 'fa-globe'
                                            }}"></i>

                                        </a>


                                        {{-- PENGELUARAN --}}

                                        <a
                                            href="{{ route(
                                                'admin.proyek.pengeluaran.index',
                                                $p->id_pembangunan
                                            ) }}"
                                            class="btn btn-sm btn-dark"
                                            title="Pengeluaran"
                                        >

                                            <i class="fas fa-wallet"></i>

                                        </a>


                                        {{-- HAPUS --}}

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="deleteData({{ $p->id_pembangunan }})"
                                            title="Hapus"
                                        >

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="text-center py-5"
                                >

                                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                                    <h6 class="text-muted">

                                        Belum ada data proyek

                                    </h6>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- PAGINATION --}}

    <div class="d-flex justify-content-center mt-4">

        {{ $proyeks->links() }}

    </div>

</div>


{{-- =========================================================
     MODAL TAMBAH
========================================================= --}}

<div
    class="modal fade"
    id="tambahModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content rounded-4 border-0 shadow-lg">

            <form
                action="{{ route('proyek.store') }}"
                method="POST"
                id="tambahForm"
            >

                @csrf


                <div class="modal-header bg-primary text-white">

                    <div>

                        <h5 class="modal-title">

                            <i class="fas fa-plus-circle me-2"></i>

                            Tambah Proyek

                        </h5>

                        <small class="opacity-75">

                            Lengkapi informasi dan dimensi proyek.

                        </small>

                    </div>


                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body project-modal-body p-4">


                    {{-- INFORMASI DASAR --}}

                    <div class="section-title">

                        <i class="fas fa-info-circle"></i>

                        Informasi Proyek

                    </div>


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Nama Pembangunan
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="nama_pembangunan"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Lokasi
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="lokasi"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Tahun Anggaran

                            </label>

                            <input
                                type="number"
                                name="tahun_anggaran"
                                class="form-control"
                                value="{{ date('Y') }}"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Sumber Dana

                            </label>

                            <input
                                type="text"
                                name="sumber_dana"
                                class="form-control"
                                placeholder="Dana Desa / APBD / lainnya"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Anggaran

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    name="anggaran"
                                    class="form-control"
                                    min="0"
                                    required
                                >

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Tanggal Mulai

                            </label>

                            <input
                                type="date"
                                name="tanggal_mulai"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Tanggal Selesai

                            </label>

                            <input
                                type="date"
                                name="tanggal_selesai"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>


                    {{-- DIMENSI --}}

                    <div class="dimension-card mt-4">

                        <div class="dimension-card-header">

                            <div>

                                <strong>

                                    <i class="fas fa-ruler-combined me-2"></i>

                                    Dimensi Pekerjaan

                                </strong>

                                <small class="d-block">

                                    Masukkan ukuran proyek secara detail.

                                </small>

                            </div>

                            <span class="dimension-unit">
                                M / CM
                            </span>

                        </div>


                        <div class="p-3">

                            <div class="row g-3">


                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">

                                        Panjang
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            id="panjang_proyek"
                                            name="panjang"
                                            class="form-control"
                                            placeholder="0.00"
                                            required
                                        >

                                        <span class="input-group-text">
                                            m
                                        </span>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">

                                        Lebar
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            id="lebar_proyek"
                                            name="lebar"
                                            class="form-control"
                                            placeholder="0.00"
                                            required
                                        >

                                        <span class="input-group-text">
                                            m
                                        </span>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">

                                        Ketebalan / Tinggi
                                        <span class="text-danger">*</span>

                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            id="ketebalan_tinggi_proyek"
                                            name="ketebalan_tinggi"
                                            class="form-control"
                                            placeholder="Contoh: 10"
                                            required
                                        >

                                        <select
                                            name="satuan_ketebalan"
                                            id="satuan_ketebalan_proyek"
                                            class="form-select"
                                            style="max-width:90px;"
                                            aria-label="Satuan ketebalan"
                                        >
                                            <option value="m">M</option>
                                            <option value="cm" selected>CM</option>
                                        </select>

                                    </div>

                                    <small class="text-muted">
                                        Bisa menggunakan meter atau centimeter.
                                    </small>

                                </div>


                            </div>


                            {{-- HASIL VOLUME --}}

                            <div class="volume-result mt-3">

                                <div class="volume-icon">

                                    <i class="fas fa-cube"></i>

                                </div>


                                <div>

                                    <small class="text-muted d-block">

                                        Volume Otomatis

                                    </small>

                                    <div class="volume-number fw-bold">

                                        <span id="hasil_volume_proyek">
                                            0
                                        </span>

                                        m³

                                    </div>

                                    <small class="text-muted">

                                        Panjang × Lebar × Ketebalan/Tinggi • CM otomatis dikonversi ke meter

                                    </small>

                                </div>

                            </div>


                            <input
                                type="hidden"
                                name="satuan_volume"
                                value="m³"
                            >

                        </div>

                    </div>


                    {{-- PENAWARAN --}}

                    <div class="section-title mt-4">

                        <i class="fas fa-gavel"></i>

                        Pengaturan Penawaran

                    </div>


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Harga Penawaran Acuan

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Rp
                                </span>

                                <input
                                    type="number"
                                    name="harga_penawaran"
                                    class="form-control"
                                    min="0"
                                >

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Batas Penawaran

                            </label>

                            <input
                                type="date"
                                name="batas_penawaran"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Status Proyek

                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required
                            >

                                <option value="direncanakan">
                                    Direncanakan
                                </option>

                                <option value="berjalan">
                                    Berjalan
                                </option>

                                <option value="selesai">
                                    Selesai
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Bidang Pekerjaan

                            </label>

                            <input
                                type="text"
                                name="bidang_pekerjaan"
                                class="form-control"
                                placeholder="Jalan, jembatan, drainase, dll"
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">

                                Catatan

                            </label>

                            <textarea
                                name="catatan"
                                class="form-control"
                                rows="3"
                            ></textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-save me-1"></i>

                        Simpan Proyek

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL EDIT
========================================================= --}}

<div
    class="modal fade"
    id="editModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content rounded-4 border-0 shadow-lg">

            <form
                method="POST"
                id="editForm"
            >

                @csrf

                @method('PUT')


                <div class="modal-header bg-warning">

                    <div>

                        <h5 class="modal-title">

                            <i class="fas fa-edit me-2"></i>

                            Edit Proyek

                        </h5>

                        <small>
                            Perbarui informasi dan dimensi proyek.
                        </small>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body project-modal-body p-4">


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Nama Pembangunan

                            </label>

                            <input
                                type="text"
                                id="edit_nama"
                                name="nama_pembangunan"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Lokasi

                            </label>

                            <input
                                type="text"
                                id="edit_lokasi"
                                name="lokasi"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Tahun Anggaran

                            </label>

                            <input
                                type="number"
                                id="edit_tahun"
                                name="tahun_anggaran"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Sumber Dana

                            </label>

                            <input
                                type="text"
                                id="edit_sumber"
                                name="sumber_dana"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Anggaran

                            </label>

                            <input
                                type="number"
                                id="edit_anggaran"
                                name="anggaran"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Tanggal Mulai

                            </label>

                            <input
                                type="date"
                                id="edit_mulai"
                                name="tanggal_mulai"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Tanggal Selesai

                            </label>

                            <input
                                type="date"
                                id="edit_selesai"
                                name="tanggal_selesai"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>


                    {{-- DIMENSI EDIT --}}

                    <div class="dimension-card mt-4">

                        <div class="dimension-card-header">

                            <div>

                                <strong>

                                    <i class="fas fa-ruler-combined me-2"></i>

                                    Dimensi Pekerjaan

                                </strong>

                                <small class="d-block">

                                    Volume dihitung otomatis.

                                </small>

                            </div>

                            <span class="dimension-unit">
                                M / CM
                            </span>

                        </div>


                        <div class="p-3">

                            <div class="row g-3">


                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">

                                        Panjang

                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            id="edit_panjang"
                                            name="panjang"
                                            class="form-control"
                                            required
                                        >

                                        <span class="input-group-text">
                                            m
                                        </span>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">

                                        Lebar

                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            id="edit_lebar"
                                            name="lebar"
                                            class="form-control"
                                            required
                                        >

                                        <span class="input-group-text">
                                            m
                                        </span>

                                    </div>

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">

                                        Ketebalan / Tinggi

                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            id="edit_ketebalan_tinggi"
                                            name="ketebalan_tinggi"
                                            class="form-control"
                                            placeholder="Contoh: 0.10"
                                            required
                                        >

                                        <select
                                            name="satuan_ketebalan"
                                            id="edit_satuan_ketebalan"
                                            class="form-select"
                                            style="max-width:90px;"
                                            aria-label="Satuan ketebalan"
                                        >
                                            <option value="m">M</option>
                                            <option value="cm">CM</option>
                                        </select>

                                    </div>

                                    <small class="text-muted">
                                        Data lama dibuka dalam meter karena database menyimpan meter.
                                    </small>

                                </div>

                            </div>


                            <div class="volume-result mt-3">

                                <div class="volume-icon">

                                    <i class="fas fa-cube"></i>

                                </div>

                                <div>

                                    <small class="text-muted">

                                        Volume Otomatis

                                    </small>

                                    <div class="volume-number fw-bold">

                                        <span id="edit_hasil_volume">
                                            0
                                        </span>

                                        m³

                                    </div>

                                </div>

                            </div>


                            <input
                                type="hidden"
                                id="edit_satuan_volume"
                                name="satuan_volume"
                                value="m³"
                            >

                        </div>

                    </div>


                    {{-- DATA LAIN --}}

                    <div class="row g-3 mt-1">


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Harga Penawaran Acuan

                            </label>

                            <input
                                type="number"
                                id="edit_harga_penawaran"
                                name="harga_penawaran"
                                class="form-control"
                                min="0"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Batas Penawaran

                            </label>

                            <input
                                type="date"
                                id="edit_batas_penawaran"
                                name="batas_penawaran"
                                class="form-control"
                            >

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Status

                            </label>

                            <select
                                id="edit_status"
                                name="status"
                                class="form-select"
                                required
                            >

                                <option value="direncanakan">
                                    Direncanakan
                                </option>

                                <option value="berjalan">
                                    Berjalan
                                </option>

                                <option value="selesai">
                                    Selesai
                                </option>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Bidang Pekerjaan

                            </label>

                            <input
                                type="text"
                                id="edit_bidang"
                                name="bidang_pekerjaan"
                                class="form-control"
                            >

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">

                                Catatan

                            </label>

                            <textarea
                                id="edit_catatan"
                                name="catatan"
                                class="form-control"
                                rows="3"
                            ></textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-warning"
                    >

                        <i class="fas fa-save me-1"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL DETAIL
========================================================= --}}

<div
    class="modal fade"
    id="detailModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content rounded-4">

            <div class="modal-header bg-info text-white">

                <h5 class="modal-title">

                    <i class="fas fa-eye me-2"></i>

                    Detail Proyek

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div
                class="modal-body"
                id="detailContent"
            >

                <div class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                    <p class="mt-3 text-muted">

                        Memuat data...

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL PENAWARAN
========================================================= --}}

<div
    class="modal fade"
    id="penawaranModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content rounded-4">

            <div class="modal-header bg-primary text-white">

                <div>

                    <h5 class="modal-title">

                        <i class="fas fa-gavel me-2"></i>

                        Daftar Penawaran

                    </h5>

                    <small
                        id="penawaranProyekName"
                        class="text-white-50"
                    >

                        Proyek: -

                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div
                class="modal-body"
                id="penawaranContent"
            >

                <div class="text-center py-5">

                    <div class="spinner-border text-primary"></div>

                    <p class="mt-3 text-muted">

                        Memuat data penawaran...

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL DELETE
========================================================= --}}

<div
    class="modal fade"
    id="deleteModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content rounded-4">

            <form
                method="POST"
                id="deleteForm"
            >

                @csrf

                @method('DELETE')


                <div class="modal-header border-0">

                    <h5 class="modal-title text-danger">

                        <i class="fas fa-trash me-2"></i>

                        Hapus Proyek

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body text-center py-4">

                    <i
                        class="fas fa-triangle-exclamation fa-3x text-warning mb-3"
                    ></i>

                    <h6>

                        Yakin ingin menghapus proyek ini?

                    </h6>

                    <p class="text-muted mb-0">

                        Data proyek dan data terkait dapat ikut terhapus
                        sesuai konfigurasi database.

                    </p>

                </div>


                <div class="modal-footer border-0">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        <i class="fas fa-trash me-1"></i>

                        Ya, Hapus

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')

<script>

    const baseUrl = "{{ url('/') }}";

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') ||
        "{{ csrf_token() }}";


    // =========================================================
    // FORMAT ANGKA
    // =========================================================

    function formatNumber(value) {

        const number =
            Number(value) || 0;

        return number
            .toLocaleString(
                'id-ID',
                {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 2
                }
            );
    }


    // =========================================================
    // HITUNG VOLUME TAMBAH
    // =========================================================

    function hitungVolumeTambah() {

        const panjang =
            parseFloat(
                $('#panjang_proyek').val()
            ) || 0;

        const lebar =
            parseFloat(
                $('#lebar_proyek').val()
            ) || 0;

        let tinggi =
            parseFloat(
                $('#ketebalan_tinggi_proyek').val()
            ) || 0;

        const satuan =
            $('#satuan_ketebalan_proyek').val() || 'cm';

        // Ubah CM ke M sebelum menghitung volume.
        if (satuan === 'cm') {
            tinggi /= 100;
        }

        const volume =
            panjang *
            lebar *
            tinggi;


        $('#hasil_volume_proyek')
            .text(
                formatNumber(volume)
            );
    }


    // =========================================================
    // HITUNG VOLUME EDIT
    // =========================================================

    function hitungVolumeEdit() {

        const panjang =
            parseFloat(
                $('#edit_panjang').val()
            ) || 0;

        const lebar =
            parseFloat(
                $('#edit_lebar').val()
            ) || 0;

        let tinggi =
            parseFloat(
                $('#edit_ketebalan_tinggi').val()
            ) || 0;

        const satuan =
            $('#edit_satuan_ketebalan').val() || 'm';

        if (satuan === 'cm') {
            tinggi /= 100;
        }

        const volume =
            panjang *
            lebar *
            tinggi;


        $('#edit_hasil_volume')
            .text(
                formatNumber(volume)
            );
    }


    // =========================================================
    // EVENT VOLUME
    // =========================================================

    $(document).on(
        'input change',
        '#panjang_proyek, #lebar_proyek, #ketebalan_tinggi_proyek, #satuan_ketebalan_proyek',
        hitungVolumeTambah
    );


    $(document).on(
        'input change',
        '#edit_panjang, #edit_lebar, #edit_ketebalan_tinggi, #edit_satuan_ketebalan',
        hitungVolumeEdit
    );


    // =========================================================
    // EDIT DATA
    // =========================================================

    function editData(id, button = null) {

        if (button) {

            button.disabled = true;

            button.dataset.oldHtml =
                button.innerHTML;

            button.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i>';
        }


        $.ajax({

            url:
                baseUrl +
                '/proyek/' +
                id +
                '/get-data',

            type: 'GET',

            dataType: 'json',

            success: function(data) {

                $('#edit_nama')
                    .val(
                        data.nama_pembangunan || ''
                    );

                $('#edit_lokasi')
                    .val(
                        data.lokasi || ''
                    );

                $('#edit_tahun')
                    .val(
                        data.tahun_anggaran || ''
                    );

                $('#edit_sumber')
                    .val(
                        data.sumber_dana || ''
                    );

                $('#edit_mulai')
                    .val(
                        data.tanggal_mulai || ''
                    );

                $('#edit_selesai')
                    .val(
                        data.tanggal_selesai || ''
                    );

                $('#edit_anggaran')
                    .val(
                        data.anggaran || ''
                    );


                // DIMENSI

                $('#edit_panjang')
                    .val(
                        data.panjang ?? ''
                    );

                $('#edit_lebar')
                    .val(
                        data.lebar ?? ''
                    );

                $('#edit_satuan_ketebalan')
                    .val(
                        data.satuan_ketebalan || 'm'
                    );

                $('#edit_ketebalan_tinggi')
                    .val(
                        data.ketebalan_tinggi ?? ''
                    );


                $('#edit_harga_penawaran')
                    .val(
                        data.harga_penawaran ?? ''
                    );

                $('#edit_batas_penawaran')
                    .val(
                        data.batas_penawaran ?? ''
                    );

                $('#edit_status')
                    .val(
                        data.status || 'direncanakan'
                    );

                $('#edit_bidang')
                    .val(
                        data.bidang_pekerjaan || ''
                    );

                $('#edit_catatan')
                    .val(
                        data.catatan || ''
                    );


                $('#editForm')
                    .attr(
                        'action',
                        baseUrl +
                        '/proyek/' +
                        id
                    );


                hitungVolumeEdit();


                $('#editModal')
                    .modal('show');

            },

            error: function(xhr) {

                console.error(
                    xhr.responseText
                );

                alert(
                    'Data proyek gagal dimuat.'
                );

            },

            complete: function() {

                if (button) {

                    button.disabled =
                        false;

                    button.innerHTML =
                        button.dataset.oldHtml;
                }

            }

        });
    }


    // =========================================================
    // DETAIL
    // =========================================================

    function showDetail(id) {

        $('#detailContent').html(`

            <div class="text-center py-5">

                <div
                    class="spinner-border text-primary"
                ></div>

                <p class="mt-3 text-muted">

                    Memuat detail proyek...

                </p>

            </div>

        `);


        $('#detailModal')
            .modal('show');


        $.ajax({

            url:
                baseUrl +
                '/proyek/' +
                id,

            type: 'GET',

            dataType: 'json',

            success: function(data) {

                const volume =
                    data.volume_proyek !== null &&
                    data.volume_proyek !== undefined
                        ? formatNumber(
                            data.volume_proyek
                        ) + ' m³'
                        : '-';


                let dimensi =
                    '-';


                if (
                    data.panjang !== null &&
                    data.lebar !== null &&
                    data.ketebalan_tinggi !== null
                ) {

                    dimensi =

                        formatNumber(
                            data.panjang
                        ) +

                        ' × ' +

                        formatNumber(
                            data.lebar
                        ) +

                        ' × ' +

                        formatNumber(
                            data.ketebalan_tinggi
                        ) +

                        ' m';

                }


                $('#detailContent').html(`

                    <div class="row g-3">

                        <div class="col-md-8">

                            <div class="card border-0 bg-light">

                                <div class="card-body">

                                    <h5 class="fw-bold">

                                        ${escapeHtml(
                                            data.nama_pembangunan || '-'
                                        )}

                                    </h5>

                                    <p class="text-muted mb-3">

                                        <i class="fas fa-location-dot me-1 text-danger"></i>

                                        ${escapeHtml(
                                            data.lokasi || '-'
                                        )}

                                    </p>


                                    <div class="row g-3">

                                        <div class="col-md-6">

                                            <small class="text-muted">
                                                Tahun Anggaran
                                            </small>

                                            <div class="fw-bold">
                                                ${data.tahun_anggaran || '-'}
                                            </div>

                                        </div>


                                        <div class="col-md-6">

                                            <small class="text-muted">
                                                Sumber Dana
                                            </small>

                                            <div class="fw-bold">
                                                ${escapeHtml(
                                                    data.sumber_dana || '-'
                                                )}
                                            </div>

                                        </div>


                                        <div class="col-md-6">

                                            <small class="text-muted">
                                                Anggaran
                                            </small>

                                            <div class="fw-bold text-success">
                                                Rp ${Number(
                                                    data.anggaran || 0
                                                ).toLocaleString('id-ID')}
                                            </div>

                                        </div>


                                        <div class="col-md-6">

                                            <small class="text-muted">
                                                Volume
                                            </small>

                                            <div class="fw-bold text-primary">
                                                ${volume}
                                            </div>

                                        </div>


                                        <div class="col-12">

                                            <small class="text-muted">
                                                Dimensi
                                            </small>

                                            <div class="fw-bold text-primary">

                                                ${dimensi}

                                            </div>

                                            <small class="text-muted">

                                                Panjang × Lebar × Ketebalan/Tinggi (standar meter)

                                            </small>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="card border-0 bg-light h-100">

                                <div class="card-body">

                                    <h6 class="fw-bold">
                                        Informasi
                                    </h6>

                                    <hr>

                                    <p class="mb-2">

                                        <small class="text-muted">
                                            Mulai
                                        </small>

                                        <br>

                                        ${data.tanggal_mulai || '-'}

                                    </p>


                                    <p class="mb-2">

                                        <small class="text-muted">
                                            Selesai
                                        </small>

                                        <br>

                                        ${data.tanggal_selesai || '-'}

                                    </p>


                                    <p class="mb-2">

                                        <small class="text-muted">
                                            Bidang
                                        </small>

                                        <br>

                                        ${escapeHtml(
                                            data.bidang_pekerjaan || '-'
                                        )}

                                    </p>


                                    <p class="mb-0">

                                        <small class="text-muted">
                                            Catatan
                                        </small>

                                        <br>

                                        ${escapeHtml(
                                            data.catatan || '-'
                                        )}

                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                `);

            },

            error: function(xhr) {

                console.error(
                    xhr.responseText
                );

                $('#detailContent').html(`

                    <div class="alert alert-danger">

                        Gagal memuat detail proyek.

                    </div>

                `);

            }

        });

    }


    // =========================================================
    // PENAWARAN - FIX VERSION
    // =========================================================

    function showPenawaran(id) {

        $('#penawaranModal')
            .modal('show');


        $('#penawaranContent').html(`

            <div class="text-center py-5">

                <div class="spinner-border text-primary"></div>

                <p class="mt-3 text-muted">

                    Memuat data penawaran...

                </p>

            </div>

        `);


        $.ajax({

            url:
                baseUrl +
                '/proyek/' +
                id +
                '/penawaran-data',

            type: 'GET',

            dataType: 'json',

            success: function(data) {

                const proyek =
                    data.proyek;

                const penawaran =
                    Array.isArray(
                        data.penawaran
                    )
                        ? data.penawaran
                        : [];


                $('#penawaranProyekName')
                    .text(
                        'Proyek: ' +
                        (
                            proyek.nama_pembangunan
                            || '-'
                        )
                    );


                if (!penawaran.length) {

                    $('#penawaranContent').html(`

                        <div class="text-center py-5">

                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>

                            <h6 class="text-muted">

                                Belum ada penawaran

                            </h6>

                        </div>

                    `);

                    return;
                }


                let html = `

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>No</th>

                                    <th>Rekanan</th>

                                    <th>Telepon</th>

                                    <th>Bidang</th>

                                    <th>CV</th>

                                    <th>Harga Penawaran</th>

                                    <th>Status</th>

                                    <th>Tanggal</th>

                                    <th>Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                `;


                penawaran.forEach(
                    function(item, index) {

                        const rekanan =
                            item.rekanan ||
                            {};


                        let statusBadge =
                            '';


                        if (
                            item.status_penawaran ===
                            'diterima'
                        ) {

                            statusBadge =
                                '<span class="badge bg-success">Disetujui</span>';

                        }
                        else if (
                            item.status_penawaran ===
                            'dipertimbangkan'
                        ) {

                            statusBadge =
                                '<span class="badge bg-warning text-dark">Dipertimbangkan</span>';

                        }
                        else if (
                            item.status_penawaran ===
                            'ditolak'
                        ) {

                            statusBadge =
                                '<span class="badge bg-danger">Ditolak</span>';

                        }
                        else {

                            statusBadge =
                                '<span class="badge bg-info">Menunggu</span>';

                        }


                        // =====================================================
                        // CV REKANAN - FIX VERSION
                        // =====================================================

                        let cvHtml =
                            '<span class="text-muted">Belum ada CV</span>';

                        // PERTAMA: cek cv_url dari controller
                        if (rekanan.cv_url) {

                            cvHtml = `
                                <a
                                    href="${rekanan.cv_url}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Lihat CV Rekanan"
                                >
                                    <i class="fas fa-file-pdf me-1"></i>
                                    Lihat CV
                                </a>
                            `;

                        }
                        // KEDUA: fallback jika cv_url tidak ada
                        else if (rekanan.cv) {

                            const filename = String(rekanan.cv).split('/').pop();

                            const cvUrl =
                                `${baseUrl}/uploads/cv_rekanan/${encodeURIComponent(filename)}`;

                            cvHtml = `
                                <a
                                    href="${cvUrl}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Lihat CV Rekanan"
                                >
                                    <i class="fas fa-file-pdf me-1"></i>
                                    Lihat CV
                                </a>
                            `;

                        }


                        let actionHtml =
                            '';


                        if (
                            proyek.status_penawaran ===
                            'open'
                        ) {

                            const baseAction =

                                `${baseUrl}/proyek/${proyek.id_pembangunan}/penawaran/${item.id_rekanan_proyek}`;


                            actionHtml = `

                                <div class="d-flex flex-wrap gap-1">

                                    <form
                                        method="POST"
                                        action="${baseAction}/setujui"
                                    >

                                        <input
                                            type="hidden"
                                            name="_token"
                                            value="${csrfToken}"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Setujui penawaran ini?')"
                                        >

                                            <i class="fas fa-check"></i>

                                        </button>

                                    </form>


                                    <form
                                        method="POST"
                                        action="${baseAction}/pertimbangkan"
                                    >

                                        <input
                                            type="hidden"
                                            name="_token"
                                            value="${csrfToken}"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-warning"
                                            onclick="return confirm('Pertimbangkan penawaran ini?')"
                                        >

                                            <i class="fas fa-clock"></i>

                                        </button>

                                    </form>


                                    <form
                                        method="POST"
                                        action="${baseAction}/tolak"
                                    >

                                        <input
                                            type="hidden"
                                            name="_token"
                                            value="${csrfToken}"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tolak penawaran ini?')"
                                        >

                                            <i class="fas fa-times"></i>

                                        </button>

                                    </form>

                                </div>

                            `;

                        }


                        html += `

                            <tr>

                                <td>
                                    ${index + 1}
                                </td>


                                <td>

                                    <strong>

                                        ${escapeHtml(
                                            rekanan.nama_user ||
                                            'Rekanan'
                                        )}

                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        ${escapeHtml(
                                            rekanan.email ||
                                            '-'
                                        )}

                                    </small>

                                </td>


                                <td>

                                    ${escapeHtml(
                                        rekanan.no_telp ||
                                        '-'
                                    )}

                                </td>


                                <td>

                                    ${escapeHtml(
                                        rekanan.bidang_keahlian ||
                                        '-'
                                    )}

                                </td>


                                <td>

                                    ${cvHtml}

                                </td>


                                <td>

                                    <strong>

                                        Rp
                                        ${Number(
                                            item.harga_penawaran_rekanan ||
                                            0
                                        ).toLocaleString('id-ID')}

                                    </strong>

                                </td>


                                <td>

                                    ${statusBadge}

                                </td>


                                <td>

                                    ${
                                        item.created_at
                                            ? new Date(
                                                item.created_at
                                            ).toLocaleDateString(
                                                'id-ID'
                                            )
                                            : '-'
                                    }

                                </td>


                                <td>

                                    ${actionHtml}

                                </td>

                            </tr>

                        `;

                    }
                );


                html += `

                            </tbody>

                        </table>

                    </div>

                `;


                $('#penawaranContent')
                    .html(html);

            },


            error: function(xhr) {

                console.error(
                    xhr.responseText
                );

                $('#penawaranContent').html(`

                    <div class="alert alert-danger">

                        Gagal memuat data penawaran.

                    </div>

                `);

            }

        });

    }


    // =========================================================
    // DELETE
    // =========================================================

    function deleteData(id) {

        $('#deleteForm')
            .attr(
                'action',
                baseUrl +
                '/proyek/' +
                id
            );


        $('#deleteModal')
            .modal('show');
    }


    // =========================================================
    // ESCAPE HTML
    // =========================================================

    function escapeHtml(value) {

        return String(
            value ?? ''
        )
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );
    }


    // =========================================================
    // RESET MODAL TAMBAH
    // =========================================================

    $('#tambahModal').on(
        'hidden.bs.modal',
        function() {

            const form =
                document.getElementById(
                    'tambahForm'
                );

            if (form) {
                form.reset();
            }

            $('#hasil_volume_proyek')
                .text('0');

            $('#satuan_ketebalan_proyek')
                .val('cm');
        }
    );


    // =========================================================
    // VOLUME EDIT SAAT MODAL DIBUKA
    // =========================================================

    $('#editModal').on(
        'shown.bs.modal',
        function() {

            hitungVolumeEdit();

        }
    );

</script>

@endpush


@push('styles')

<style>

    /* =========================================================
       TABLE
    ========================================================== */

    #dataTable td {

        vertical-align: middle;

    }


    /* =========================================================
       DIMENSION CARD
    ========================================================== */

    .dimension-card {

        border:
            1px solid #dbe5f1;

        border-radius:
            15px;

        background:
            #f8fbff;

        overflow:
            hidden;

    }


    .dimension-card-header {

        display:
            flex;

        justify-content:
            space-between;

        align-items:
            center;

        padding:
            15px 18px;

        color:
            #fff;

        background:
            linear-gradient(
                135deg,
                #1e3c72 0%,
                #2a5298 100%
            );

    }


    .dimension-card-header small {

        opacity:
            .85;

    }


    .dimension-unit {

        background:
            #fff;

        color:
            #1e3c72;

        padding:
            5px 12px;

        border-radius:
            50px;

        font-size:
            12px;

        font-weight:
            700;

    }


    /* =========================================================
       VOLUME
    ========================================================== */

    .volume-result {

        display:
            flex;

        align-items:
            center;

        gap:
            14px;

        padding:
            16px;

        background:
            #fff;

        border:
            1px solid #dbe5f1;

        border-radius:
            12px;

    }


    .volume-icon {

        width:
            48px;

        height:
            48px;

        min-width:
            48px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        border-radius:
            12px;

        background:
            #eaf2ff;

        color:
            #1e3c72;

        font-size:
            20px;

    }


    .volume-number {

        font-size:
            1.5rem;

        line-height:
            1.2;

        color:
            #1e3c72;

    }


    /* =========================================================
       SECTION TITLE
    ========================================================== */

    .section-title {

        display:
            flex;

        align-items:
            center;

        gap:
            8px;

        font-weight:
            700;

        color:
            #1e3c72;

        margin-bottom:
            14px;

    }


    /* =========================================================
       MODAL SCROLL FIX
    ========================================================== */

    #tambahModal .modal-dialog,
    #editModal .modal-dialog {

        max-width:
            1100px;

        height:
            calc(100vh - 2rem);

        margin:
            1rem auto;

    }


    #tambahModal .modal-content,
    #editModal .modal-content {

        height:
            100%;

        max-height:
            100%;

        display:
            flex;

        flex-direction:
            column;

        overflow:
            hidden;

        border-radius:
            18px;

    }


    #tambahModal form,
    #editModal form {

        min-height:
            0;

        flex:
            1 1 auto;

        display:
            flex;

        flex-direction:
            column;

    }


    #tambahModal .project-modal-body,
    #editModal .project-modal-body {

        flex:
            1 1 auto;

        min-height:
            0;

        overflow-y:
            auto;

        overflow-x:
            hidden;

        -webkit-overflow-scrolling:
            touch;

        overscroll-behavior:
            contain;

    }


    #tambahModal .modal-header,
    #editModal .modal-header {

        flex:
            0 0 auto;

    }


    #tambahModal .modal-footer,
    #editModal .modal-footer {

        flex:
            0 0 auto;

        background:
            #fff;

        border-top:
            1px solid #edf0f3;

    }


    /* =========================================================
       SCROLLBAR
    ========================================================== */

    #tambahModal .project-modal-body::-webkit-scrollbar,
    #editModal .project-modal-body::-webkit-scrollbar {

        width:
            7px;

    }


    #tambahModal .project-modal-body::-webkit-scrollbar-track,
    #editModal .project-modal-body::-webkit-scrollbar-track {

        background:
            #f1f3f5;

        border-radius:
            10px;

    }


    #tambahModal .project-modal-body::-webkit-scrollbar-thumb,
    #editModal .project-modal-body::-webkit-scrollbar-thumb {

        background:
            #adb5bd;

        border-radius:
            10px;

    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 768px) {

        #tambahModal .modal-dialog,
        #editModal .modal-dialog {

            width:
                calc(100% - 1rem);

            max-width:
                none;

            height:
                calc(100vh - 1rem);

            margin:
                .5rem auto;

        }


        #tambahModal .project-modal-body,
        #editModal .project-modal-body {

            padding:
                1rem !important;

        }


        .dimension-card-header {

            align-items:
                flex-start;

            gap:
                10px;

        }

    }

</style>

@endpush

@endsection