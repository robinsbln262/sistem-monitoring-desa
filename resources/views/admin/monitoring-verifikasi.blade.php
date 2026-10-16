@extends('layouts.app')

@section('content')
<div class="table-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
            <i class="fas fa-shield-alt me-2 text-primary"></i> 
            Verifikasi Monitoring Progres
            <span class="badge bg-warning text-dark ms-2 rounded-pill">
                <i class="fas fa-clock me-1"></i> {{ $stats['pending'] }} Menunggu
            </span>
        </h5>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                <i class="fas fa-clock me-1"></i> Pending: {{ $stats['pending'] }}
            </span>
            <span class="badge bg-success rounded-pill px-3 py-2">
                <i class="fas fa-check-circle me-1"></i> Approved: {{ $stats['approved'] }}
            </span>
            <span class="badge bg-danger rounded-pill px-3 py-2">
                <i class="fas fa-times-circle me-1"></i> Rejected: {{ $stats['rejected'] }}
            </span>
            <span class="badge bg-secondary rounded-pill px-3 py-2">
                <i class="fas fa-list me-1"></i> Total: {{ $stats['total'] }}
            </span>
        </div>
    </div>

    @if($pendingMonitorings->count() > 0)
        <div class="alert alert-info border-0 rounded-3">
            <i class="fas fa-info-circle me-2"></i>
            <strong>{{ $stats['pending'] }}</strong> progres menunggu verifikasi Anda. 
            Silakan periksa setiap laporan dan setujui atau tolak dengan memberikan catatan.
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="verifikasiTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Proyek</th>
                        <th>Tanggal</th>
                        <th width="8%">Progres</th>
                        <th>Status Progres</th>
                        <th>Dana Terpakai</th>
                        <th>Keterangan</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingMonitorings as $index => $m)
                        <tr>
                            <td>{{ $pendingMonitorings->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $m->proyek->nama_pembangunan ?? 'Proyek' }}</strong>
                                <br>
                                <small class="text-muted">{{ $m->proyek->lokasi ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                                    {{ $m->progres }}%
                                </span>
                            </td>
                            <td>{{ $m->status_progres }}</td>
                            <td>
                                @if($m->dana_terpakai)
                                    <span class="text-success fw-semibold">
                                        Rp {{ number_format($m->dana_terpakai, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($m->keterangan)
                                    <span data-bs-toggle="tooltip" title="{{ strip_tags($m->keterangan) }}">
                                        {{ Str::limit(strip_tags($m->keterangan), 40) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <!-- Tombol Detail -->
                                    <button class="btn btn-sm btn-info btn-action" 
                                        onclick="lihatDetail({{ $m->id_monitoring }})" 
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Tombol Setujui -->
                                    <button class="btn btn-sm btn-success btn-action" 
                                        onclick="approveMonitoring({{ $m->id_monitoring }})" 
                                        title="Setujui Progres">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    
                                    <!-- Tombol Tolak -->
                                    <button class="btn btn-sm btn-danger btn-action" 
                                        onclick="rejectMonitoring({{ $m->id_monitoring }})" 
                                        title="Tolak Progres">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ========================================== --}}
        {{-- PAGINATION — VENDOR CUSTOM --}}
        {{-- ========================================== --}}
        @if($pendingMonitorings->hasPages())
            <div class="mt-4">
                {{ $pendingMonitorings->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h5 class="text-success">Semua Progres Sudah Diverifikasi!</h5>
            <p class="text-muted">Tidak ada progres yang menunggu verifikasi saat ini.</p>
            <a href="{{ route('admin.monitoring') }}" class="btn btn-primary rounded-3 px-4 mt-2">
                <i class="fas fa-list me-2"></i> Lihat Semua Monitoring
            </a>
        </div>
    @endif
</div>

<!-- ========================================== -->
<!-- MODAL DETAIL MONITORING -->
<!-- ========================================== -->
<div class="modal fade" id="detailModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-info-circle me-2"></i> Detail Monitoring
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL APPROVE -->
<!-- ========================================== -->
<div class="modal fade" id="approveModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-check-circle me-2"></i> Setujui Progres
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-thumbs-up fa-3x text-success mb-3"></i>
                        <h6>Apakah Anda yakin ingin menyetujui progres ini?</h6>
                        <p class="text-muted small">Progres akan langsung tampil di halaman publik setelah disetujui.</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea name="catatan_verifikasi" id="approveCatatan" class="form-control rounded-3" 
                                  rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success rounded-3 px-4" id="btnApprove">
                        <i class="fas fa-check me-2"></i> Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL REJECT -->
<!-- ========================================== -->
<div class="modal fade" id="rejectModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-times-circle me-2"></i> Tolak Progres
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-thumbs-down fa-3x text-danger mb-3"></i>
                        <h6>Apakah Anda yakin ingin menolak progres ini?</h6>
                        <p class="text-muted small">Progres yang ditolak tidak akan tampil di publik.</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-danger">
                            <i class="fas fa-pen me-1"></i> Catatan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea name="catatan_verifikasi" id="rejectCatatan" class="form-control rounded-3" 
                                  rows="4" placeholder="Berikan alasan penolakan dengan jelas..." required></textarea>
                        <small class="text-muted">Catatan akan dikirim ke rekanan sebagai feedback.</small>
                        <div class="invalid-feedback">Catatan penolakan wajib diisi minimal 5 karakter.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4" id="btnReject">
                        <i class="fas fa-times me-2"></i> Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="liveToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-action {
        padding: 6px 12px;
        margin: 2px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    #verifikasiTable td {
        vertical-align: middle;
    }
    .toast {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .toast-success {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
    }
    .toast-error {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%) !important;
    }
    .toast-warning {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%) !important;
    }
    .detail-item {
        padding: 10px 0;
        border-bottom: 1px solid #f0f2f5;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 600;
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 3px;
    }
    .detail-value {
        font-size: 1rem;
        color: #1e293b;
    }
    .foto-thumbnail {
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .foto-thumbnail:hover {
        transform: scale(1.02);
    }
</style>
@endpush

@push('scripts')
<script>
var baseUrl = '{{ url("/") }}';
var csrfToken = '{{ csrf_token() }}';

$(document).ready(function() {
    // ==========================================
    // DATATABLE DIHAPUS — Pakai server-side pagination Laravel
    // Pagination dari vendor custom (AppServiceProvider)
    // ==========================================

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ==========================================
    // HANDLE APPROVE
    // ==========================================
    $('#approveForm').on('submit', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('action');
        var catatan = $('#approveCatatan').val().trim();
        
        $('#btnApprove').prop('disabled', true);
        $('#btnApprove').html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: csrfToken,
                catatan_verifikasi: catatan
            },
            success: function(response) {
                $('#approveModal').modal('hide');
                showToast('Progres berhasil disetujui!', 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                var errorMsg = 'Gagal menyetujui progres. Silakan coba lagi.';
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.message) errorMsg = response.message;
                } catch(e) {}
                showToast(errorMsg, 'error');
                $('#btnApprove').prop('disabled', false);
                $('#btnApprove').html('<i class="fas fa-check me-2"></i> Ya, Setujui');
            }
        });
    });

    // ==========================================
    // HANDLE REJECT
    // ==========================================
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('action');
        var catatan = $('#rejectCatatan').val().trim();
        
        // Validasi catatan
        if (catatan.length < 5) {
            $('#rejectCatatan').addClass('is-invalid');
            showToast('Catatan penolakan minimal 5 karakter!', 'error');
            return;
        }
        $('#rejectCatatan').removeClass('is-invalid');
        
        $('#btnReject').prop('disabled', true);
        $('#btnReject').html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: csrfToken,
                catatan_verifikasi: catatan
            },
            success: function(response) {
                $('#rejectModal').modal('hide');
                showToast('Progres berhasil ditolak!', 'warning');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                var errorMsg = 'Gagal menolak progres. Silakan coba lagi.';
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.message) errorMsg = response.message;
                } catch(e) {}
                showToast(errorMsg, 'error');
                $('#btnReject').prop('disabled', false);
                $('#btnReject').html('<i class="fas fa-times me-2"></i> Ya, Tolak');
            }
        });
    });

    // Reset modal saat ditutup
    $('#approveModal').on('hidden.bs.modal', function() {
        $('#approveCatatan').val('');
        $('#btnApprove').prop('disabled', false);
        $('#btnApprove').html('<i class="fas fa-check me-2"></i> Ya, Setujui');
    });

    $('#rejectModal').on('hidden.bs.modal', function() {
        $('#rejectCatatan').val('');
        $('#rejectCatatan').removeClass('is-invalid');
        $('#btnReject').prop('disabled', false);
        $('#btnReject').html('<i class="fas fa-times me-2"></i> Ya, Tolak');
    });
});

// ==========================================
// APPROVE MONITORING
// ==========================================
function approveMonitoring(id) {
    $('#approveForm').attr('action', baseUrl + '/admin/monitoring/approve/' + id);
    $('#approveModal').modal('show');
}

// ==========================================
// REJECT MONITORING
// ==========================================
function rejectMonitoring(id) {
    $('#rejectForm').attr('action', baseUrl + '/admin/monitoring/reject/' + id);
    $('#rejectModal').modal('show');
}

// ==========================================
// LIHAT DETAIL
// ==========================================
function lihatDetail(id) {
    $('#detailContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    $('#detailModal').modal('show');

    $.ajax({
        url: baseUrl + '/admin/monitoring/verifikasi/' + id + '/data',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-building me-2 text-primary"></i> Nama Proyek</div>
                            <div class="detail-value">${escapeHtml(data.nama_pembangunan || '-')}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-calendar me-2 text-primary"></i> Tanggal Monitoring</div>
                            <div class="detail-value">${escapeHtml(data.tanggal_monitoring || '-')}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-percent me-2 text-primary"></i> Progres</div>
                            <div class="detail-value">
                                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">${data.progres}%</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-tasks me-2 text-primary"></i> Status Progres</div>
                            <div class="detail-value">${escapeHtml(data.status_progres || '-')}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-money-bill-wave me-2 text-success"></i> Dana Terpakai</div>
                            <div class="detail-value">
                                ${data.dana_terpakai ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.dana_terpakai) : '<span class="text-muted">-</span>'}
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-align-left me-2 text-primary"></i> Keterangan</div>
                            <div class="detail-value">${data.keterangan ? data.keterangan : '<span class="text-muted">-</span>'}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-clock me-2 text-primary"></i> Dilaporkan Pada</div>
                            <div class="detail-value">${escapeHtml(data.created_at || '-')}</div>
                        </div>
                        ${data.komentar_admin ? `
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-comment me-2 text-warning"></i> Komentar Admin</div>
                            <div class="detail-value">${escapeHtml(data.komentar_admin)}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-secondary text-white py-2">
                                <small><i class="fas fa-camera me-1"></i> SEBELUM (Before)</small>
                            </div>
                            ${data.foto_before ? 
                                `<img src="${resolveMonitoringPhotoUrl(data.foto_before)}" class="foto-thumbnail w-100" 
                                     onclick="window.open(resolveMonitoringPhotoUrl(data.foto_before), '_blank')" alt="Before"
                                     onerror="this.onerror=null; this.src='${baseUrl}/images/no-image.png';">` :
                                `<div class="text-center py-4 text-muted">Tidak ada foto</div>`
                            }
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-success text-white py-2">
                                <small><i class="fas fa-camera me-1"></i> SESUDAH (After)</small>
                            </div>
                            ${data.foto_after ? 
                                `<img src="${resolveMonitoringPhotoUrl(data.foto_after)}" class="foto-thumbnail w-100" 
                                     onclick="window.open(resolveMonitoringPhotoUrl(data.foto_after), '_blank')" alt="After"
                                     onerror="this.onerror=null; this.src='${baseUrl}/images/no-image.png';">` :
                                `<div class="text-center py-4 text-muted">Tidak ada foto</div>`
                            }
                        </div>
                    </div>
                </div>
            `;
            $('#detailContent').html(html);
        },
        error: function() {
            $('#detailContent').html(`
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    <h5 class="text-danger">Gagal Memuat Data</h5>
                    <p class="text-muted">Terjadi kesalahan saat memuat detail monitoring.</p>
                </div>
            `);
        }
    });
}


