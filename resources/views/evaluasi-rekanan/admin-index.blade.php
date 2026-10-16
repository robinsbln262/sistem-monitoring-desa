@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- ============================================= --}}
    {{-- HEADER --}}
    {{-- ============================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-clipboard-check text-primary me-2"></i>Evaluasi Rekanan
            </h4>
            <p class="text-muted mb-0">Kelola dan verifikasi evaluasi pelaksanaan proyek oleh rekanan</p>
        </div>
        <a href="{{ route('admin.kredibilitas-rekanan') }}" class="btn btn-outline-primary">
            <i class="fas fa-chart-bar me-1"></i>Lihat Kredibilitas Rekanan
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- STATISTIK --}}
    {{-- ============================================= --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-primary-subtle">
                            <i class="fas fa-list text-primary"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold">{{ $totalEvaluasi }}</h3>
                    <small class="text-muted d-block">Total Evaluasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-warning-subtle">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-warning">{{ $totalPending }}</h3>
                    <small class="text-muted d-block">Menunggu Verifikasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-icon bg-success-subtle">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                    </div>
                    <h3 class="mb-1 fw-bold text-success">{{ $totalApproved }}</h3>
                    <small class="text-muted d-block">Disetujui</small>
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
                            {{ format_angka($rataRating) }}
                        </h3>
                        <div class="d-inline-flex align-items-center gap-1">
                            @php $ratingInt = (int) round($rataRating); @endphp
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
    {{-- FILTER --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Rekanan</label>
                    <select name="rekanan" class="form-select form-select-sm">
                        <option value="">Semua Rekanan</option>
                        @foreach($rekananList as $r)
                            <option value="{{ $r->id_user }}" {{ request('rekanan') == $r->id_user ? 'selected' : '' }}>
                                {{ $r->nama_user }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Ketepatan Waktu</label>
                    <select name="tepat_waktu" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="1" {{ request('tepat_waktu') == '1' ? 'selected' : '' }}>Tepat Waktu</option>
                        <option value="0" {{ request('tepat_waktu') == '0' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.evaluasi-rekanan') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- TABEL --}}
    {{-- ============================================= --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-list-ol text-primary me-2"></i>Daftar Evaluasi
            </h6>
            <span class="badge bg-light text-dark border">
                {{ $evaluasis->total() }} evaluasi
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Proyek</th>
                            <th>Rekanan</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Tepat Waktu</th>
                            <th class="text-center">Kualitas</th>
                            <th class="text-center">Rating</th>
                            <th class="text-center">Status</th>
                            <th width="130" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluasis as $e)
                            @php
                                $rating = (int) ($e->rating ?? 0);
                            @endphp
                            <tr>
                                {{-- Proyek --}}
                                <td>
                                    <div class="fw-semibold">{{ $e->proyek->nama_pembangunan ?? '-' }}</div>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $e->proyek->lokasi ?? '-' }}
                                    </small>
                                </td>

                                {{-- Rekanan --}}
                                <td>
                                    <a href="{{ route('admin.evaluasi-rekanan.kinerja', $e->id_rekanan) }}"
                                       class="d-flex align-items-center text-decoration-none">
                                        <div class="user-avatar me-2"
                                             style="width:34px;height:34px;font-size:12px;">
                                            {{ strtoupper(substr($e->rekanan->nama_user ?? 'NA', 0, 2)) }}
                                        </div>
                                        <span class="fw-semibold text-dark">
                                            {{ $e->rekanan->nama_user ?? '-' }}
                                        </span>
                                    </a>
                                </td>

                                {{-- Tanggal --}}
                                <td class="text-center">
                                    <div>{{ $e->tanggal_evaluasi->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $e->tanggal_evaluasi->diffForHumans() }}</small>
                                </td>

                                {{-- Tepat Waktu --}}
                                <td class="text-center">{!! $e->tepat_waktu_badge !!}</td>

                                {{-- Kualitas --}}
                                <td class="text-center">{!! $e->kualitas_badge !!}</td>

                                {{-- Rating — SVG --}}
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     width="14" height="14"
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
                                                     width="14" height="14"
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
                                    <div class="small fw-bold text-muted mt-1">{{ $rating }}/5</div>
                                </td>

                                {{-- Status --}}
                                <td class="text-center">{!! $e->status_badge !!}</td>

                                {{-- Aksi --}}
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $e->id_evaluasi_rekanan }}"
                                                title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if($e->status_verifikasi === 'pending')
                                            <button class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#verifikasiModal{{ $e->id_evaluasi_rekanan }}"
                                                    title="Verifikasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x d-block mb-3"></i>
                                    <h6 class="mb-1">Belum ada data evaluasi</h6>
                                    <small>Evaluasi akan muncul di sini setelah rekanan mengisi form evaluasi.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $evaluasis->links() }}
            </div>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- MODAL DETAIL & VERIFIKASI --}}
{{-- ============================================= --}}
@foreach($evaluasis as $e)
    @php
        $rating = (int) ($e->rating ?? 0);
    @endphp

    {{-- ======================== --}}
    {{-- DETAIL MODAL --}}
    {{-- ======================== --}}
    <div class="modal fade" id="detailModal{{ $e->id_evaluasi_rekanan }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-clipboard-check me-2"></i>Detail Evaluasi
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- Info Utama --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <small class="text-muted d-block mb-1">Proyek</small>
                                <strong>{{ $e->proyek->nama_pembangunan ?? '-' }}</strong>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $e->proyek->lokasi ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <small class="text-muted d-block mb-1">Rekanan</small>
                                <strong>{{ $e->rekanan->nama_user ?? '-' }}</strong>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-tools me-1"></i>{{ $e->rekanan->bidang_keahlian ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Penilaian Ringkas --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 text-center h-100">
                                <small class="text-muted d-block mb-1">Tanggal</small>
                                <strong>{{ $e->tanggal_evaluasi->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 text-center h-100">
                                <small class="text-muted d-block mb-2">Tepat Waktu</small>
                                {!! $e->tepat_waktu_badge !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 text-center h-100">
                                <small class="text-muted d-block mb-2">Kualitas</small>
                                {!! $e->kualitas_badge !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded-3 text-center h-100">
                                <small class="text-muted d-block mb-1">Penyelesaian</small>
                                <strong class="text-primary">{{ $e->persentase_penyelesaian }}%</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Rating --}}
                    <div class="p-3 bg-light rounded-3 mb-3 text-center">
                        <small class="text-muted d-block mb-2">Rating Kinerja</small>
                        <div class="d-inline-flex align-items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         width="26" height="26"
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
                                         width="26" height="26"
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
                        <div class="fw-bold text-warning">{{ $rating }}/5</div>
                    </div>

                    {{-- Kendala --}}
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">
                            <i class="fas fa-exclamation-circle me-1"></i>KENDALA YANG DIHADAPI
                        </label>
                        <div class="p-3 border rounded-3">
                            {!! nl2br(e($e->kendala)) ?: '<em class="text-muted">-</em>' !!}
                        </div>
                    </div>

                    {{-- Hasil Akhir --}}
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-1">
                            <i class="fas fa-check-double me-1"></i>HASIL AKHIR PEKERJAAN
                        </label>
                        <div class="p-3 border rounded-3">
                            {!! nl2br(e($e->hasil_akhir)) ?: '<em class="text-muted">-</em>' !!}
                        </div>
                    </div>

                    {{-- Rekomendasi --}}
                    @if($e->rekomendasi_rekanan)
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-lightbulb me-1"></i>REKOMENDASI
                            </label>
                            <div class="p-3 border rounded-3">
                                {!! nl2br(e($e->rekomendasi_rekanan)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Catatan Verifikasi --}}
                    @if($e->catatan_verifikasi)
                        <div class="mb-0">
                            <label class="text-muted small d-block mb-1">
                                <i class="fas fa-comment-dots me-1"></i>CATATAN VERIFIKASI
                            </label>
                            <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded-3">
                                {!! nl2br(e($e->catatan_verifikasi)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Status Verifikasi --}}
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">Status Verifikasi</small>
                                {!! $e->status_badge !!}
                            </div>
                            @if($e->tanggal_verifikasi)
                                <div class="text-end">
                                    <small class="text-muted d-block">Diverifikasi</small>
                                    <strong class="small">
                                        {{ $e->verifikator->nama_user ?? '-' }}
                                    </strong>
                                    <div class="small text-muted">
                                        {{ $e->tanggal_verifikasi->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================== --}}
    {{-- VERIFIKASI MODAL --}}
    {{-- ======================== --}}
    @if($e->status_verifikasi === 'pending')
        <div class="modal fade" id="verifikasiModal{{ $e->id_evaluasi_rekanan }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('admin.evaluasi-rekanan.verifikasi', $e->id_evaluasi_rekanan) }}"
                      method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-shield-alt text-primary me-2"></i>Verifikasi Evaluasi
                            </h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            {{-- Info singkat --}}
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3" style="width:42px;height:42px;font-size:14px;">
                                        {{ strtoupper(substr($e->rekanan->nama_user ?? 'NA', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $e->rekanan->nama_user ?? '-' }}</strong>
                                        <small class="text-muted">{{ $e->proyek->nama_pembangunan ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Pilihan Status --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status Verifikasi</label>
                                <div class="d-flex gap-2">
                                    <div class="form-check flex-fill p-2 border rounded-3">
                                        <input class="form-check-input ms-1"
                                               type="radio"
                                               name="status_verifikasi"
                                               id="approve_{{ $e->id_evaluasi_rekanan }}"
                                               value="approved"
                                               checked>
                                        <label class="form-check-label ms-1 w-100"
                                               for="approve_{{ $e->id_evaluasi_rekanan }}">
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            <strong>Setujui</strong>
                                            <div class="small text-muted ms-4">
                                                Evaluasi valid & dihitung ke kredibilitas
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-check flex-fill p-2 border rounded-3">
                                        <input class="form-check-input ms-1"
                                               type="radio"
                                               name="status_verifikasi"
                                               id="reject_{{ $e->id_evaluasi_rekanan }}"
                                               value="rejected">
                                        <label class="form-check-label ms-1 w-100"
                                               for="reject_{{ $e->id_evaluasi_rekanan }}">
                                            <i class="fas fa-times-circle text-danger me-1"></i>
                                            <strong>Tolak</strong>
                                            <div class="small text-muted ms-4">
                                                Rekanan harus memperbaiki & kirim ulang
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-0">
                                <label class="form-label fw-semibold">
                                    Catatan <span class="text-muted small">(opsional)</span>
                                </label>
                                <textarea name="catatan_verifikasi"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Tambahkan catatan untuk rekanan..."></textarea>
                                <small class="text-muted">
                                    Catatan akan ditampilkan ke rekanan.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Verifikasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach
@endsection

@push('styles')
<style>
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
</style>
@endpush