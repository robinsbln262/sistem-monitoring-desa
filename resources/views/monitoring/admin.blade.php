@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i> Monitoring Proyek (Admin)</h5>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-secondary rounded-pill px-3 py-2">
                    <i class="fas fa-list me-1"></i> Total: {{ $proyeks->total() }} Proyek
                </span>
                <a href="{{ route('admin.monitoring.verifikasi') }}" class="btn btn-warning btn-sm rounded-pill">
                    <i class="fas fa-shield-alt me-1"></i> Verifikasi Progres
                    @if($pendingCount > 0)
                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- ===== FILTER STATUS VERIFIKASI (SERVER-SIDE) ===== -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="d-flex gap-2 flex-wrap align-items-center">
                    <label class="fw-semibold me-2 small">Filter Status Verifikasi:</label>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('admin.monitoring', ['status_verifikasi' => 'all']) }}"
                           class="btn btn-outline-secondary {{ ($filterStatus ?? 'all') === 'all' ? 'active' : '' }}">
                            <i class="fas fa-list me-1"></i> Semua
                        </a>
                        <a href="{{ route('admin.monitoring', ['status_verifikasi' => 'pending']) }}"
                           class="btn btn-outline-warning {{ ($filterStatus ?? 'all') === 'pending' ? 'active' : '' }}">
                            <i class="fas fa-clock me-1"></i> Pending
                            <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
                        </a>
                        <a href="{{ route('admin.monitoring', ['status_verifikasi' => 'approved']) }}"
                           class="btn btn-outline-success {{ ($filterStatus ?? 'all') === 'approved' ? 'active' : '' }}">
                            <i class="fas fa-check-circle me-1"></i> Approved
                        </a>
                        <a href="{{ route('admin.monitoring', ['status_verifikasi' => 'rejected']) }}"
                           class="btn btn-outline-danger {{ ($filterStatus ?? 'all') === 'rejected' ? 'active' : '' }}">
                            <i class="fas fa-times-circle me-1"></i> Rejected
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pembangunan</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th width="12%">Progres</th>
                        <th width="35%">Riwayat Monitoring</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedMonitorings as $index => $item)
                        @php
                            $p = $item['pembangunan'];
                            $riwayat = $item['riwayat'];
                            $progresTerbaru = $item['progres_terbaru'];
                        @endphp
                        <tr class="monitoring-row">
                            <td>{{ $proyeks->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $p->nama_pembangunan }}</td>
                            <td>{{ $p->lokasi }}</td>
                            <td>
                                @if ($p->status == 'direncanakan')
                                    <span class="badge bg-secondary rounded-pill px-3 py-2"><i
                                            class="fas fa-clock me-1"></i> Direncanakan</span>
                                @elseif($p->status == 'berjalan')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i
                                            class="fas fa-spinner fa-pulse me-1"></i> Berjalan</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i
                                            class="fas fa-check-circle me-1"></i> Selesai</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                        <div class="progress-bar {{ $progresTerbaru == 100 ? 'bg-success' : 'bg-primary' }}"
                                            style="width: {{ $progresTerbaru }}%"></div>
                                    </div>
                                    <span class="small fw-bold">{{ $progresTerbaru }}%</span>
                                </div>
                            </td>
                            <td>
                                <div class="riwayat-list">
                                    @foreach ($riwayat->take(3) as $r)
                                        <div class="riwayat-item mb-2 p-2 bg-light rounded">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="badge bg-info text-white">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ date('d/m/Y', strtotime($r->tanggal_monitoring)) }}
                                                </span>
                                                <span class="fw-bold text-primary">{{ $r->progres }}%</span>
                                            </div>
                                            <div class="small mt-1">
                                                <strong><i class="fas fa-tasks me-1"></i> {{ $r->status_progres }}</strong>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                <i class="fas fa-info-circle me-1"></i>
                                                {{ Str::limit(strip_tags($r->keterangan), 40) ?: '-' }}
                                            </div>

                                            <!-- ===== BADGE STATUS VERIFIKASI ===== -->
                                            <div class="mt-1">
                                                @if($r->status_verifikasi == 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i> Menunggu Verifikasi
                                                    </span>
                                                @elseif($r->status_verifikasi == 'approved')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Disetujui
                                                    </span>
                                                @elseif($r->status_verifikasi == 'rejected')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                                    </span>
                                                    @if($r->catatan_verifikasi)
                                                        <span class="badge bg-secondary" style="cursor:pointer;" 
                                                              onclick="lihatCatatanPenolakan('{{ addslashes($r->catatan_verifikasi) }}')" 
                                                              title="Lihat Catatan Penolakan">
                                                            <i class="fas fa-info-circle me-1"></i> Catatan
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-question-circle me-1"></i> Unknown
                                                    </span>
                                                @endif
                                            </div>

                                            @if ($r->komentar_admin)
                                                <div class="mt-1">
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-comment me-1"></i> Ada Komentar
                                                    </span>
                                                </div>
                                            @endif
                                            {{-- ================= FOTO DOKUMENTASI ================= --}}
                                            @if($r->foto_before || $r->foto_after)
                                                @php
                                                    $fotoBeforeUrl = $r->foto_before_url ?? ($r->foto_before ? asset('storage/' . $r->foto_before) : null);
                                                    $fotoAfterUrl = $r->foto_after_url ?? ($r->foto_after ? asset('storage/' . $r->foto_after) : null);
                                                @endphp

                                                <div class="row g-2 mt-2">
                                                    @if($fotoBeforeUrl)
                                                        <div class="col-6">
                                                            <div class="photo-thumb-card">
                                                                <div class="photo-thumb-header bg-secondary">
                                                                    <i class="fas fa-camera me-1"></i> SEBELUM
                                                                </div>
                                                                <img
                                                                    src="{{ $fotoBeforeUrl }}"
                                                                    class="photo-thumb"
                                                                    alt="Foto Sebelum"
                                                                    loading="lazy"
                                                                    data-photo-url="{{ $fotoBeforeUrl }}"
                                                                    data-photo-caption="Foto Sebelum - {{ $r->status_progres }}"
                                                                    onclick="showLightbox(this.dataset.photoUrl, this.dataset.photoCaption)"
                                                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';"
                                                                >
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($fotoAfterUrl)
                                                        <div class="col-6">
                                                            <div class="photo-thumb-card">
                                                                <div class="photo-thumb-header bg-success">
                                                                    <i class="fas fa-camera me-1"></i> SESUDAH
                                                                </div>
                                                                <img
                                                                    src="{{ $fotoAfterUrl }}"
                                                                    class="photo-thumb"
                                                                    alt="Foto Sesudah"
                                                                    loading="lazy"
                                                                    data-photo-url="{{ $fotoAfterUrl }}"
                                                                    data-photo-caption="Foto Sesudah - {{ $r->status_progres }}"
                                                                    onclick="showLightbox(this.dataset.photoUrl, this.dataset.photoCaption)"
                                                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';"
                                                                >
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="mt-2">
                                                @if($r->status_verifikasi == 'pending')
                                                    <a href="{{ route('admin.monitoring.verifikasi') }}"
                                                       class="btn btn-sm btn-warning btn-action"
                                                       title="Verifikasi Progres">
                                                        <i class="fas fa-shield-alt me-1"></i> Verifikasi
                                                    </a>
                                                @endif

                                                @if ($r->komentar_admin)
                                                    <button class="btn btn-sm btn-info btn-action"
                                                        onclick="lihatKomentar('{{ addslashes($r->komentar_admin) }}')"
                                                        title="Lihat Komentar">
                                                        <i class="fas fa-eye me-1"></i> Lihat Komentar
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-primary btn-action"
                                                        onclick="tambahKomentar({{ $r->id_monitoring }})"
                                                        title="Beri Komentar">
                                                        <i class="fas fa-comment me-1"></i> Komentar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($riwayat->count() > 3)
                                        <div class="text-center mt-2">
                                            <span class="badge bg-secondary rounded-pill" style="cursor: pointer;"
                                                onclick="showDetailRiwayat({{ $p->id_pembangunan }})">
                                                <i class="fas fa-ellipsis-h me-1"></i> +{{ $riwayat->count() - 3 }} riwayat
                                                lainnya
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical btn-group-sm" role="group">
                                    <button class="btn btn-sm btn-info"
                                        onclick="showDetailRiwayat({{ $p->id_pembangunan }})" title="Lihat Semua Riwayat">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </button>
                                    <a href="{{ route('admin.monitoring.verifikasi') }}" 
                                       class="btn btn-sm btn-warning mt-1" 
                                       title="Verifikasi Progres">
                                        <i class="fas fa-shield-alt me-1"></i> Verifikasi
                                        @if($pendingCount > 0)
                                            <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                                        @endif
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-chart-line fa-3x mb-3 d-block"></i>
                                <h5>Belum Ada Data Monitoring</h5>
                                <p>Belum ada rekanan yang melakukan monitoring proyek.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ========================================== --}}
        {{-- PAGINATION — VENDOR CUSTOM --}}
        {{-- ========================================== --}}
        @if($proyeks->hasPages())
            <div class="mt-4">
                {{ $proyeks->links() }}
            </div>
        @endif
    </div>

    <!-- ========================================== -->
    <!-- MODAL KOMENTAR -->
    <!-- ========================================== -->
    <div class="modal fade" id="komentarModal" tabindex="-1" aria-labelledby="komentarModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white" id="komentarModalLabel">
                        <i class="fas fa-comment me-2"></i> Beri Komentar Admin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="komentarForm" method="POST" action="">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-edit text-primary me-1"></i> Komentar <span class="text-danger">*</span>
                            </label>
                            <textarea name="komentar_admin" id="komentarInput" class="form-control rounded-3" rows="5"
                                placeholder="Tulis komentar atau catatan untuk progres ini..." required
                                style="resize: vertical; min-height: 120px;"></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i> Komentar akan terlihat oleh rekanan dan masyarakat
                            </small>
                        </div>
                        <div class="alert alert-info border-0 rounded-3 bg-light">
                            <i class="fas fa-building me-2"></i>
                            <strong>Proyek:</strong> <span id="komentarProyekNama">-</span>
                            <br>
                            <i class="fas fa-calendar me-2"></i>
                            <strong>Tanggal Monitoring:</strong> <span id="komentarTanggal">-</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4" id="btnKirimKomentar">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Komentar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL LIHAT KOMENTAR -->
    <!-- ========================================== -->
    <div class="modal fade" id="lihatKomentarModal" tabindex="-1" aria-labelledby="lihatKomentarModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white" id="lihatKomentarModalLabel">
                        <i class="fas fa-comment me-2"></i> Komentar Admin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 rounded-3 p-4">
                        <div class="d-flex">
                            <i class="fas fa-quote-left fa-2x text-warning me-3 opacity-50"></i>
                            <div>
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-user-shield me-1"></i> Komentar dari Admin
                                </small>
                                <p class="mb-0 fw-semibold fs-5" id="komentarText" style="word-wrap: break-word;">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ========================================== -->
    <!-- MODAL LIHAT CATATAN PENOLAKAN -->
    <!-- ========================================== -->
    <div class="modal fade" id="catatanPenolakanModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
                    <h5 class="modal-title text-white" id="catatanPenolakanModalLabel">
                        <i class="fas fa-info-circle me-2"></i> Catatan Penolakan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-danger border-0 rounded-3 p-4">
                        <div class="d-flex">
                            <i class="fas fa-times-circle fa-2x text-danger me-3 opacity-50"></i>
                            <div>
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-user-shield me-1"></i> Alasan Penolakan dari Admin
                                </small>
                                <p class="mb-0 fw-semibold fs-5" id="catatanPenolakanText" style="word-wrap: break-word;">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- ========================================== -->
    <!-- LIGHTBOX FOTO -->
    <!-- ========================================== -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center p-0 position-relative">
                    <button type="button"
                            class="btn btn-light btn-lg rounded-circle position-absolute top-0 end-0 m-3"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"
                            style="z-index:1060;width:46px;height:46px;">
                        <i class="fas fa-times"></i>
                    </button>

                    <img id="lightboxImage"
                         src=""
                         alt="Foto Dokumentasi"
                         class="lightbox-image"
                         onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">

                    <div id="lightboxCaption" class="lightbox-caption"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL DETAIL RIWAYAT -->
    <!-- ========================================== -->
    <div class="modal fade" id="detailRiwayatModal" tabindex="-1" aria-labelledby="detailRiwayatModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white" id="detailRiwayatModalLabel">
                        <i class="fas fa-history me-2"></i> Riwayat Monitoring Lengkap
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="detailRiwayatContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat riwayat monitoring...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <!-- Message akan diisi oleh JS -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .riwayat-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .riwayat-item {
            transition: all 0.2s ease;
            border-left: 3px solid #1e3c72;
        }

        .riwayat-item:hover {
            background: #e9ecef !important;
            transform: translateX(5px);
        }

        .btn-action {
            padding: 4px 10px;
            margin: 2px;
            font-size: 12px;
        }

        /* ==========================================
           FOTO THUMBNAIL MONITORING
           ========================================== */
        .photo-thumb-card {
            overflow: hidden;
            border-radius: 10px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .photo-thumb-header {
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 5px 8px;
            letter-spacing: .2px;
        }

        .photo-thumb {
            display: block;
            width: 100%;
            height: 105px;
            object-fit: cover;
            cursor: pointer;
            transition: transform .2s ease, opacity .2s ease;
        }

        .photo-thumb:hover {
            transform: scale(1.03);
            opacity: .92;
        }

        /* ==========================================
           LIGHTBOX
           ========================================== */
        #lightboxModal,
        #komentarModal,
        #lihatKomentarModal,
        #catatanPenolakanModal {
            z-index: 1080;
        }

        .modal-backdrop {
            z-index: 1050;
        }

        #lightboxModal .modal-content {
            background: transparent !important;
        }

        #lightboxModal .modal-body {
            padding: 0 !important;
        }

        .lightbox-image {
            display: block;
            max-width: 95%;
            max-height: 82vh;
            width: auto;
            height: auto;
            margin: 0 auto;
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, .45);
            object-fit: contain;
        }

        .lightbox-caption {
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            margin-top: 12px;
            text-shadow: 0 2px 6px rgba(0, 0, 0, .7);
        }

        /* ==========================================
           FOTO DI DETAIL RIWAYAT
           ========================================== */
        .detail-photo-grid {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .detail-photo-item {
            width: 145px;
            max-width: 100%;
            overflow: hidden;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, .08);
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .detail-photo-label {
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 5px 7px;
        }

        .detail-photo-image {
            display: block;
            width: 100%;
            height: 95px;
            object-fit: cover;
            cursor: pointer;
            transition: transform .2s ease;
        }

        .detail-photo-image:hover {
            transform: scale(1.04);
        }

        /* FOTO MODAL RIWAYAT MONITORING LENGKAP */
        .riwayat-foto-wrapper { margin-top: 4px; }
        .riwayat-foto-title { font-size: 11px; font-weight: 700; color: #495057; margin-bottom: 6px; }
        .riwayat-foto-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 7px; }
        .riwayat-foto-card { overflow: hidden; border: 1px solid #dee2e6; border-radius: 9px; background: #fff; box-shadow: 0 2px 7px rgba(0,0,0,.06); }
        .riwayat-foto-label { padding: 4px 7px; color: #fff; font-size: 9px; font-weight: 700; }
        .riwayat-foto-label.before { background: #6c757d; }
        .riwayat-foto-label.after { background: #198754; }
        .riwayat-foto-image-wrap { position: relative; height: 95px; overflow: hidden; cursor: pointer; background: #f1f3f5; }
        .riwayat-foto-image { width: 100%; height: 100%; display: block; object-fit: cover; transition: transform .2s, filter .2s; }
        .riwayat-foto-image-wrap:hover .riwayat-foto-image { transform: scale(1.05); filter: brightness(.7); }
        .riwayat-foto-overlay { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#fff; background:rgba(0,0,0,.25); opacity:0; transition:opacity .2s; pointer-events:none; }
        .riwayat-foto-overlay i { font-size:18px; }
        .riwayat-foto-overlay span { font-size:9px; font-weight:600; }
        .riwayat-foto-image-wrap:hover .riwayat-foto-overlay { opacity:1; }

        @media (max-width: 576px) {
            .photo-thumb {
                height: 85px;
            }

            .detail-photo-item {
                width: 125px;
            }

            .detail-photo-image {
                height: 82px;
            }

            .lightbox-image {
                max-width: 98%;
                max-height: 78vh;
            }
        }

        #detailRiwayatContent .table td,
        #detailRiwayatContent .table th {
            vertical-align: middle;
            padding: 12px 10px;
        }

        #detailRiwayatContent .card-header {
            border-radius: 16px 16px 0 0;
        }

        .modal-content {
            border-radius: 20px !important;
        }

        .toast {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .toast-success {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
        }

        .toast-error {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%) !important;
        }

        .progress-detail-item {
            border-left: 4px solid #1e3c72;
            padding-left: 20px;
        }

        .progress-detail-item:last-child {
            border-bottom: none !important;
        }

        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1050 !important;
        }

        .modal.show {
            z-index: 1055 !important;
        }

        .modal-backdrop.show {
            z-index: 1045 !important;
        }

        .position-fixed {
            z-index: 9999 !important;
        }

        /* Filter button active */
        .btn-group .btn.active {
            background-color: #1e3c72;
            color: white;
            border-color: #1e3c72;
        }
        .btn-group .btn-outline-warning.active {
            background-color: #ffc107;
            color: #212529;
            border-color: #ffc107;
        }
        .btn-group .btn-outline-success.active {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }
        .btn-group .btn-outline-danger.active {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }
    </style>
@endpush

@push('scripts')
    <script>
        var baseUrl = '{{ url('/') }}';
        var csrfToken = '{{ csrf_token() }}';
        var activeFilter = 'all';

        $(document).ready(function() {
            // ==========================================
            // DATATABLE DIHAPUS — Pakai server-side pagination Laravel
            // Pagination dari vendor custom (AppServiceProvider)
            // ==========================================

            // ==========================================
            // HANDLE SUBMIT KOMENTAR VIA AJAX
            // ==========================================
            $('#komentarForm').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var komentar = $('#komentarInput').val().trim();

                if (komentar === '') {
                    showToast('Komentar tidak boleh kosong!', 'error');
                    $('#komentarInput').focus();
                    return false;
                }

                $('#btnKirimKomentar').prop('disabled', true);
                $('#btnKirimKomentar').html('<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        komentar_admin: komentar
                    },
                    success: function(response) {
                        $('#komentarModal').modal('hide');
                        showToast('Komentar berhasil ditambahkan!', 'success');

                        $('#komentarInput').val('');
                        $('#komentarForm').attr('action', '');

                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        var errorMsg = 'Gagal menambahkan komentar. Silakan coba lagi.';

                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        } catch (e) {}

                        showToast(errorMsg, 'error');
                        $('#btnKirimKomentar').prop('disabled', false);
                        $('#btnKirimKomentar').html(
                            '<i class="fas fa-paper-plane me-2"></i> Kirim Komentar');
                    }
                });
            });

            // ==========================================
            // HANDLE ENTER KEY DI TEXTAREA
            // ==========================================
            $('#komentarInput').on('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    $('#komentarForm').submit();
                }
            });

            // ==========================================
            // RESET MODAL SAAT DITUTUP
            // ==========================================
            $('#komentarModal').on('hidden.bs.modal', function() {
                $('#komentarInput').val('');
                $('#komentarForm').attr('action', '');
                $('#btnKirimKomentar').prop('disabled', false);
                $('#btnKirimKomentar').html('<i class="fas fa-paper-plane me-2"></i> Kirim Komentar');
                $('#komentarProyekNama').text('-');
                $('#komentarTanggal').text('-');
            });

            // Pastikan modal lain tidak saling bertumpuk
            $('.modal').on('show.bs.modal', function() {
                var zIndex = 1050 + $('.modal:visible').length * 5;
                $(this).css('z-index', zIndex);
            });
        });

        // ==========================================
        // TAMBAH KOMENTAR
        // ==========================================
        function tambahKomentar(id) {
            $('#komentarForm').attr('action', baseUrl + '/monitoring/' + id + '/komentar');

            $('#komentarInput').val('');
            $('#komentarProyekNama').text('Memuat...');
            $('#komentarTanggal').text('Memuat...');

            $.ajax({
                url: baseUrl + '/monitoring/' + id + '/get-data',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data && data.id_monitoring) {
                        var proyekNama = data.proyek ? data.proyek.nama_pembangunan : 'Proyek #' + data
                            .id_pembangunan;
                        var tanggal = data.tanggal_monitoring_formatted ?
                            new Date(data.tanggal_monitoring_formatted).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            }) : '-';

                        $('#komentarProyekNama').text(proyekNama);
                        $('#komentarTanggal').text(tanggal);
                    } else {
                        $('#komentarProyekNama').text('Proyek #' + id);
                        $('#komentarTanggal').text('-');
                    }
                },
                error: function() {
                    $('#komentarProyekNama').text('Proyek #' + id);
                    $('#komentarTanggal').text('-');
                }
            });

            openNestedModal('komentarModal');

            setTimeout(function() {
                $('#komentarInput').focus();
            }, 500);
        }

        // ==========================================
        // LIHAT KOMENTAR
        // ==========================================
        function lihatKomentar(komentar) {
            if (!komentar || komentar.trim() === '') {
                showToast('Tidak ada komentar', 'error');
                return;
            }
            $('#komentarText').text(komentar);
            openNestedModal('lihatKomentarModal');
        }

        // ==========================================
        // LIHAT CATATAN PENOLAKAN
        // ==========================================
        function lihatCatatanPenolakan(catatan) {
            if (!catatan || catatan.trim() === '') {
                showToast('Tidak ada catatan', 'error');
                return;
            }
            $('#catatanPenolakanText').text(catatan);
            openNestedModal('catatanPenolakanModal');
        }

        // ==========================================
        // LIGHTBOX FOTO
        // ==========================================
        let nestedFromRiwayat = false;

        function openNestedModal(modalId) {
            const detailModalElement = document.getElementById('detailRiwayatModal');
            const childModalElement = document.getElementById(modalId);

            if (!childModalElement) {
                console.error('Modal tidak ditemukan:', modalId);
                return;
            }

            const detailModal = detailModalElement
                ? bootstrap.Modal.getOrCreateInstance(detailModalElement)
                : null;

            const childModal = bootstrap.Modal.getOrCreateInstance(childModalElement);

            nestedFromRiwayat = !!(
                detailModalElement &&
                detailModalElement.classList.contains('show')
            );

            if (nestedFromRiwayat && detailModal) {
                detailModal.hide();
            }

            setTimeout(function() {
                childModal.show();
            }, nestedFromRiwayat ? 180 : 0);
        }

        function showLightbox(imageUrl, caption = '') {
            if (!imageUrl || imageUrl.trim() === '') {
                showToast('Foto tidak tersedia', 'error');
                return;
            }

            let fullUrl = imageUrl.trim();

            if (!fullUrl.startsWith('http://') &&
                !fullUrl.startsWith('https://') &&
                !fullUrl.startsWith('/')) {
                fullUrl = baseUrl.replace(/\/$/, '') + '/storage/' + fullUrl.replace(/^\//, '');
            }

            $('#lightboxImage').attr('src', fullUrl);
            $('#lightboxCaption').text(caption || 'Foto Dokumentasi');

            openNestedModal('lightboxModal');
        }

        $(document).on('hidden.bs.modal', '#lightboxModal, #komentarModal, #lihatKomentarModal, #catatanPenolakanModal', function() {
            if (!nestedFromRiwayat) {
                return;
            }

            nestedFromRiwayat = false;

            const detailModalElement = document.getElementById('detailRiwayatModal');
            if (!detailModalElement) {
                return;
            }

            setTimeout(function() {
                bootstrap.Modal.getOrCreateInstance(detailModalElement).show();
            }, 120);
        })

        // ==========================================
        // SHOW DETAIL RIWAYAT
        // ==========================================
        function showDetailRiwayat(idPembangunan) {
            $('#detailRiwayatContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat riwayat monitoring...</p>
                </div>
            `);
            $('#detailRiwayatModal').modal('show');

            $.ajax({
                url: baseUrl + '/monitoring-riwayat/proyek/' + idPembangunan,
                type: 'GET',
                dataType: 'json',
                timeout: 15000,
                success: function(response) {
                    if (response.success) {
                        let html = buildRiwayatHtml(response);
                        $('#detailRiwayatContent').html(html);
                    } else {
                        throw new Error(response.error || 'Gagal memuat data');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.status, error);
                    console.error('Response:', xhr.responseText);

                    $('#detailRiwayatContent').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                            <h5 class="text-danger">Gagal Memuat Data</h5>
                            <p class="text-muted">Status: ${xhr.status} - ${error}</p>
                            <button class="btn btn-primary mt-3 rounded-3 px-4" onclick="showDetailRiwayat(${idPembangunan})">
                                <i class="fas fa-sync-alt me-2"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                }
            });
        }

        // ==========================================
        // BUILD RIWAYAT HTML
        // ==========================================
        function buildRiwayatHtml(response) {
            let statusClass = 'bg-secondary';
            let statusText = 'Direncanakan';
            if (response.pembangunan.status == 'berjalan') {
                statusClass = 'bg-warning text-dark';
                statusText = 'Sedang Berjalan';
            } else if (response.pembangunan.status == 'selesai') {
                statusClass = 'bg-success';
                statusText = 'Selesai';
            }

            let html = `
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-top-4 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building text-primary me-2"></i>
                                <strong>${escapeHtml(response.pembangunan.nama_pembangunan)}</strong>
                            </div>
                            <span class="badge bg-secondary">${escapeHtml(response.pembangunan.lokasi)}</span>
                            <span class="badge ${statusClass}">${statusText}</span>
                        </div>
                        <span class="badge bg-info fs-6">Progres: ${response.pembangunan.progress}%</span>
                    </div>
                    <div class="card-body p-4">
            `;

            if (response.monitorings && response.monitorings.length > 0) {
                html += `
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="8%">Progres</th>
                                    <th width="15%">Status</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="12%">Dana Terpakai</th>
                                    <th width="13%">Status Verifikasi</th>
                                    <th width="20%">Dokumentasi</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                response.monitorings.forEach((m, index) => {
                    let danaHtml = m.dana_terpakai ? 'Rp ' + new Intl.NumberFormat('id-ID').format(m
                        .dana_terpakai) : '-';

                    let verifikasiBadge = '';
                    if (m.status_verifikasi == 'pending') {
                        verifikasiBadge = `<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>`;
                    } else if (m.status_verifikasi == 'approved') {
                        verifikasiBadge = `<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Approved</span>`;
                    } else if (m.status_verifikasi == 'rejected') {
                        verifikasiBadge = `<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>`;
                    } else {
                        verifikasiBadge = `<span class="badge bg-secondary">-</span>`;
                    }

                    let komentarHtml = '';
                    if (m.komentar_admin) {
                        komentarHtml = `
                            <button class="btn btn-sm btn-info btn-action" onclick="lihatKomentar('${escapeHtml(m.komentar_admin)}')" title="Lihat Komentar">
                                <i class="fas fa-comment"></i>
                            </button>
                        `;
                    } else {
                        komentarHtml = `
                            <button class="btn btn-sm btn-primary btn-action" onclick="tambahKomentar(${m.id_monitoring})" title="Beri Komentar">
                                <i class="fas fa-comment"></i>
                            </button>
                        `;
                    }

                    let fotoHtml = '';
                    let fotoBeforeUrl = m.foto_before_url || '';
                    let fotoAfterUrl = m.foto_after_url || '';

                    if (!fotoBeforeUrl && m.foto_before) {
                        fotoBeforeUrl = baseUrl.replace(/\/$/, '') + '/storage/' + String(m.foto_before).replace(/^\//, '');
                    }
                    if (!fotoAfterUrl && m.foto_after) {
                        fotoAfterUrl = baseUrl.replace(/\/$/, '') + '/storage/' + String(m.foto_after).replace(/^\//, '');
                    }

                    const safeBeforeUrl = escapeHtml(fotoBeforeUrl);
                    const safeAfterUrl = escapeHtml(fotoAfterUrl);
                    const statusProgres = escapeHtml(m.status_progres || 'Monitoring');

                    if (fotoBeforeUrl || fotoAfterUrl) {
                        fotoHtml = `
                            <div class="riwayat-foto-wrapper">
                                <div class="riwayat-foto-title"><i class="fas fa-images me-2"></i>Dokumentasi Foto</div>
                                <div class="riwayat-foto-grid">
                                    ${fotoBeforeUrl ? `
                                        <div class="riwayat-foto-card">
                                            <div class="riwayat-foto-label before"><i class="fas fa-camera me-1"></i> SEBELUM</div>
                                            <div class="riwayat-foto-image-wrap" onclick="showLightbox('${safeBeforeUrl}', 'Foto Sebelum - ${statusProgres}')" title="Klik untuk melihat foto lebih besar">
                                                <img src="${safeBeforeUrl}" class="riwayat-foto-image" alt="Foto Sebelum" loading="lazy" onerror="this.onerror=null;this.src='${baseUrl}/images/no-image.png';">
                                                <div class="riwayat-foto-overlay"><i class="fas fa-search-plus"></i><span>Lihat Foto</span></div>
                                            </div>
                                        </div>
                                    ` : ''}
                                    ${fotoAfterUrl ? `
                                        <div class="riwayat-foto-card">
                                            <div class="riwayat-foto-label after"><i class="fas fa-camera me-1"></i> SESUDAH</div>
                                            <div class="riwayat-foto-image-wrap" onclick="showLightbox('${safeAfterUrl}', 'Foto Sesudah - ${statusProgres}')" title="Klik untuk melihat foto lebih besar">
                                                <img src="${safeAfterUrl}" class="riwayat-foto-image" alt="Foto Sesudah" loading="lazy" onerror="this.onerror=null;this.src='${baseUrl}/images/no-image.png';">
                                                <div class="riwayat-foto-overlay"><i class="fas fa-search-plus"></i><span>Lihat Foto</span></div>
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }

                    html += `
                        <tr>
                            <td class="text-center fw-bold">${index + 1}</td>
                            <td class="text-center">${formatDate(m.tanggal_monitoring)}</td>
                            <td class="text-center">
                                <span class="badge bg-primary px-3 py-2">${m.progres}%</span>
                            </td>
                            <td>${escapeHtml(m.status_progres)}</td>
                            <td>${m.keterangan ? stripHtmlTags(m.keterangan).substring(0, 80) + (stripHtmlTags(m.keterangan).length > 80 ? '...' : '') : '-'}</td>
                            <td class="text-center">${danaHtml}</td>
                            <td class="text-center">${verifikasiBadge}</td>
                            <td class="text-center">
                                ${fotoHtml || '<span class="text-muted small"><i class="fas fa-image me-1"></i>Tidak ada foto</span>'}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    ${komentarHtml}
                                </div>
                            </td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                html += `
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada riwayat monitoring</p>
                    </div>
                `;
            }

            html += `
                    </div>
                </div>
            `;

            return html;
        }

        // ==========================================
        // HELPER FUNCTIONS
        // ==========================================
        function stripHtmlTags(html) {
            if (!html) return '';
            return html.replace(/<[^>]*>/g, '');
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ==========================================
        // TOAST NOTIFICATION
        // ==========================================
        function showToast(message, type = 'success') {
            var toast = $('#liveToast');
            var toastMessage = $('#toastMessage');

            toast.removeClass('toast-success toast-error');

            if (type === 'success') {
                toast.addClass('toast-success');
                toastMessage.html('<i class="fas fa-check-circle me-2"></i> ' + message);
            } else {
                toast.addClass('toast-error');
                toastMessage.html('<i class="fas fa-exclamation-circle me-2"></i> ' + message);
            }

            var bsToast = new bootstrap.Toast(toast[0], {
                delay: 3000,
                animation: true
            });
            bsToast.show();
        }

        // ==========================================
        // KEYBOARD SHORTCUTS
        // ==========================================
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                if ($('#komentarModal').hasClass('show')) {
                    $('#komentarModal').modal('hide');
                }
                if ($('#lihatKomentarModal').hasClass('show')) {
                    $('#lihatKomentarModal').modal('hide');
                }
                if ($('#lightboxModal').hasClass('show')) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('lightboxModal')).hide();
                }
                if ($('#detailRiwayatModal').hasClass('show')) {
                    $('#detailRiwayatModal').modal('hide');
                }
                if ($('#catatanPenolakanModal').hasClass('show')) {
                    $('#catatanPenolakanModal').modal('hide');
                }
            }
        });
    </script>
@endpush