// ==========================================
// RESOLVER URL FOTO MONITORING
// Mendukung data lama + data baru public/uploads
// ==========================================
function resolveMonitoringPhotoUrl(path) {
    if (!path) return '';

    path = String(path).replace(/\\/g, '/').replace(/^\/+/, '');

    // URL absolut
    if (/^https?:\/\//i.test(path)) {
        return path;
    }

    // Data baru: uploads/monitoring/...
    if (path.indexOf('uploads/') === 0) {
        return baseUrl + '/' + path;
    }

    // Jika sudah menyimpan storage/...
    if (path.indexOf('storage/') === 0) {
        return baseUrl + '/' + path;
    }

    // Data lama dari Laravel public disk:
    // monitoring_before/... atau monitoring_after/...
    return baseUrl + '/storage/' + path;
}

// ==========================================
// TOAST NOTIFICATION
// ==========================================
function showToast(message, type = 'success') {
    var toast = $('#liveToast');
    var toastMessage = $('#toastMessage');
    
    toast.removeClass('toast-success toast-error toast-warning');
    
    if (type === 'success') {
        toast.addClass('toast-success');
        toastMessage.html('<i class="fas fa-check-circle me-2"></i> ' + message);
    } else if (type === 'warning') {
        toast.addClass('toast-warning');
        toastMessage.html('<i class="fas fa-exclamation-triangle me-2"></i> ' + message);
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
// HELPER FUNCTIONS
// ==========================================
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush