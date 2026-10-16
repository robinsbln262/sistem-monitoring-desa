@extends('layouts.app')

@section('content')

<div class="table-wrapper">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h5 class="mb-0">
            <i class="fas fa-building me-2 text-primary"></i>
            Semua Proyek
        </h5>

        <span class="badge bg-primary rounded-pill px-3 py-2">

            <i class="fas fa-file me-1"></i>

            Total: {{ $proyeks->total() }}

        </span>

    </div>


    {{-- =========================================================
        STATISTIK
    ========================================================== --}}
    <div class="row mb-4">

        <div class="col-md-3 mb-2">

            <div class="card bg-primary text-white border-0 rounded-3">

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-white-50">
                                TOTAL PROYEK
                            </small>

                            <h4 class="mb-0">
                                {{ $proyeks->total() }}
                            </h4>

                        </div>

                        <i class="fas fa-building fa-2x opacity-50"></i>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-2">

            <div class="card bg-secondary text-white border-0 rounded-3">

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-white-50">
                                DIRENCANAKAN
                            </small>

                            <h4 class="mb-0">
                                {{ $proyeks->where('status', 'direncanakan')->count() }}
                            </h4>

                        </div>

                        <i class="fas fa-clock fa-2x opacity-50"></i>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-2">

            <div class="card bg-warning text-white border-0 rounded-3">

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-white-50">
                                SEDANG BERJALAN
                            </small>

                            <h4 class="mb-0">
                                {{ $proyeks->where('status', 'berjalan')->count() }}
                            </h4>

                        </div>

                        <i class="fas fa-spinner fa-2x opacity-50"></i>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-2">

            <div class="card bg-success text-white border-0 rounded-3">

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-white-50">
                                SUDAH SELESAI
                            </small>

                            <h4 class="mb-0">
                                {{ $proyeks->where('status', 'selesai')->count() }}
                            </h4>

                        </div>

                        <i class="fas fa-check-circle fa-2x opacity-50"></i>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3 mb-2">

            <div class="card bg-info text-white border-0 rounded-3">

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-white-50">
                                DIPUBLIKASI
                            </small>

                            <h4 class="mb-0">
                                {{ $proyeks->where('is_published', true)->count() }}
                            </h4>

                        </div>

                        <i class="fas fa-globe fa-2x opacity-50"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TABEL
    ========================================================== --}}
    <div class="table-responsive">

        <table
            class="table table-hover"
            id="dataTable"
        >

            <thead class="table-light">

                <tr>

                    <th width="5%">
                        No
                    </th>

                    <th>
                        Nama Proyek
                    </th>

                    <th>
                        Lokasi
                    </th>

                    <th>
                        Anggaran
                    </th>

                    <th>
                        Ukuran Proyek
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Rekanan
                    </th>

                    <th>
                        Penawaran
                    </th>

                    <th>
                        Progres
                    </th>

                    <th width="10%">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach ($proyeks as $index => $p)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | CEK DIMENSI
                        |--------------------------------------------------------------------------
                        */

                        $hasDimension =
                            $p->panjang !== null &&
                            $p->lebar !== null &&
                            $p->ketebalan_tinggi !== null;


                        /*
                        |--------------------------------------------------------------------------
                        | VOLUME
                        |--------------------------------------------------------------------------
                        */

                        $volume = $p->volume_proyek;


                        /*
                        |--------------------------------------------------------------------------
                        | FALLBACK DATA BARU
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $volume === null &&
                            $hasDimension
                        ) {

                            $volume =
                                (float) $p->panjang *
                                (float) $p->lebar *
                                (float) $p->ketebalan_tinggi;

                        }

                    @endphp


                    <tr>

                        {{-- NO --}}
                        <td>
                            {{ $loop->iteration }}
                        </td>


                        {{-- NAMA --}}
                        <td class="fw-bold">

                            {{ $p->nama_pembangunan }}

                        </td>


                        {{-- LOKASI --}}
                        <td>

                            {{ $p->lokasi }}

                        </td>


                        {{-- ANGGARAN --}}
                        <td>

                            Rp
                            {{ number_format(
                                $p->anggaran,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        {{-- =================================================
                            UKURAN PROYEK
                        ================================================== --}}
                        <td>

                            @if($hasDimension)

                                <div class="project-size-mini">

                                    <div class="size-row">

                                        <span>
                                            Panjang
                                        </span>

                                        <strong>

                                            {{ rtrim(rtrim(
                                                number_format(
                                                    (float) $p->panjang,
                                                    2,
                                                    ',',
                                                    '.'
                                                ),
                                                '0'
                                            ), ',') }}

                                            m

                                        </strong>

                                    </div>


                                    <div class="size-row">

                                        <span>
                                            Lebar
                                        </span>

                                        <strong>

                                            {{ rtrim(rtrim(
                                                number_format(
                                                    (float) $p->lebar,
                                                    2,
                                                    ',',
                                                    '.'
                                                ),
                                                '0'
                                            ), ',') }}

                                            m

                                        </strong>

                                    </div>


                                    <div class="size-row">

                                        <span>
                                            Ketebalan/Tinggi
                                        </span>

                                        <strong>

                                            {{ rtrim(rtrim(
                                                number_format(
                                                    (float) $p->ketebalan_tinggi,
                                                    2,
                                                    ',',
                                                    '.'
                                                ),
                                                '0'
                                            ), ',') }}

                                            m

                                        </strong>

                                    </div>


                                    <div class="volume-row">

                                        <span>
                                            Volume
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


                            @elseif($p->volume_proyek !== null)

                                {{-- FALLBACK DATA LAMA --}}

                                <div class="old-volume">

                                    <i class="fas fa-ruler-combined me-1"></i>

                                    {{ rtrim(rtrim(
                                        number_format(
                                            (float) $p->volume_proyek,
                                            2,
                                            ',',
                                            '.'
                                        ),
                                        '0'
                                    ), ',') }}

                                    {{ $p->satuan_volume }}

                                </div>


                            @else

                                <span class="text-muted">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if ($p->status == 'direncanakan')

                                <span class="badge bg-secondary">

                                    Direncanakan

                                </span>

                            @elseif($p->status == 'berjalan')

                                <span class="badge bg-warning text-dark">

                                    Berjalan

                                </span>

                            @else

                                <span class="badge bg-success">

                                    Selesai

                                </span>

                            @endif

                        </td>


                        {{-- REKANAN --}}
                        <td>

                            @if (
                                $p->rekananSetuju &&
                                $p->rekananSetuju->count() > 0
                            )

                                @foreach ($p->rekananSetuju as $rekanan)

                                    <span class="badge bg-primary rounded-pill">

                                        <i class="fas fa-user-check me-1"></i>

                                        {{ $rekanan->nama_user }}

                                    </span>

                                    <br>

                                    <small class="text-muted">

                                        {{
                                            $rekanan->bidang_keahlian
                                                ?: 'Umum'
                                        }}

                                    </small>

                                @endforeach

                            @else

                                <span class="text-muted">

                                    Belum ada rekanan

                                </span>

                            @endif

                        </td>


                        {{-- PENAWARAN --}}
                        <td>

                            @php

                                $jumlahPenawaran =
                                    $p->rekananProyek
                                        ? $p->rekananProyek->count()
                                        : 0;


                                $jumlahAktif =
                                    $p->rekananProyek
                                        ? $p->rekananProyek
                                            ->whereIn(
                                                'status_penawaran',
                                                [
                                                    'pending',
                                                    'dipertimbangkan'
                                                ]
                                            )
                                            ->count()
                                        : 0;

                            @endphp


                            @if($jumlahPenawaran > 0)

                                <span class="badge bg-info">

                                    {{ $jumlahPenawaran }}
                                    Penawaran

                                </span>


                                @if($jumlahAktif > 0)

                                    <br>

                                    <small class="text-muted">

                                        {{ $jumlahAktif }}
                                        masih dipertimbangkan

                                    </small>

                                @endif

                            @else

                                <span class="text-muted">
                                    Belum ada
                                </span>

                            @endif

                        </td>


                        {{-- PROGRES --}}
                        <td>

                            <div class="d-flex align-items-center">

                                <div
                                    class="progress flex-grow-1 me-2"
                                    style="height: 6px;"
                                >

                                    <div
                                        class="progress-bar {{
                                            $p->progress == 100
                                                ? 'bg-success'
                                                : 'bg-primary'
                                        }}"
                                        style="width: {{ $p->progress }}%"
                                    ></div>

                                </div>


                                <span class="small">

                                    {{ $p->progress }}%

                                </span>

                            </div>

                        </td>


                        {{-- AKSI --}}
                        <td>

                            <button
                                class="btn btn-sm btn-info btn-action"
                                onclick="showDetail({{ $p->id_pembangunan }})"
                                title="Detail"
                            >

                                <i class="fas fa-eye"></i>

                            </button>


                            <a
                                href="{{ route(
                                    'laporan.cetak-proyek',
                                    $p->id_pembangunan
                                ) }}"
                                class="btn btn-sm btn-danger btn-action"
                                title="Cetak PDF"
                            >

                                <i class="fas fa-file-pdf"></i>

                            </a>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>


    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">

        {{ $proyeks->links() }}

    </div>

</div>


{{-- =========================================================
    MODAL DETAIL
========================================================= --}}
<div
    class="modal fade"
    id="detailModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content rounded-4">


            {{-- HEADER --}}
            <div
                class="modal-header border-0"
                style="
                    background:
                        linear-gradient(
                            135deg,
                            #1e3c72 0%,
                            #2a5298 100%
                        );
                "
            >

                <h5 class="modal-title text-white">

                    <i class="fas fa-info-circle me-2"></i>

                    Detail Proyek

                </h5>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            {{-- BODY --}}
            <div
                class="modal-body p-4"
                id="detailContent"
            >

                <div class="text-center py-4">

                    <div
                        class="spinner-border text-primary"
                        role="status"
                    >

                        <span class="visually-hidden">
                            Loading...
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


@endsection


@push('styles')

<style>

/* =========================================================
   PROJECT SIZE TABLE
========================================================= */

