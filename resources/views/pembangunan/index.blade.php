@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="fas fa-building me-2 text-primary"></i>
                Data Pembangunan Desa
            </h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus me-1"></i> Tambah Pembangunan
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pembangunan</th>
                        <th>Lokasi</th>
                        <th>Tahun</th>
                        <th>Anggaran</th>
                        <th width="10%">Status</th>
                        <th width="15%">Progres</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembangunans as $index => $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $p->nama_pembangunan }}</td>
                            <td>{{ $p->lokasi }}</td>
                            <td>{{ $p->tahun_anggaran }}</td>
                            <td>Rp {{ number_format($p->anggaran, 0, ',', '.') }}</td>
                            <td>
                                @if($p->status == 'direncanakan')
                                    <span class="badge bg-secondary">Direncanakan</span>
                                @elseif($p->status == 'berjalan')
                                    <span class="badge bg-warning text-dark">Berjalan</span>
                                @else
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                        <div class="progress-bar {{ $p->progress == 100 ? 'bg-success' : 'bg-primary' }}"
                                            style="width: {{ $p->progress }}%"></div>
                                    </div>
                                    <span class="small">{{ $p->progress }}%</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-action" onclick="showDetail({{ $p->id_pembangunan }})"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning btn-action" onclick="editData({{ $p->id_pembangunan }})"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-action" onclick="deleteData({{ $p->id_pembangunan }})"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== MODAL TAMBAH ==================== -->
    <div class="modal fade" id="tambahModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Tambah Data Pembangunan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pembangunan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pembangunan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pembangunan" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" name="lokasi" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tahun Anggaran <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_anggaran" class="form-control" value="{{ date('Y') }}"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sumber Dana <span class="text-danger">*</span></label>
                                <input type="text" name="sumber_dana" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Anggaran (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="anggaran" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="direncanakan">Direncanakan</option>
                                    <option value="berjalan">Sedang Berjalan</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penanggung Jawab / Mandor</label>
                                <input type="text" name="mandor" class="form-control" placeholder="Contoh: Bpk. Jhon">
                                <small class="text-muted">Opsional: Nama mandor atau penanggung jawab pembangunan</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL EDIT ==================== -->
    <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Data Pembangunan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pembangunan</label>
                                <input type="text" name="nama_pembangunan" id="edit_nama" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lokasi</label>
                                <input type="text" name="lokasi" id="edit_lokasi" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tahun Anggaran</label>
                                <input type="number" name="tahun_anggaran" id="edit_tahun" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="edit_mulai" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="edit_selesai" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sumber Dana</label>
                                <input type="text" name="sumber_dana" id="edit_sumber" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Anggaran (Rp)</label>
                                <input type="number" name="anggaran" id="edit_anggaran" class="form-control" step="0.01"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="direncanakan">Direncanakan</option>
                                    <option value="berjalan">Sedang Berjalan</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penanggung Jawab / Mandor</label>
                                <input type="text" name="mandor" id="edit_mandor" class="form-control"
                                    placeholder="Contoh: Bpk. Jhon">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL DETAIL ==================== -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i> Detail Pembangunan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat data detail...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL HAPUS ==================== -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                        <small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Base URL untuk AJAX
        var baseUrl = '{{ url("/") }}';

        $(document).ready(function () {
            // Initialize DataTable
            $('#dataTable').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                pageLength: 10,
                responsive: true
            });
        });

        // Function Edit Data
        function editData(id) {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            $.ajax({
                url: baseUrl + '/pembangunan/' + id + '/edit',
                type: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function (data) {
                    $('#edit_nama').val(data.nama_pembangunan);
                    $('#edit_lokasi').val(data.lokasi);
                    $('#edit_tahun').val(data.tahun_anggaran);
                    $('#edit_sumber').val(data.sumber_dana);

                    function formatDateForInput(dateString) {
                        if (!dateString) return '';
                        let date = new Date(dateString);
                        if (isNaN(date.getTime())) {
                            let clean = dateString.substring(0, 10);
                            date = new Date(clean);
                        }
                        if (isNaN(date.getTime())) return '';
                        let year = date.getFullYear();
                        let month = ('0' + (date.getMonth() + 1)).slice(-2);
                        let day = ('0' + date.getDate()).slice(-2);
                        return year + '-' + month + '-' + day;
                    }

                    $('#edit_mulai').val(formatDateForInput(data.tanggal_mulai));
                    $('#edit_selesai').val(formatDateForInput(data.tanggal_selesai));
                    $('#edit_anggaran').val(data.anggaran);
                    $('#edit_status').val(data.status);
                    $('#edit_mandor').val(data.mandor || '');
                    $('#editForm').attr('action', baseUrl + '/pembangunan/' + id);
                    $('#editModal').modal('show');
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat memuat data. Silakan coba lagi.',
                        confirmButtonColor: '#1e3c72'
                    });
                },
                complete: function () {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            });
        }

        // Function Show Detail
        function showDetail(id) {
            $('#detailContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat data detail pembangunan...</p>
                </div>
            `);
            $('#detailModal').modal('show');

            $.ajax({
                url: baseUrl + '/pembangunan/' + id,
                type: 'GET',
                dataType: 'json',
                timeout: 15000,
                success: function (data) {
                    let statusBadge = '';
                    if (data.status == 'direncanakan') {
                        statusBadge = '<span class="badge bg-secondary px-3 py-2"><i class="fas fa-clock me-1"></i> Direncanakan</span>';
                    } else if (data.status == 'berjalan') {
                        statusBadge = '<span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-spinner fa-pulse me-1"></i> Berjalan</span>';
                    } else {
                        statusBadge = '<span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Selesai</span>';
                    }

                    let monitoringHtml = '';
                    if (data.monitorings && data.monitorings.length > 0) {
                        data.monitorings.forEach(m => {
                            monitoringHtml += `
                                <tr>
                                    <td>${formatDate(m.tanggal_monitoring)}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: ${m.progres}%"></div>
                                            </div>
                                            <span class="small fw-bold">${m.progres}%</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">${m.status_progres}</span></td>
                                    <td>${m.keterangan || '-'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        monitoringHtml = '<tr><td colspan="4" class="text-center py-4 text-muted"><i class="fas fa-chart-line me-2"></i>Belum ada data monitoring</th></tr>';
                    }

                    // DOKUMENTASI - 1 KETERANGAN UNTUK SEMUA FOTO (SUMMERNOTE DIBERSIHKAN)
                    let dokumentasiHtml = '';
                    if (data.dokumentasis && data.dokumentasis.length > 0) {
                        // Ambil keterangan dari foto pertama dan bersihkan tag HTML
                        let keteranganUtama = stripHtmlTags(data.dokumentasis[0].keterangan) || 'Dokumentasi Kegiatan';
                        let tanggalUpload = data.dokumentasis[0].tanggal_upload;

                        // Kumpulkan semua foto
                        let fotoHtml = '';
                        data.dokumentasis.forEach(dok => {
                            fotoHtml += `
                                <div class="col-md-3 mb-3">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <img src="${baseUrl}/storage/${dok.foto}" class="card-img-top" style="height: 150px; object-fit: cover; cursor: pointer;" 
                                             onclick="window.open('${baseUrl}/storage/${dok.foto}', '_blank')" alt="Dokumentasi">
                                    </div>
                                </div>
                            `;
                        });

                        dokumentasiHtml = `
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-header bg-light">
                                            <strong><i class="fas fa-camera text-primary me-2"></i>Dokumentasi Kegiatan</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3 p-3 bg-light rounded-3">
                                                <h6 class="mb-1 fw-bold text-dark">
                                                    <i class="fas fa-info-circle text-primary me-2"></i>${keteranganUtama}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i> ${formatDate(tanggalUpload)} &bull; 
                                                    <i class="fas fa-images me-1"></i> ${data.dokumentasis.length} Foto
                                                </small>
                                            </div>
                                            <div class="row">
                                                ${fotoHtml}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3 border-0">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-info-circle text-primary me-2"></i>Informasi Pembangunan</strong>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless mb-0">
                                            <tr><td width="40%" class="text-muted">Nama Pembangunan</td><td class="fw-bold">${escapeHtml(data.nama_pembangunan)}</td></tr>
                                            <tr><td class="text-muted">Lokasi</td><td>${escapeHtml(data.lokasi)}</td></tr>
                                            <tr><td class="text-muted">Tahun Anggaran</td><td>${data.tahun_anggaran}</td></tr>
                                            <tr><td class="text-muted">Sumber Dana</td><td>${escapeHtml(data.sumber_dana)}</td></tr>
                                            <tr><td class="text-muted">Tanggal Mulai</td><td>${formatDate(data.tanggal_mulai)}</td></tr>
                                            <tr><td class="text-muted">Tanggal Selesai</td><td>${formatDate(data.tanggal_selesai)}</td></tr>
                                            <tr><td class="text-muted">Anggaran</td><td class="fw-bold text-success">Rp ${new Intl.NumberFormat('id-ID').format(data.anggaran)}</td></tr>
                                            <tr><td class="text-muted">Status</td><td>${statusBadge}</td></tr>
                                            <tr><td class="text-muted">Penanggung Jawab / Mandor</td><td class="fw-bold">${data.mandor || '-'}</td></tr>
                                            <tr><td class="text-muted">Dibuat Oleh</td><td>${data.user ? escapeHtml(data.user.nama_user) : '-'}</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-light">
                                        <strong><i class="fas fa-chart-line text-primary me-2"></i>Riwayat Monitoring</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr><th>Tanggal</th><th>Progres</th><th>Status</th><th>Keterangan</th></tr>
                                                </thead>
                                                <tbody>
                                                    ${monitoringHtml}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ${dokumentasiHtml}
                    `;

                    $('#detailContent').html(html);
                },
                error: function (xhr, status, error) {
                    let errorMsg = 'Gagal memuat data detail. Silakan coba lagi.';
                    if (xhr.status === 404) errorMsg = 'Data tidak ditemukan.';
                    else if (xhr.status === 500) errorMsg = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';

                    $('#detailContent').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                            <h5 class="text-danger">Gagal Memuat Data</h5>
                            <p class="text-muted">${errorMsg}</p>
                            <button class="btn btn-primary mt-2" onclick="showDetail(${id})">
                                <i class="fas fa-sync-alt me-2"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                }
            });
        }

        // Function Delete Data
        function deleteData(id) {
            $('#deleteForm').attr('action', baseUrl + '/pembangunan/' + id);
            $('#deleteModal').modal('show');
        }

        // Helper function to format date
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

        // Helper function to strip HTML tags
        function stripHtmlTags(html) {
            if (!html) return '';
            return html.replace(/<[^>]*>/g, '');
        }

        // Helper function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
@endpush