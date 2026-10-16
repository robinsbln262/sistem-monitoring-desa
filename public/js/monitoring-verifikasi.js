/**
 * monitoring-verifikasi.js
 * JavaScript untuk halaman verifikasi monitoring admin
 * Fitur: Approve, Reject, Toast Notification, Modal Konfirmasi
 */

// ==========================================
// VARIABLES
// ==========================================
var verifikasi = {
    baseUrl: window.location.origin,
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
};

// ==========================================
// TOAST NOTIFICATION
// ==========================================
function verifikasiToast(message, type = 'success') {
    // Cek apakah toast container sudah ada
    let toastContainer = document.getElementById('verifikasiToastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'verifikasiToastContainer';
        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Buat toast element
    const toastId = 'toast-' + Date.now();
    const colors = {
        success: 'bg-success',
        error: 'bg-danger',
        warning: 'bg-warning text-dark',
        info: 'bg-info text-dark'
    };

    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    const html = `
        <div id="${toastId}" class="toast align-items-center border-0 ${colors[type] || colors.success} show" 
             role="alert" aria-live="assertive" aria-atomic="true" style="min-width: 300px;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${icons[type] || icons.success} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', html);

    // Auto hide setelah 4 detik
    setTimeout(() => {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }, 4000);
}

// ==========================================
// LOADING BUTTON
// ==========================================
function setLoading(button, loading = true) {
    if (!button) return;
    const originalText = button.getAttribute('data-original-text') || button.innerHTML;
    
    if (loading) {
        button.setAttribute('data-original-text', originalText);
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
    } else {
        button.disabled = false;
        button.innerHTML = button.getAttribute('data-original-text') || originalText;
    }
}

// ==========================================
// APPROVE MONITORING
// ==========================================
function approveVerifikasi(id, button = null) {
    if (!id) {
        verifikasiToast('ID monitoring tidak ditemukan!', 'error');
        return;
    }

    // Jika ada button, set loading
    if (button) {
        setLoading(button, true);
    }

    // Siapkan data
    const formData = new FormData();
    formData.append('_token', verifikasi.csrfToken);
    
    // Cek apakah ada catatan dari modal
    const catatan = document.getElementById('approveCatatan')?.value || '';
    if (catatan) {
        formData.append('catatan_verifikasi', catatan);
    }

    fetch(`${verifikasi.baseUrl}/admin/monitoring/approve/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (button) setLoading(button, false);
        
        if (data.success || data.message) {
            verifikasiToast(data.message || 'Monitoring berhasil disetujui!', 'success');
            // Tutup modal approve
            const approveModal = document.getElementById('approveModal');
            if (approveModal) {
                const modal = bootstrap.Modal.getInstance(approveModal);
                if (modal) modal.hide();
            }
            // Reload setelah 1.5 detik
            setTimeout(() => location.reload(), 1500);
        } else {
            verifikasiToast(data.error || 'Gagal menyetujui monitoring.', 'error');
        }
    })
    .catch(error => {
        if (button) setLoading(button, false);
        console.error('Error:', error);
        verifikasiToast('Terjadi kesalahan pada server.', 'error');
    });
}

