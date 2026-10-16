@extends('layouts.app')

@section('title', 'Pengeluaran Proyek')

@section('page_title', 'Pengeluaran Proyek')

@section(
    'page_subtitle',
    'Kelola rincian penggunaan anggaran proyek'
)

@section('content')

<div class="container-fluid px-0">

    {{-- TOMBOL KEMBALI KE DAFTAR PROYEK --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="fw-bold mb-1">
                <i class="fas fa-money-bill-wave text-success me-2"></i>
                Pengeluaran Proyek
            </h5>

            <small class="text-muted">
                Kelola rincian penggunaan anggaran proyek
            </small>
        </div>

        <a
            href="{{ route('proyek.index') }}"
            class="btn btn-outline-primary rounded-3 px-3"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Kembali ke Proyek
        </a>

    </div>

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-circle-exclamation me-2"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Terdapat kesalahan:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- INFORMASI PROYEK --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <div class="text-muted small mb-1">
                        PROYEK
                        #{{ $proyek->id_pembangunan }}
                    </div>

                    <h4 class="fw-bold mb-2">

                        <i class="fas fa-building text-primary me-2"></i>

                        {{ $proyek->nama_pembangunan }}

                    </h4>

                    <div class="text-muted">

                        <i class="fas fa-location-dot me-1"></i>

                        {{ $proyek->lokasi }}

                    </div>

                </div>

                <div>

                    @if($proyek->status === 'direncanakan')

                        <span class="badge bg-secondary px-3 py-2">
                            Direncanakan
                        </span>

                    @elseif($proyek->status === 'berjalan')

                        <span class="badge bg-warning text-dark px-3 py-2">
                            Berjalan
                        </span>

                    @else

                        <span class="badge bg-success px-3 py-2">
                            Selesai
                        </span>

                    @endif

                </div>

            </div>

            <hr>

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="small text-muted">
                        Nilai Anggaran
                    </div>

                    <div class="fs-5 fw-bold">

                        Rp
                        {{ number_format(
                            $proyek->anggaran,
                            0,
                            ',',
                            '.'
                        ) }}

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="small text-muted">
                        Total Pengeluaran
                    </div>

                    <div class="fs-5 fw-bold text-danger">

                        Rp
                        {{ number_format(
                            $totalPengeluaran,
                            0,
                            ',',
                            '.'
                        ) }}

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="small text-muted">
                        Sisa Anggaran
                    </div>

                    <div class="fs-5 fw-bold text-success">

                        Rp
                        {{ number_format(
                            $sisaAnggaran,
                            0,
                            ',',
                            '.'
                        ) }}

                    </div>

                </div>

            </div>

            <div class="mt-4">

                <div class="d-flex justify-content-between mb-1">

                    <small>
                        Penggunaan Anggaran
                    </small>

                    <small class="fw-bold">
                        {{ number_format(
                            min($persentase, 100),
                            1
                        ) }}%
                    </small>

                </div>

                <div
                    class="progress"
                    style="height: 10px;"
                >

                    <div
                        class="progress-bar
                        @if($persentase >= 90)
                            bg-danger
                        @elseif($persentase >= 70)
                            bg-warning
                        @else
                            bg-success
                        @endif"
                        style="width: {{ min($persentase, 100) }}%"
                    ></div>

                </div>

            </div>

        </div>

    </div>


    {{-- TOMBOL TAMBAH --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h5 class="fw-bold mb-1">
                Rincian Pengeluaran
            </h5>

            <small class="text-muted">
                Catat seluruh penggunaan anggaran proyek.
            </small>

        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalTambah"
        >
            <i class="fas fa-plus me-1"></i>
            Tambah Pengeluaran
        </button>

    </div>


    {{-- TABEL --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                No
                            </th>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Uraian
                            </th>

                            <th>
                                Volume
                            </th>

                            <th>
                                Harga Satuan
                            </th>

                            <th>
                                Jumlah
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th class="text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse(
                            $pengeluarans
                            as $pengeluaran
                        )

                            <tr>

                                <td class="px-3">
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    @switch(
                                        $pengeluaran->kategori
                                    )

                                        @case(
                                            'upah_tenaga_kerja'
                                        )

                                            <span
                                                class="badge bg-primary"
                                            >
                                                Upah Tenaga Kerja
                                            </span>

                                            @break

                                        @case('material')

                                            <span
                                                class="badge bg-success"
                                            >
                                                Material
                                            </span>

                                            @break

                                        @case('sewa_alat')

                                            <span
                                                class="badge bg-warning text-dark"
                                            >
                                                Sewa Alat
                                            </span>

                                            @break

                                        @case('transportasi')

                                            <span
                                                class="badge bg-info"
                                            >
                                                Transportasi
                                            </span>

                                            @break

                                        @case('administrasi')

                                            <span
                                                class="badge bg-secondary"
                                            >
                                                Administrasi
                                            </span>

                                            @break

                                        @default

                                            <span
                                                class="badge bg-dark"
                                            >
                                                Lainnya
                                            </span>

                                    @endswitch

                                </td>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $pengeluaran->uraian }}
                                    </div>

                                    @if(
                                        $pengeluaran->keterangan
                                    )

                                        <small class="text-muted">
                                            {{ $pengeluaran->keterangan }}
                                        </small>

                                    @endif

                                </td>

                                <td>

                                    {{ rtrim(rtrim(number_format((float) $pengeluaran->volume, 2, ',', '.'), '0'), ',') }}

                                    {{ $pengeluaran->satuan }}

                                </td>

                                <td>

                                    Rp
                                    {{ number_format(
                                        $pengeluaran->harga_satuan,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td class="fw-bold">

                                    Rp
                                    {{ number_format(
                                        $pengeluaran->jumlah,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                                <td>

                                    {{ optional(
                                        $pengeluaran
                                            ->tanggal_pengeluaran
                                    )->format('d/m/Y') }}

                                </td>

                                <td>

                                    <div
                                        class="d-flex justify-content-center gap-1"
                                    >

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $pengeluaran->id_pengeluaran }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.proyek.pengeluaran.destroy',
                                                [
                                                    $proyek->id_pembangunan,
                                                    $pengeluaran->id_pengeluaran
                                                ]
                                            ) }}"
                                            onsubmit="return confirm('Hapus pengeluaran ini?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="fas fa-receipt fa-3x text-muted mb-3"
                                    ></i>

                                    <h6 class="fw-bold">
                                        Belum Ada Pengeluaran
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Belum ada rincian penggunaan
                                        anggaran untuk proyek ini.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    @if($pengeluarans->count())

                        <tfoot class="table-light">

                            <tr>

                                <th
                                    colspan="5"
                                    class="text-end"
                                >
                                    TOTAL PENGELUARAN
                                </th>

                                <th class="text-danger">

                                    Rp
                                    {{ number_format(
                                        $totalPengeluaran,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </th>

                                <th colspan="2"></th>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- MODAL TAMBAH --}}
{{-- ================================================= --}}

<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <form
                method="POST"
                action="{{ route(
                    'admin.proyek.pengeluaran.store',
                    $proyek->id_pembangunan
                ) }}"
            >

                @csrf

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title fw-bold">
                            Tambah Pengeluaran
                        </h5>

                        <small class="text-muted">
                            {{ $proyek->nama_pembangunan }}
                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Kategori
                            </label>

                            <select
                                name="kategori"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Pilih Kategori --
                                </option>

                                @foreach(
                                    $kategori
                                    as $key => $label
                                )

                                    <option value="{{ $key }}">
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Tanggal Pengeluaran
                            </label>

                            <input
                                type="date"
                                name="tanggal_pengeluaran"
                                class="form-control"
                                value="{{ date('Y-m-d') }}"
                                required
                            >

                        </div>

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Uraian Pengeluaran
                            </label>

                            <input
                                type="text"
                                name="uraian"
                                class="form-control"
                                placeholder="Contoh: Pembelian semen"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Volume
                            </label>

                            <input
                                type="number"
                                name="volume"
                                id="tambah_volume"
                                class="form-control hitung-jumlah"
                                step="0.01"
                                min="0"
                                value="1"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Satuan
                            </label>

                            <input
                                type="text"
                                name="satuan"
                                class="form-control"
                                placeholder="sak / m³ / hari / orang"
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Harga Satuan
                            </label>

                            <input
                                type="number"
                                name="harga_satuan"
                                id="tambah_harga"
                                class="form-control hitung-jumlah"
                                min="0"
                                step="0.01"
                                required
                            >

                        </div>

                        <div class="col-12">

                            <div class="alert alert-primary mb-0">

                                <div class="small">
                                    Total Pengeluaran
                                </div>

                                <div
                                    id="tambah_total"
                                    class="fs-4 fw-bold"
                                >
                                    Rp 0
                                </div>

                                <small>
                                    Volume × Harga Satuan
                                </small>

                            </div>

                        </div>

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Keterangan
                            </label>

                            <textarea
                                name="keterangan"
                                class="form-control"
                                rows="3"
                                placeholder="Keterangan tambahan..."
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
                        Simpan Pengeluaran
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ================================================= --}}
{{-- MODAL EDIT --}}
{{-- ================================================= --}}

@foreach($pengeluarans as $pengeluaran)

<div
    class="modal fade"
    id="modalEdit{{ $pengeluaran->id_pengeluaran }}"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <form
                method="POST"
                action="{{ route(
                    'admin.proyek.pengeluaran.update',
                    [
                        $proyek->id_pembangunan,
                        $pengeluaran->id_pengeluaran
                    ]
                ) }}"
            >

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title fw-bold">
                        Edit Pengeluaran
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Kategori
                            </label>

                            <select
                                name="kategori"
                                class="form-select"
                                required
                            >

                                @foreach(
                                    $kategori
                                    as $key => $label
                                )

                                    <option
                                        value="{{ $key }}"
                                        @selected(
                                            $pengeluaran->kategori
                                            === $key
                                        )
                                    >
                                        {{ $label }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Tanggal Pengeluaran
                            </label>

                            <input
                                type="date"
                                name="tanggal_pengeluaran"
                                class="form-control"
                                value="{{
                                    optional(
                                        $pengeluaran
                                            ->tanggal_pengeluaran
                                    )->format('Y-m-d')
                                }}"
                                required
                            >

                        </div>

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Uraian Pengeluaran
                            </label>

                            <input
                                type="text"
                                name="uraian"
                                class="form-control"
                                value="{{ $pengeluaran->uraian }}"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Volume
                            </label>

                            <input
                                type="number"
                                name="volume"
                                class="form-control edit-volume-{{ $pengeluaran->id_pengeluaran }}"
                                value="{{ rtrim(rtrim(number_format((float) $pengeluaran->volume, 2, '.', ''), '0'), '.') }}"
                                step="0.01"
                                min="0"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Satuan
                            </label>

                            <input
                                type="text"
                                name="satuan"
                                class="form-control"
                                value="{{ $pengeluaran->satuan }}"
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Harga Satuan
                            </label>

                            <input
                                type="number"
                                name="harga_satuan"
                                class="form-control edit-harga-{{ $pengeluaran->id_pengeluaran }}"
                                value="{{ $pengeluaran->harga_satuan }}"
                                min="0"
                                step="0.01"
                                required
                            >

                        </div>

                        <div class="col-12">

                            <div class="alert alert-primary mb-0">

                                <div class="small">
                                    Total Pengeluaran
                                </div>

                                <div class="fs-4 fw-bold">

                                    Rp
                                    <span
                                        id="edit-total-{{ $pengeluaran->id_pengeluaran }}"
                                    >
                                        {{ number_format(
                                            $pengeluaran->jumlah,
                                            0,
                                            ',',
                                            '.'
                                        ) }}
                                    </span>

                                </div>

                            </div>

                        </div>

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Keterangan
                            </label>

                            <textarea
                                name="keterangan"
                                class="form-control"
                                rows="3"
                            >{{ $pengeluaran->keterangan }}</textarea>

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
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        function rupiah(angka) {

            return new Intl.NumberFormat(
                'id-ID'
            ).format(angka || 0);

        }

        const volumeTambah =
            document.getElementById(
                'tambah_volume'
            );

        const hargaTambah =
            document.getElementById(
                'tambah_harga'
            );

        const totalTambah =
            document.getElementById(
                'tambah_total'
            );

        function hitungTambah() {

            const volume =
                parseFloat(
                    volumeTambah.value
                ) || 0;

            const harga =
                parseFloat(
                    hargaTambah.value
                ) || 0;

            totalTambah.textContent =
                'Rp ' +
                rupiah(volume * harga);
        }

        volumeTambah.addEventListener(
            'input',
            hitungTambah
        );

        hargaTambah.addEventListener(
            'input',
            hitungTambah
        );


        @foreach($pengeluarans as $pengeluaran)

            const editVolume{{ $pengeluaran->id_pengeluaran }} =
                document.querySelector(
                    '.edit-volume-{{ $pengeluaran->id_pengeluaran }}'
                );

            const editHarga{{ $pengeluaran->id_pengeluaran }} =
                document.querySelector(
                    '.edit-harga-{{ $pengeluaran->id_pengeluaran }}'
                );

            const editTotal{{ $pengeluaran->id_pengeluaran }} =
                document.getElementById(
                    'edit-total-{{ $pengeluaran->id_pengeluaran }}'
                );

            function hitungEdit{{ $pengeluaran->id_pengeluaran }}() {

                const volume =
                    parseFloat(
                        editVolume{{ $pengeluaran->id_pengeluaran }}.value
                    ) || 0;

                const harga =
                    parseFloat(
                        editHarga{{ $pengeluaran->id_pengeluaran }}.value
                    ) || 0;

                editTotal{{ $pengeluaran->id_pengeluaran }}.textContent =
                    rupiah(volume * harga);
            }

            editVolume{{ $pengeluaran->id_pengeluaran }}
                .addEventListener(
                    'input',
                    hitungEdit{{ $pengeluaran->id_pengeluaran }}
                );

            editHarga{{ $pengeluaran->id_pengeluaran }}
                .addEventListener(
                    'input',
                    hitungEdit{{ $pengeluaran->id_pengeluaran }}
                );

        @endforeach

    }
);

</script>

@endsection