.project-size-mini {

    min-width: 145px;

    padding: 7px 9px;

    border-radius: 9px;

    background: #f8fbff;

    border: 1px solid #dce8f7;

}


.size-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 8px;

    padding: 3px 0;

    font-size: 10px;

    border-bottom: 1px dashed #e5eaf1;

}


.size-row:last-of-type {

    border-bottom: 0;

}


.size-row span {

    color: #7b8794;

}


.size-row strong {

    color: #1e3c72;

    white-space: nowrap;

}


.volume-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-top: 5px;

    padding-top: 6px;

    border-top: 1px solid #dce8f7;

    font-size: 10px;

}


.volume-row span {

    color: #64748b;

    font-weight: 600;

}


.volume-row strong {

    color: #198754;

    font-size: 11px;

}


.old-volume {

    white-space: nowrap;

    font-size: 11px;

}


/* =========================================================
   DETAIL MODAL DIMENSION
========================================================= */

.detail-dimension-box {

    padding: 14px;

    margin-top: 15px;

    border-radius: 13px;

    background: #f8fbff;

    border: 1px solid #dce8f7;

}


.detail-dimension-title {

    display: flex;

    align-items: center;

    gap: 8px;

    margin-bottom: 10px;

    color: #1e3c72;

    font-size: 13px;

    font-weight: 700;

}


