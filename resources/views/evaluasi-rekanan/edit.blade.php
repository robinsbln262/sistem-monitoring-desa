@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ============================================= --}}
    {{-- ALERT: Validasi & Session --}}
    {{-- ============================================= --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h6 class="fw-bold mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>Validasi Gagal:
            </h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- HEADER --}}
    {{-- ============================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-edit text-warning me-2"></i>Edit Evaluasi Pelaksanaan
            </h4>
            <p class="text-muted mb-0">{{ $evaluasi->proyek->nama_pembangunan ?? '-' }}</p>
        </div>
        <a href="{{ route('evaluasi-rekanan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- INFO PROYEK --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <small class="text-muted d-block">Nama Proyek</small>
                    <strong>{{ $evaluasi->proyek->nama_pembangunan ?? '-' }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Lokasi</small>
                    <strong>{{ $evaluasi->proyek->lokasi ?? '-' }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Anggaran</small>
                    <strong>Rp {{ number_format($evaluasi->proyek->anggaran ?? 0, 0, ',', '.') }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Tahun Anggaran</small>
                    <strong>{{ $evaluasi->proyek->tahun_anggaran ?? '-' }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Tanggal Mulai</small>
                    <strong>{{ $evaluasi->proyek->tanggal_mulai?->format('d/m/Y') ?? '-' }}</strong>
                </div>
                <div class="col-md-4">
                    <small class="text-muted d-block">Tanggal Selesai (Rencana)</small>
                    <strong>{{ $evaluasi->proyek->tanggal_selesai?->format('d/m/Y') ?? '-' }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- ALERT JIKA STATUS REJECTED --}}
    {{-- ============================================= --}}
    @if($evaluasi->status_verifikasi === 'rejected')
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Evaluasi sebelumnya ditolak.</strong>
            @if($evaluasi->catatan_verifikasi)
                <div class="mt-2">
                    <strong>Catatan Admin/Kades:</strong> {{ $evaluasi->catatan_verifikasi }}
                </div>
            @endif
            <div class="mt-2 small">Silakan perbaiki dan kirim ulang evaluasi Anda.</div>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- FORM --}}
    {{-- ============================================= --}}
    <form action="{{ route('evaluasi-rekanan.update', $evaluasi->id_evaluasi_rekanan) }}"
          method="POST"
          id="formEditEvaluasi">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                {{-- ============================================ --}}
                {{-- Tanggal & Persentase --}}
                {{-- ============================================ --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Tanggal Evaluasi <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="tanggal_evaluasi" class="form-control"
                               value="{{ old('tanggal_evaluasi', $evaluasi->tanggal_evaluasi->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Persentase Penyelesaian (%) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="persentase_penyelesaian" class="form-control"
                               value="{{ old('persentase_penyelesaian', $evaluasi->persentase_penyelesaian) }}"
                               min="0" max="100" required>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- Tepat Waktu --}}
                {{-- ============================================ --}}
                <div class="mb-4 p-3 bg-light rounded-3">
                    <label class="form-label fw-semibold d-block mb-3">
                        Apakah proyek selesai tepat waktu? <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="selesai_tepat_waktu"
                               id="tepat_ya" value="1"
                               {{ old('selesai_tepat_waktu', $evaluasi->selesai_tepat_waktu ? '1' : '0') == '1' ? 'checked' : '' }}
                               onchange="toggleKeterlambatan()">
                        <label class="form-check-label" for="tepat_ya">
                            <i class="fas fa-check-circle text-success me-1"></i>Ya, Tepat Waktu
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="selesai_tepat_waktu"
                               id="tepat_tidak" value="0"
                               {{ old('selesai_tepat_waktu', $evaluasi->selesai_tepat_waktu ? '1' : '0') == '0' ? 'checked' : '' }}
                               onchange="toggleKeterlambatan()">
                        <label class="form-check-label" for="tepat_tidak">
                            <i class="fas fa-times-circle text-danger me-1"></i>Tidak, Terlambat
                        </label>
                    </div>

                    <div class="mt-3" id="keterlambatanWrap"
                         style="display: {{ old('selesai_tepat_waktu', $evaluasi->selesai_tepat_waktu ? '1' : '0') == '0' ? 'block' : 'none' }};">
                        <label class="form-label fw-semibold">Berapa hari keterlambatan?</label>
                        <input type="number" name="keterlambatan_hari" class="form-control"
                               min="0" value="{{ old('keterlambatan_hari', $evaluasi->keterlambatan_hari) }}"
                               style="max-width:200px;">
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- Kendala --}}
                {{-- ============================================ --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Kendala yang Dihadapi <span class="text-danger">*</span>
                    </label>
                    <textarea name="kendala" class="form-control" rows="4" required
                              minlength="10"
                              placeholder="Jelaskan kendala selama pelaksanaan proyek...">{{ old('kendala', $evaluasi->kendala) }}</textarea>
                </div>

                {{-- ============================================ --}}
                {{-- Hasil Akhir --}}
                {{-- ============================================ --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Hasil Akhir Pekerjaan <span class="text-danger">*</span>
                    </label>
                    <textarea name="hasil_akhir" class="form-control" rows="4" required
                              minlength="10"
                              placeholder="Jelaskan hasil akhir pekerjaan yang telah diselesaikan...">{{ old('hasil_akhir', $evaluasi->hasil_akhir) }}</textarea>
                </div>

                {{-- ============================================ --}}
                {{-- Kualitas Pekerjaan --}}
                {{-- ============================================ --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Kualitas Pekerjaan <span class="text-danger">*</span>
                    </label>
                    <select name="kualitas_pekerjaan" class="form-select" required>
                        <option value="sangat_baik" {{ old('kualitas_pekerjaan', $evaluasi->kualitas_pekerjaan) == 'sangat_baik' ? 'selected' : '' }}>Sangat Baik</option>
                        <option value="baik" {{ old('kualitas_pekerjaan', $evaluasi->kualitas_pekerjaan) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="cukup" {{ old('kualitas_pekerjaan', $evaluasi->kualitas_pekerjaan) == 'cukup' ? 'selected' : '' }}>Cukup</option>
                        <option value="kurang" {{ old('kualitas_pekerjaan', $evaluasi->kualitas_pekerjaan) == 'kurang' ? 'selected' : '' }}>Kurang</option>
                    </select>
                </div>

                {{-- ============================================ --}}
                {{-- RATING BINTANG INTERAKTIF --}}
                {{-- ============================================ --}}
                @php
                    $ratingValue = (int) old('rating', $evaluasi->rating ?? 5);
                @endphp
                <div class="mb-4 p-3 bg-light rounded-3">
                    <label class="form-label fw-semibold d-block mb-2">
                        Rating Kinerja <span class="text-danger">*</span>
                    </label>
                    <small class="text-muted d-block mb-3">
                        Klik bintang untuk memberi penilaian (1 = Sangat Kurang, 5 = Sangat Baik)
                    </small>

                    {{-- Hidden input untuk nilai rating --}}
                    <input type="hidden" name="rating" id="inputRating" value="{{ $ratingValue }}" required>

                    {{-- Bintang yang bisa diklik --}}
                    <div class="d-flex align-items-center gap-2" id="ratingBintang">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                    class="btn-rating-star"
                                    data-value="{{ $i }}"
                                    aria-label="Beri rating {{ $i }}"
                                    style="background:none; border:0; padding:4px; cursor:pointer; transition:transform .15s ease;">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="star-svg"
                                     width="38" height="38"
                                     viewBox="0 0 24 24"
                                     data-index="{{ $i }}"
                                     fill="{{ $i <= $ratingValue ? '#ffc107' : 'none' }}"
                                     stroke="{{ $i <= $ratingValue ? '#ffc107' : '#c9ced6' }}"
                                     stroke-width="1.5"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            </button>
                        @endfor

                        {{-- Label dinamis --}}
                        <span class="ms-2 fw-bold text-warning" id="ratingLabel" style="font-size:1.05rem;">
                            {{ $ratingValue }}/5
                        </span>
                    </div>

                    {{-- Teks deskripsi rating --}}
                    <div class="mt-2 small text-muted" id="ratingDeskripsi">
                        @switch($ratingValue)
                            @case(5) Sangat Baik – pekerjaan berkualitas tinggi @break
                            @case(4) Baik – pekerjaan sesuai standar @break
                            @case(3) Cukup – ada beberapa kekurangan @break
                            @case(2) Kurang – banyak kekurangan @break
                            @default Sangat Kurang – pekerjaan tidak memenuhi standar
                        @endswitch
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- Rekomendasi --}}
                {{-- ============================================ --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Rekomendasi untuk Proyek Selanjutnya
                    </label>
                    <textarea name="rekomendasi_rekanan" class="form-control" rows="3"
                              placeholder="Saran/perbaikan untuk proyek ke depan (opsional)">{{ old('rekomendasi_rekanan', $evaluasi->rekomendasi_rekanan) }}</textarea>
                </div>

                {{-- ============================================ --}}
                {{-- Tombol --}}
                {{-- ============================================ --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('evaluasi-rekanan.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning" id="btnSubmitEdit">
                        <i class="fas fa-save me-1"></i>Perbarui & Kirim Ulang
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .btn-rating-star:hover {
        transform: scale(1.12);
    }

    .btn-rating-star:active {
        transform: scale(0.96);
    }

    .btn-rating-star:hover .star-svg,
    .star-svg.hover-active {
        fill: #ffc107;
        stroke: #ffc107;
    }
</style>
@endpush

@push('scripts')
<script>
    // =====================================================
    // TOGGLE KETERLAMBATAN
    // =====================================================
    function toggleKeterlambatan() {
        const tidak = document.getElementById('tepat_tidak').checked;
        document.getElementById('keterlambatanWrap').style.display = tidak ? 'block' : 'none';
    }

    // =====================================================
    // RATING BINTANG INTERAKTIF
    // =====================================================
    (function() {
        const inputRating   = document.getElementById('inputRating');
        const ratingLabel   = document.getElementById('ratingLabel');
        const ratingDeskrip = document.getElementById('ratingDeskripsi');
        const stars         = document.querySelectorAll('#ratingBintang .star-svg');

        const deskripsiMap = {
            1: 'Sangat Kurang – pekerjaan tidak memenuhi standar',
            2: 'Kurang – banyak kekurangan',
            3: 'Cukup – ada beberapa kekurangan',
            4: 'Baik – pekerjaan sesuai standar',
            5: 'Sangat Baik – pekerjaan berkualitas tinggi',
        };

        const COLOR_ACTIVE = '#ffc107';
        const COLOR_EMPTY  = '#c9ced6';

        function renderStars(value) {
            stars.forEach(function(star) {
                const idx = parseInt(star.dataset.index, 10);
                if (idx <= value) {
                    star.setAttribute('fill', COLOR_ACTIVE);
                    star.setAttribute('stroke', COLOR_ACTIVE);
                } else {
                    star.setAttribute('fill', 'none');
                    star.setAttribute('stroke', COLOR_EMPTY);
                }
            });
        }

        function updateRating(value) {
            inputRating.value       = value;
            ratingLabel.textContent = value + '/5';
            ratingDeskrip.textContent = deskripsiMap[value] || '';
            renderStars(value);
        }

        // Klik bintang
        stars.forEach(function(star) {
            star.parentElement.addEventListener('click', function() {
                const value = parseInt(this.dataset.value, 10);
                updateRating(value);
            });
        });

        // Hover preview
        stars.forEach(function(star) {
            star.parentElement.addEventListener('mouseenter', function() {
                const value = parseInt(this.dataset.value, 10);
                stars.forEach(function(s) {
                    const idx = parseInt(s.dataset.index, 10);
                    if (idx <= value) {
                        s.classList.add('hover-active');
                    }
                });
            });

            star.parentElement.addEventListener('mouseleave', function() {
                stars.forEach(function(s) {
                    s.classList.remove('hover-active');
                });
            });
        });

        // Render awal
        renderStars(parseInt(inputRating.value, 10) || 5);
    })();

    // =====================================================
    // SUBMIT: DISABLE BUTTON
    // =====================================================
    document.getElementById('formEditEvaluasi').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitEdit');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memperbarui...';
    });
</script>
@endpush