// ==========================================
// REJECT MONITORING
// ==========================================
function rejectVerifikasi(id, button = null) {
    if (!id) {
        verifikasiToast('ID monitoring tidak ditemukan!', 'error');
        return;
    }

    // Validasi catatan
    const catatan = document.getElementById('rejectCatatan')?.value?.trim();
    if (!catatan || catatan.length < 5) {
        const rejectCatatan = document.getElementById('rejectCatatan');
        if (rejectCatatan) {
            rejectCatatan.classList.add('is-invalid');
            rejectCatatan.focus();
        }
        verifikasiToast('Catatan penolakan wajib diisi minimal 5 karakter!', 'error');
        return;
    }

    // Jika ada button, set loading
    if (button) {
        setLoading(button, true);
    }

    // Siapkan data
    const formData = new FormData();
    formData.append('_token', verifikasi.csrfToken);
    formData.append('catatan_verifikasi', catatan);

    fetch(`${verifikasi.baseUrl}/admin/monitoring/reject/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (button) setLoading(button, false);
        
        if (data.success || data.message) {
            verifikasiToast(data.message || 'Monitoring berhasil ditolak!', 'warning');
            // Tutup modal reject
            const rejectModal = document.getElementById('rejectModal');
            if (rejectModal) {
                const modal = bootstrap.Modal.getInstance(rejectModal);
                if (modal) modal.hide();
            }
            // Reload setelah 1.5 detik
            setTimeout(() => location.reload(), 1500);
        } else {
            verifikasiToast(data.error || 'Gagal menolak monitoring.', 'error');
        }
    })
    .catch(error => {
        if (button) setLoading(button, false);
        console.error('Error:', error);
        verifikasiToast('Terjadi kesalahan pada server.', 'error');
    });
}

// ==========================================
// SHOW APPROVE MODAL
// ==========================================
function showApproveModal(id) {
    if (!id) {
        verifikasiToast('ID tidak ditemukan!', 'error');
        return;
    }

    // Set action form
    const form = document.getElementById('approveForm');
    if (form) {
        form.action = `${verifikasi.baseUrl}/admin/monitoring/approve/${id}`;
    }

    // Reset catatan
    const catatan = document.getElementById('approveCatatan');
    if (catatan) catatan.value = '';

    // Tampilkan modal
    const modal = document.getElementById('approveModal');
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        // Fokus ke textarea setelah modal terbuka
        setTimeout(() => {
            if (catatan) catatan.focus();
        }, 500);
    }
}

// ==========================================
// SHOW REJECT MODAL
// ==========================================
function showRejectModal(id) {
    if (!id) {
        verifikasiToast('ID tidak ditemukan!', 'error');
        return;
    }

    // Set action form
    const form = document.getElementById('rejectForm');
    if (form) {
        form.action = `${verifikasi.baseUrl}/admin/monitoring/reject/${id}`;
    }

    // Reset catatan
    const catatan = document.getElementById('rejectCatatan');
    if (catatan) {
        catatan.value = '';
        catatan.classList.remove('is-invalid');
    }

    // Tampilkan modal
    const modal = document.getElementById('rejectModal');
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        
        // Fokus ke textarea setelah modal terbuka
        setTimeout(() => {
            if (catatan) catatan.focus();
        }, 500);
    }
}

// ==========================================
// LOAD DETAIL MONITORING (AJAX)
// ==========================================
function loadDetailMonitoring(id) {
    if (!id) {
        verifikasiToast('ID tidak ditemukan!', 'error');
        return;
    }

    const content = document.getElementById('detailContent');
    if (content) {
        content.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat detail monitoring...</p>
            </div>
        `;
    }

    // Tampilkan modal
    const modal = document.getElementById('detailModal');
    if (modal) {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    fetch(`${verifikasi.baseUrl}/admin/monitoring/verifikasi/${id}/data`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        if (content) {
            content.innerHTML = buildDetailHtml(data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (content) {
            content.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    <h5 class="text-danger">Gagal Memuat Data</h5>
                    <p class="text-muted">${error.message || 'Terjadi kesalahan saat memuat detail.'}</p>
                    <button class="btn btn-primary mt-3" onclick="loadDetailMonitoring(${id})">
                        <i class="fas fa-sync-alt me-2"></i> Coba Lagi
                    </button>
                </div>
            `;
        }
    });
}

// ==========================================
// BUILD DETAIL HTML
// ==========================================
function buildDetailHtml(data) {
    const danaTerpakai = data.dana_terpakai 
        ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.dana_terpakai) 
        : '<span class="text-muted">-</span>';

    const fotoBeforeHtml = data.foto_before 
        ? `<img src="${verifikasi.baseUrl}/storage/${data.foto_before}" class="foto-thumbnail w-100" 
             onclick="window.open('${verifikasi.baseUrl}/storage/${data.foto_before}', '_blank')" alt="Before">`
        : '<div class="text-center py-4 text-muted">Tidak ada foto</div>';

    const fotoAfterHtml = data.foto_after 
        ? `<img src="${verifikasi.baseUrl}/storage/${data.foto_after}" class="foto-thumbnail w-100" 
             onclick="window.open('${verifikasi.baseUrl}/storage/${data.foto_after}', '_blank')" alt="After">`
        : '<div class="text-center py-4 text-muted">Tidak ada foto</div>';

    return `
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
                    <div class="detail-value">${danaTerpakai}</div>
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
                    ${fotoBeforeHtml}
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white py-2">
                        <small><i class="fas fa-camera me-1"></i> SESUDAH (After)</small>
                    </div>
                    ${fotoAfterHtml}
                </div>
            </div>
        </div>
    `;
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

// ==========================================
// KEYBOARD SHORTCUTS
// ==========================================
document.addEventListener('keydown', function(e) {
    // ESC to close modals
    if (e.key === 'Escape') {
        const modals = ['komentarModal', 'lihatKomentarModal', 'lihatFotoModal', 'fullImageModal', 'detailRiwayatModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (modal && modal.classList.contains('show')) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            }
        });
    }
});

// ==========================================
// INITIALIZATION - Auto bind event listeners
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    // Bind approve form submit
    const approveForm = document.getElementById('approveForm');
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = this.action;
            const id = action.split('/').pop();
            const button = this.querySelector('button[type="submit"]');
            approveVerifikasi(id, button);
        });
    }

    // Bind reject form submit
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = this.action;
            const id = action.split('/').pop();
            const button = this.querySelector('button[type="submit"]');
            rejectVerifikasi(id, button);
        });
    }

    // Bind reject catatan validation on input
    const rejectCatatan = document.getElementById('rejectCatatan');
    if (rejectCatatan) {
        rejectCatatan.addEventListener('input', function() {
            if (this.value.length >= 5) {
                this.classList.remove('is-invalid');
            }
        });
    }

    console.log('✅ Monitoring Verifikasi JS loaded successfully.');
});