.detail-dimension-item {

    padding: 10px;

    border-radius: 10px;

    background: #fff;

    border: 1px solid #e6ebf2;

    text-align: center;

}


.detail-dimension-item small {

    display: block;

    color: #8a94a6;

    font-size: 9px;

    margin-bottom: 3px;

}


.detail-dimension-item strong {

    display: block;

    color: #1e3c72;

    font-size: 14px;

}


.detail-dimension-item span {

    color: #64748b;

    font-size: 10px;

}


.detail-volume {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-top: 10px;

    padding: 11px 13px;

    border-radius: 9px;

    background: #fff;

    border: 1px solid #dce8f7;

}


.detail-volume span {

    color: #64748b;

    font-size: 10px;

}


.detail-volume strong {

    color: #198754;

    font-size: 16px;

}


/* =========================================================
   MODAL SCROLL
========================================================= */

#detailModal .modal-dialog {

    max-height: calc(100vh - 30px);

}


#detailModal .modal-content {

    max-height: calc(100vh - 30px);

}


#detailModal .modal-body {

    overflow-y: auto;

}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .project-size-mini {

        min-width: 125px;

    }


    .size-row {

        font-size: 9px;

    }

}

</style>

@endpush


@push('scripts')

<script>

var baseUrl = '{{ url('/') }}';


/* =========================================================
   DATATABLE
========================================================= */

$(document).ready(function() {

    $('#dataTable').DataTable({

        language: {

            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"

        },

        pageLength: 10,

        responsive: true

    });

});


/* =========================================================
   SHOW DETAIL
========================================================= */

function showDetail(id) {

    $('#detailContent').html(`

        <div class="text-center py-5">

            <div
                class="spinner-border text-primary"
                style="
                    width: 3rem;
                    height: 3rem;
                "
                role="status"
            >

                <span class="visually-hidden">
                    Loading...
                </span>

            </div>

            <p class="mt-3 text-muted">
                Memuat data detail proyek...
            </p>

        </div>

    `);


    $('#detailModal').modal('show');


    $.ajax({

        url:
            baseUrl +
            '/kades/proyek/' +
            id,

        type: 'GET',

        dataType: 'json',

        timeout: 15000,


        success: function(data) {


            /* =================================================
               STATUS
            ================================================= */

            let statusBadge = '';


            if(data.status == 'direncanakan') {

                statusBadge = `

                    <span class="badge bg-secondary px-3 py-2">

                        <i class="fas fa-clock me-1"></i>

                        Direncanakan

                    </span>

                `;

            }

            else if(data.status == 'berjalan') {

                statusBadge = `

                    <span class="badge bg-warning text-dark px-3 py-2">

                        <i class="fas fa-spinner fa-pulse me-1"></i>

                        Berjalan

                    </span>

                `;

            }

            else {

                statusBadge = `

                    <span class="badge bg-success px-3 py-2">

                        <i class="fas fa-check-circle me-1"></i>

                        Selesai

                    </span>

                `;

            }


            /* =================================================
               REKANAN
            ================================================= */

            let rekananHtml = '-';


            if(
                data.rekanan_setuju &&
                data.rekanan_setuju.length > 0
            ) {

                rekananHtml =
                    data.rekanan_setuju
                        .map(r => `

                            <span class="badge bg-primary rounded-pill">

                                <i class="fas fa-user-check me-1"></i>

                                ${escapeHtml(r.nama_user)}

                            </span>

                        `)
                        .join(' ');

            }


            /* =================================================
               PENAWARAN
            ================================================= */

            let penawaranHtml = `

                <div class="text-muted">

                    Belum ada penawaran rekanan.

                </div>

            `;


            if(
                data.rekanan_proyek &&
                data.rekanan_proyek.length > 0
            ) {

                penawaranHtml =
                    data.rekanan_proyek
                        .map(item => {

                            const nama =
                                item.rekanan
                                    ? item.rekanan.nama_user
                                    : 'Rekanan';


                            const harga =
                                item.harga_penawaran_rekanan
                                    ? 'Rp ' +
                                        new Intl.NumberFormat(
                                            'id-ID'
                                        ).format(
                                            item.harga_penawaran_rekanan
                                        )
                                    : '-';


                            let badge = `

                                <span class="badge bg-warning text-dark">

                                    Menunggu

                                </span>

                            `;


                            if(
                                item.status_penawaran ===
                                'diterima'
                            ) {

                                badge = `

                                    <span class="badge bg-success">

                                        Disetujui

                                    </span>

                                `;

                            }

                            else if(
                                item.status_penawaran ===
                                'dipertimbangkan'
                            ) {

                                badge = `

                                    <span class="badge bg-info text-dark">

                                        Dipertimbangkan

                                    </span>

                                `;

                            }

                            else if(
                                item.status_penawaran ===
                                'ditolak'
                            ) {

                                badge = `

                                    <span class="badge bg-danger">

                                        Ditolak

                                    </span>

                                `;

                            }


                            let actionHtml = '';


                            const canAct =
                                data.status_penawaran !==
                                    'closed' &&
                                item.status_penawaran !==
                                    'ditolak' &&
                                item.status_penawaran !==
                                    'diterima';


                            if(canAct) {

                                const baseAction =
                                    baseUrl +
                                    '/kades/proyek/' +
                                    data.id_pembangunan +
                                    '/penawaran/' +
                                    item.id_rekanan_proyek;


                                actionHtml = `

                                    <div
                                        class="
                                            d-flex
                                            flex-wrap
                                            gap-2
                                            mt-3
                                            pt-3
                                            border-top
                                        "
                                    >

                                        <form
                                            method="POST"
                                            action="${baseAction}/setujui"
                                            class="d-inline"
                                            onsubmit="
                                                return confirm(
                                                    'Setujui penawaran dari ' +
                                                    escapeHtml(nama) +
                                                    '?'
                                                )
                                            "
                                        >

                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="${
                                                    document
                                                        .querySelector(
                                                            'meta[name=csrf-token]'
                                                        )
                                                        ?.getAttribute(
                                                            'content'
                                                        ) || ''
                                                }"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                            >

                                                <i class="fas fa-check me-1"></i>

                                                Setujui

                                            </button>

                                        </form>


                                        <form
                                            method="POST"
                                            action="${baseAction}/pertimbangkan"
                                            class="d-inline"
                                        >

                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="${
                                                    document
                                                        .querySelector(
                                                            'meta[name=csrf-token]'
                                                        )
                                                        ?.getAttribute(
                                                            'content'
                                                        ) || ''
                                                }"
                                            >

                                            <button
                                                type="submit"
                                                class="
                                                    btn
                                                    btn-sm
                                                    btn-warning
                                                    text-dark
                                                "
                                            >

                                                <i class="fas fa-clock me-1"></i>

                                                Dipertimbangkan

                                            </button>

                                        </form>


                                        <form
                                            method="POST"
                                            action="${baseAction}/tolak"
                                            class="d-inline"
                                            onsubmit="
                                                return confirm(
                                                    'Tolak penawaran dari ' +
                                                    escapeHtml(nama) +
                                                    '?'
                                                )
                                            "
                                        >

                                            <input
                                                type="hidden"
                                                name="_token"
                                                value="${
                                                    document
                                                        .querySelector(
                                                            'meta[name=csrf-token]'
                                                        )
                                                        ?.getAttribute(
                                                            'content'
                                                        ) || ''
                                                }"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                            >

                                                <i class="fas fa-times me-1"></i>

                                                Ditolak

                                            </button>

                                        </form>

                                    </div>

                                `;

                            }


                            return `

                                <div class="border rounded-3 p-3 mb-2">

                                    <div
                                        class="
                                            d-flex
                                            justify-content-between
                                            align-items-start
                                            gap-2
                                        "
                                    >

                                        <div>

                                            <div class="fw-bold">

                                                ${escapeHtml(nama)}

                                            </div>

                                            <small class="text-muted">

                                                ${harga}

                                            </small>

                                        </div>


                                        <div>

                                            ${badge}

                                        </div>

                                    </div>


                                    ${
                                        item.catatan
                                            ? `
                                                <div
                                                    class="
                                                        small
                                                        text-muted
                                                        mt-2
                                                    "
                                                >

                                                    <i class="
                                                        fas
                                                        fa-comment
                                                        me-1
                                                    "></i>

                                                    ${escapeHtml(
                                                        item.catatan
                                                    )}

                                                </div>
                                            `
                                            : ''
                                    }


                                    ${actionHtml}

                                </div>

                            `;

                        })
                        .join('');

            }


            /* =================================================
               HITUNG VOLUME
            ================================================= */

            const hasDimension =
                data.panjang !== null &&
                data.panjang !== undefined &&
                data.lebar !== null &&
                data.lebar !== undefined &&
                data.ketebalan_tinggi !== null &&
                data.ketebalan_tinggi !== undefined;


            let volume = data.volume_proyek;


            if(
                (
                    volume === null ||
                    volume === undefined
                ) &&
                hasDimension
            ) {

                volume =
                    Number(data.panjang) *
                    Number(data.lebar) *
                    Number(data.ketebalan_tinggi);

            }


            /* =================================================
               FORMAT ANGKA
            ================================================= */

            const formatNumber = value => {

                if(
                    value === null ||
                    value === undefined ||
                    value === ''
                ) {

                    return '-';

                }


                return new Intl.NumberFormat(
                    'id-ID',
                    {
                        maximumFractionDigits: 2
                    }
                ).format(value);

            };


            /* =================================================
               DETAIL UKURAN
            ================================================= */

            let ukuranHtml = '';


            if(hasDimension) {

                ukuranHtml = `

                    <div class="detail-dimension-box">

                        <div class="detail-dimension-title">

                            <i class="fas fa-ruler-combined"></i>

                            Detail Ukuran Proyek

                        </div>


                        <div class="row g-2">


                            <div class="col-md-4">

                                <div class="detail-dimension-item">

                                    <small>
                                        Panjang
                                    </small>

                                    <strong>

                                        ${formatNumber(
                                            data.panjang
                                        )}

                                        <span>m</span>

                                    </strong>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="detail-dimension-item">

                                    <small>
                                        Lebar
                                    </small>

                                    <strong>

                                        ${formatNumber(
                                            data.lebar
                                        )}

                                        <span>m</span>

                                    </strong>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="detail-dimension-item">

                                    <small>
                                        Ketebalan / Tinggi
                                    </small>

                                    <strong>

                                        ${formatNumber(
                                            data.ketebalan_tinggi
                                        )}

                                        <span>m</span>

                                    </strong>

                                </div>

                            </div>

                        </div>


                        <div class="detail-volume">

                            <span>

                                <i class="fas fa-cube me-1"></i>

                                Volume Pekerjaan

                            </span>


                            <strong>

                                ${formatNumber(volume)}
                                m³

                            </strong>

                        </div>


                        <div
                            class="
                                text-muted
                                small
                                mt-2
                            "
                        >

                            <i class="fas fa-calculator me-1"></i>

                            Volume = Panjang × Lebar ×
                            Ketebalan/Tinggi

                        </div>

                    </div>

                `;

            }

            else if(
                data.volume_proyek !== null &&
                data.volume_proyek !== undefined
            ) {

                ukuranHtml = `

                    <div class="alert alert-light border mt-3">

                        <i class="fas fa-ruler-combined me-2 text-primary"></i>

                        <strong>
                            Volume:
                        </strong>

                        ${formatNumber(
                            data.volume_proyek
                        )}

                        ${escapeHtml(
                            data.satuan_volume || ''
                        )}

                    </div>

                `;

            }


            /* =================================================
               HTML DETAIL
            ================================================= */

            let html = `

                <div class="row">


                    {{-- INFORMASI --}}
                    <div class="col-md-7">

                        <div class="card shadow-sm border-0 mb-3">

                            <div class="card-header bg-light">

                                <strong>

                                    <i
                                        class="
                                            fas
                                            fa-info-circle
                                            text-primary
                                            me-2
                                        "
                                    ></i>

                                    Informasi Proyek

                                </strong>

                            </div>


                            <div class="card-body">

                                <table
                                    class="
                                        table
                                        table-borderless
                                        mb-0
                                    "
                                >

                                    <tr>

                                        <td
                                            width="35%"
                                            class="text-muted"
                                        >
                                            Nama Proyek
                                        </td>

                                        <td class="fw-bold">

                                            ${escapeHtml(
                                                data.nama_pembangunan
                                            )}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Lokasi
                                        </td>

                                        <td>

                                            ${escapeHtml(
                                                data.lokasi
                                            )}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Tahun Anggaran
                                        </td>

                                        <td>
                                            ${data.tahun_anggaran ?? '-'}
                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Sumber Dana
                                        </td>

                                        <td>

                                            ${escapeHtml(
                                                data.sumber_dana
                                            )}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Anggaran
                                        </td>

                                        <td
                                            class="
                                                fw-bold
                                                text-success
                                            "
                                        >

                                            Rp

                                            ${
                                                new Intl.NumberFormat(
                                                    'id-ID'
                                                ).format(
                                                    data.anggaran || 0
                                                )
                                            }

                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Tanggal Pelaksanaan
                                        </td>

                                        <td>

                                            ${formatDate(
                                                data.tanggal_mulai
                                            )}

                                            -

                                            ${formatDate(
                                                data.tanggal_selesai
                                            )}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Status
                                        </td>

                                        <td>

                                            ${statusBadge}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Rekanan
                                        </td>

                                        <td>

                                            ${rekananHtml}

                                        </td>

                                    </tr>


                                    <tr>

                                        <td class="text-muted">
                                            Dibuat Oleh
                                        </td>

                                        <td>

                                            ${
                                                data.user
                                                    ? escapeHtml(
                                                        data.user.nama_user
                                                    )
                                                    : '-'
                                            }

                                        </td>

                                    </tr>

                                </table>


                                ${ukuranHtml}

                            </div>

                        </div>

                    </div>


                    {{-- PENAWARAN + PROGRESS --}}
                    <div class="col-md-5">


                        <div class="card shadow-sm border-0 mb-3">

                            <div class="card-header bg-light">

                                <strong>

                                    <i
                                        class="
                                            fas
                                            fa-gavel
                                            text-primary
                                            me-2
                                        "
                                    ></i>

                                    Penawaran Rekanan

                                </strong>

                            </div>


                            <div class="card-body">

                                ${
                                    data.status_penawaran ===
                                    'closed'

                                        ? `

                                            <div
                                                class="
                                                    alert
                                                    alert-secondary
                                                    py-2
                                                    small
                                                "
                                            >

                                                <i
                                                    class="
                                                        fas
                                                        fa-lock
                                                        me-1
                                                    "
                                                ></i>

                                                Penawaran proyek
                                                sudah ditutup.

                                            </div>

                                        `

                                        : ''
                                }


                                ${penawaranHtml}

                            </div>

                        </div>


                        <div class="card shadow-sm border-0 mb-3">

                            <div class="card-header bg-light">

                                <strong>

                                    <i
                                        class="
                                            fas
                                            fa-chart-line
                                            text-primary
                                            me-2
                                        "
                                    ></i>

                                    Progres Proyek

                                </strong>

                            </div>


                            <div class="card-body">

                                <small class="text-muted">

                                    Progres Saat Ini

                                </small>


                                <div
                                    class="
                                        d-flex
                                        align-items-center
                                        gap-2
                                    "
                                >

                                    <div
                                        class="
                                            progress
                                            flex-grow-1
                                        "
                                        style="height:10px;"
                                    >

                                        <div
                                            class="
                                                progress-bar
                                                ${
                                                    data.progress == 100
                                                        ? 'bg-success'
                                                        : 'bg-primary'
                                                }
                                            "
                                            style="
                                                width:
                                                ${data.progress || 0}%;
                                            "
                                        ></div>

                                    </div>


                                    <span class="fw-bold">

                                        ${data.progress || 0}%

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            `;


            $('#detailContent').html(html);

        },


        error: function(xhr, status, error) {

            console.error('Error:', error);

            console.error(
                'Response:',
                xhr.responseText
            );


            $('#detailContent').html(`

                <div class="text-center py-5">

                    <i
                        class="
                            fas
                            fa-exclamation-triangle
                            fa-4x
                            text-warning
                            mb-3
                        "
                    ></i>


                    <h5 class="text-danger">

                        Gagal Memuat Data

                    </h5>


                    <p class="text-muted">

                        Gagal memuat data detail.
                        Silakan coba lagi.

                    </p>


                    <button
                        class="
                            btn
                            btn-primary
                            mt-2
                            rounded-3
                        "
                        onclick="showDetail(${id})"
                    >

                        <i
                            class="
                                fas
                                fa-sync-alt
                                me-2
                            "
                        ></i>

                        Coba Lagi

                    </button>

                </div>

            `);

        }

    });

}


/* =========================================================
   FORMAT DATE
========================================================= */

function formatDate(dateString) {

    if(!dateString) {

        return '-';

    }


    const date =
        new Date(dateString);


    if(
        isNaN(
            date.getTime()
        )
    ) {

        return dateString;

    }


    return date.toLocaleDateString(
        'id-ID',
        {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }
    );

}


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(text) {

    if(
        text === null ||
        text === undefined
    ) {

        return '';

    }


    const div =
        document.createElement('div');


    div.textContent =
        String(text);


    return div.innerHTML;

}

</script>

@endpush