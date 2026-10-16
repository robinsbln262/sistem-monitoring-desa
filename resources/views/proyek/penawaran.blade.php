@extends('layouts.app')

@section('content')

<div class="table-wrapper">

{{-- =========================
    HEADER
========================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0">
            <i class="fas fa-list me-2 text-primary"></i>
            Daftar Penawaran - {{ $proyek->nama_pembangunan }}
        </h5>
        <small class="text-muted">
            Lokasi: {{ $proyek->lokasi }}
        </small>
    </div>

    <div>
        <span class="badge bg-primary px-3 py-2">
            <i class="fas fa-gavel me-1"></i>
            Status: {{ ucfirst($proyek->status_penawaran) }}
        </span>

        <a href="{{ route('proyek.index') }}"
           class="btn btn-secondary btn-sm ms-2">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>
</div>


{{-- =========================
    INFO PROYEK
========================== --}}
<div class="row mb-4">

    {{-- Harga Proyek --}}
    <div class="col-md-4">
        <div class="card bg-light border-0">
            <div class="card-body">
                <small class="text-muted">
                    Harga Penawaran Proyek
                </small>

                <h5 class="text-success">
                    Rp {{ number_format($proyek->harga_penawaran ?? 0, 0, ',', '.') }}
                </h5>
            </div>
        </div>
    </div>

    {{-- Total Penawaran --}}
    <div class="col-md-4">
        <div class="card bg-light border-0">
            <div class="card-body">
                <small class="text-muted">
                    Total Penawaran Masuk
                </small>

                <h5>
                    {{ $penawaran->count() }}
                </h5>
            </div>
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <div class="card bg-light border-0">
            <div class="card-body">
                <small class="text-muted">
                    Status
                </small>

                <h5>
                    @if($proyek->status_penawaran == 'open')

                        <span class="badge bg-success">
                            Terbuka
                        </span>

                    @elseif($proyek->status_penawaran == 'review')

                        <span class="badge bg-warning text-dark">
                            Review
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Tertutup
                        </span>

                    @endif
                </h5>
            </div>
        </div>
    </div>

</div>


{{-- =========================
    TABEL PENAWARAN
========================== --}}
<div class="table-responsive">

    <table class="table table-hover align-middle" id="dataTable">

        <thead class="table-light">
            <tr>
                <th>No</th>

                <th>Rekanan</th>

                {{-- KREDIBILITAS --}}
                <th>Kredibilitas</th>

                <th>Bidang Keahlian</th>

                <th>Harga Penawaran</th>

                <th>Status</th>

                <th>Catatan</th>

                <th>Tanggal</th>

                <th>Aksi</th>
            </tr>
        </thead>


        <tbody>

            @forelse($penawaran as $index => $p)

                @php
                    /*
                     * Ambil data rekanan dengan aman.
                     * Fallback ke null jika relasi tidak tersedia.
                     */
                    $rekanan = $p->rekanan ?? null;

                    $skorKredibilitas = $rekanan->skor_kredibilitas ?? 0;

                    $predikatKredibilitas =
                        $rekanan->predikat_kredibilitas
                        ?? 'Belum Ada Data';

                    $rataRating =
                        $rekanan->rata_rating ?? 0;

                    $persentaseTepatWaktu =
                        $rekanan->persentase_tepat_waktu ?? 0;

                    /*
                     * Tentukan warna badge kredibilitas.
                     */
                    if ($skorKredibilitas >= 70) {
                        $kredibilitasClass = 'bg-success';
                    } elseif ($skorKredibilitas >= 55) {
                        $kredibilitasClass = 'bg-warning text-dark';
                    } else {
                        $kredibilitasClass = 'bg-danger';
                    }
                @endphp

                <tr>

                    {{-- NO --}}
                    <td>
                        {{ $loop->iteration }}
                    </td>


                    {{-- =========================
                        REKANAN
                    ========================== --}}
                    <td>
                        @if($rekanan)

                            <strong>
                                {{ $rekanan->nama_user }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $rekanan->no_telp }}
                            </small>

                        @else

                            <span class="text-muted">
                                Data rekanan tidak tersedia
                            </span>

                        @endif
                    </td>


                    {{-- =========================
                        KREDIBILITAS
                    ========================== --}}
                    <td>

                        <div class="d-flex align-items-center">

                            <span class="badge {{ $kredibilitasClass }} me-2">

                                {{ number_format((float) $skorKredibilitas, 1) }}

                            </span>

                            <small>
                                {{ $predikatKredibilitas }}
                            </small>

                        </div>

                        <small class="text-muted">

                            <span>
                                ⭐
                                {{ number_format((float) $rataRating, 1) }}
                            </span>

                            <span class="mx-1">|</span>

                            <span>
                                ⏱
                                {{ number_format((float) $persentaseTepatWaktu, 0) }}%
                                tepat waktu
                            </span>

                        </small>

                    </td>


                    {{-- =========================
                        BIDANG KEAHLIAN
                    ========================== --}}
                    <td>

                        @if($rekanan)

                            <span class="badge bg-info text-dark">
                                {{ $rekanan->bidang_keahlian ?: 'Umum' }}
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Umum
                            </span>

                        @endif

                    </td>


                    {{-- =========================
                        HARGA PENAWARAN
                    ========================== --}}
                    <td>

                        <strong class="text-primary">
                            Rp
                            {{ number_format($p->harga_penawaran_rekanan ?? 0, 0, ',', '.') }}
                        </strong>


                        {{-- Perbandingan harga --}}
                        @if($proyek->harga_penawaran && $p->harga_penawaran_rekanan)

                            @php
                                $selisih =
                                    $proyek->harga_penawaran
                                    - $p->harga_penawaran_rekanan;

                                $persen =
                                    ($selisih / $proyek->harga_penawaran)
                                    * 100;
                            @endphp


                            @if($selisih > 0)

                                <span class="badge bg-success ms-1">
                                    -{{ number_format($persen, 1) }}%
                                </span>

                            @elseif($selisih < 0)

                                <span class="badge bg-danger ms-1">
                                    +{{ number_format(abs($persen), 1) }}%
                                </span>

                            @else

                                <span class="badge bg-secondary ms-1">
                                    Sama
                                </span>

                            @endif

                        @endif

                    </td>


                    {{-- =========================
                        STATUS
                    ========================== --}}
                    <td>

                        @if($p->status_penawaran == 'pending')

                            <span class="badge bg-warning text-dark">
                                Menunggu
                            </span>

                        @elseif($p->status_penawaran == 'diterima')

                            <span class="badge bg-success">
                                Diterima ✅
                            </span>

                        @elseif($p->status_penawaran == 'ditolak')

                            <span class="badge bg-danger">
                                Ditolak ❌
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ ucfirst($p->status_penawaran ?? '-') }}
                            </span>

                        @endif

                    </td>


                    {{-- =========================
                        CATATAN
                    ========================== --}}
                    <td>

                        {{ Str::limit($p->catatan, 30) ?: '-' }}

                    </td>


                    {{-- =========================
                        TANGGAL
                    ========================== --}}
                    <td>

                        {{ $p->created_at
                            ? $p->created_at->format('d/m/Y H:i')
                            : '-' }}

                    </td>


                    {{-- =========================
                        AKSI
                    ========================== --}}
                    <td>

                        @if(
                            $proyek->status_penawaran == 'open'
                            && $p->status_penawaran == 'pending'
                        )

                            <form
                                action="{{ route(
                                    'proyek.setujui-penawaran',
                                    [
                                        'proyekId' => $proyek->id_pembangunan,
                                        'rekananProyekId' => $p->id_rekanan_proyek
                                    ]
                                ) }}"
                                method="POST"
                                class="d-inline"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-success"
                                    onclick="return confirm('Setujui penawaran dari {{ $rekanan->nama_user ?? 'rekanan ini' }}?')"
                                >

                                    <i class="fas fa-check me-1"></i>
                                    Setujui

                                </button>

                            </form>

                        @endif

                    </td>

                </tr>

            @empty

                {{-- =========================
                    DATA KOSONG
                ========================== --}}
                <tr>

                    <td colspan="9" class="text-center py-5">

                        <div class="text-muted">

                            <i
                                class="fas fa-inbox fa-3x mb-3 opacity-50"
                            ></i>

                            <h6>
                                Belum Ada Penawaran
                            </h6>

                            <small>
                                Belum ada rekanan yang mengirimkan
                                penawaran untuk proyek ini.
                            </small>

                        </div>

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

</div>
@endsection
