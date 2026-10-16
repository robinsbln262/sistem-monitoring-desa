@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @php
        $backRoute = Auth::user()->level === 'rekanan'
            ? route('evaluasi-rekanan.index')
            : route('admin.evaluasi-rekanan');

        $status = $evaluasi->status_verifikasi;

        // Warna & ikon status banner
        $bannerClass = 'alert-warning';
        $bannerIcon  = 'fa-clock';
        if ($status === 'approved') {
            $bannerClass = 'alert-success';
            $bannerIcon  = 'fa-check-circle';
        } elseif ($status === 'rejected') {
            $bannerClass = 'alert-danger';
            $bannerIcon  = 'fa-times-circle';
        }

        // Label status
        $statusLabel = match ($status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Menunggu Verifikasi',
        };

        $rating = (int) ($evaluasi->rating ?? 0);
    @endphp

    {{-- ============================================= --}}
    {{-- HEADER --}}
    {{-- ============================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-clipboard-check text-primary me-2"></i>Detail Evaluasi
            </h4>
            <p class="text-muted mb-0">{{ $evaluasi->proyek->nama_pembangunan ?? '-' }}</p>
        </div>
        <a href="{{ $backRoute }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- STATUS BANNER --}}
    {{-- ============================================= --}}
    <div class="alert {{ $bannerClass }} d-flex align-items-center">
        <i class="fas {{ $bannerIcon }} fa-2x me-3"></i>
        <div>
            <strong>Status: {{ $statusLabel }}</strong>
            @if($evaluasi->tanggal_verifikasi)
                <div class="small">
                    Diverifikasi oleh <strong>{{ $evaluasi->verifikator->nama_user ?? '-' }}</strong>
                    pada {{ $evaluasi->tanggal_verifikasi->format('d/m/Y H:i') }}
                </div>
            @endif
            @if($evaluasi->catatan_verifikasi)
                <div class="mt-2">
                    <strong>Catatan:</strong> {{ $evaluasi->catatan_verifikasi }}
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- ============================================= --}}
        {{-- KOLOM KIRI --}}
        {{-- ============================================= --}}
        <div class="col-lg-8">
            {{-- Info Proyek --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-building text-primary me-2"></i>Informasi Proyek
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nama Proyek</small>
                            <strong>{{ $evaluasi->proyek->nama_pembangunan ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Lokasi</small>
                            <strong>{{ $evaluasi->proyek->lokasi ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tahun Anggaran</small>
                            <strong>{{ $evaluasi->proyek->tahun_anggaran ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Anggaran</small>
                            <strong>Rp {{ number_format($evaluasi->proyek->anggaran ?? 0, 0, ',', '.') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Mulai</small>
                            <strong>{{ $evaluasi->proyek->tanggal_mulai?->format('d/m/Y') ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Tanggal Selesai (Rencana)</small>
                            <strong>{{ $evaluasi->proyek->tanggal_selesai?->format('d/m/Y') ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hasil Evaluasi --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-clipboard-list text-primary me-2"></i>Hasil Evaluasi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small d-block mb-2">KENDALA YANG DIHADAPI</label>
                        <div class="p-3 bg-light rounded-3">
                            {!! nl2br(e($evaluasi->kendala)) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small d-block mb-2">HASIL AKHIR PEKERJAAN</label>
                        <div class="p-3 bg-light rounded-3">
                            {!! nl2br(e($evaluasi->hasil_akhir)) !!}
                        </div>
                    </div>

                    @if($evaluasi->rekomendasi_rekanan)
                        <div class="mb-0">
                            <label class="text-muted small d-block mb-2">REKOMENDASI</label>
                            <div class="p-3 bg-light rounded-3">
                                {!! nl2br(e($evaluasi->rekomendasi_rekanan)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- KOLOM KANAN --}}
        {{-- ============================================= --}}
        <div class="col-lg-4">
            {{-- Ringkasan Penilaian --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-star text-warning me-2"></i>Penilaian
                    </h6>
                </div>
                <div class="card-body">
                    {{-- Rating Bintang Besar --}}
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="32" height="32"
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
                                         width="32" height="32"
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
                        <div class="h4 fw-bold text-primary">{{ $rating }}/5</div>
                        <small class="text-muted">Rating Kinerja</small>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Ketepatan Waktu</small>
                        {!! $evaluasi->tepat_waktu_badge !!}
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Kualitas Pekerjaan</small>
                        {!! $evaluasi->kualitas_badge !!}
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Penyelesaian</small>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1 me-2" style="height:10px;">
                                <div class="progress-bar bg-success"
                                     style="width: {{ $evaluasi->persentase_penyelesaian }}%"></div>
                            </div>
                            <strong>{{ $evaluasi->persentase_penyelesaian }}%</strong>
                        </div>
                    </div>

                    <div class="mb-0">
                        <small class="text-muted d-block mb-1">Tanggal Evaluasi</small>
                        <strong>{{ $evaluasi->tanggal_evaluasi->format('d F Y') }}</strong>
                    </div>
                </div>
            </div>

            {{-- Info Rekanan --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user-tie text-primary me-2"></i>Rekanan Pelaksana
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar me-3" style="width:50px;height:50px;font-size:18px;">
                            {{ strtoupper(substr($evaluasi->rekanan->nama_user ?? 'NA', 0, 2)) }}
                        </div>
                        <div>
                            <strong class="d-block">{{ $evaluasi->rekanan->nama_user ?? '-' }}</strong>
                            <small class="text-muted">{{ $evaluasi->rekanan->bidang_keahlian ?? '-' }}</small>
                        </div>
                    </div>

                    @if($evaluasi->rekanan)
                        @php $rekanan = $evaluasi->rekanan; @endphp

                        <div class="border-top pt-3">
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3">
                                        <div class="h5 mb-0 text-primary fw-bold">
                                            {{ format_angka($rekanan->skor_kredibilitas) }}
                                        </div>
                                        <small class="text-muted" style="font-size:10px;">SKOR KREDIBILITAS</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3">
                                        <div class="h5 mb-0 text-warning fw-bold">
                                            {{ format_angka($rekanan->rata_rating) }}
                                        </div>
                                        <small class="text-muted" style="font-size:10px;">RATA RATING</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3">
                                        <div class="h5 mb-0 text-success fw-bold">
                                            {{ $rekanan->total_tepat_waktu }}
                                        </div>
                                        <small class="text-muted" style="font-size:10px;">TEPAT WAKTU</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3">
                                        <div class="h5 mb-0 text-info fw-bold">
                                            {{ $rekanan->total_proyek_dievaluasi }}
                                        </div>
                                        <small class="text-muted" style="font-size:10px;">TOTAL EVALUASI</small>
                                    </div>
                                </div>
                            </div>

                            @if(in_array(Auth::user()->level, ['admin', 'kepala_desa']))
                                <a href="{{ route('admin.evaluasi-rekanan.kinerja', $rekanan->id_user) }}"
                                   class="btn btn-sm btn-outline-primary w-100 mt-3">
                                    <i class="fas fa-chart-line me-1"></i>Lihat Kinerja Lengkap
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- TOMBOL VERIFIKASI (Admin/Kades) --}}
    {{-- ============================================= --}}
    @if(in_array(Auth::user()->level, ['admin', 'kepala_desa']) && $evaluasi->status_verifikasi === 'pending')
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-shield-alt text-primary me-2"></i>Verifikasi Evaluasi
                </h6>
                <form action="{{ route('admin.evaluasi-rekanan.verifikasi', $evaluasi->id_evaluasi_rekanan) }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select" required>
                                <option value="approved">Setujui</option>
                                <option value="rejected">Tolak</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Catatan (opsional)</label>
                            <input type="text" name="catatan_verifikasi" class="form-control"
                                   placeholder="Tambahkan catatan verifikasi...">
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection