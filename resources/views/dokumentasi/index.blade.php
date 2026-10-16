@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="fas fa-camera me-2 text-primary"></i> Galeri Dokumentasi Pembangunan</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus me-1"></i> Upload Dokumentasi
            </button>
        </div>

        <!-- Stats Summary -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">TOTAL GRUP</small>
                                <h3 class="mb-0 mt-1">{{ $dokumentasis->total() }}</h3>
                            </div>
                            <i class="fas fa-images fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">TOTAL PEMBANGUNAN</small>
                                <h3 class="mb-0 mt-1">{{ $pembangunans->count() }}</h3>
                            </div>
                            <i class="fas fa-building fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">BULAN INI</small>
                                <h3 class="mb-0 mt-1">{{ $dokumentasiBulanIni }}</h3>
                            </div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-dark-50">RATA-RATA</small>
                                <h3 class="mb-0 mt-1">
                                    {{ round($dokumentasis->count() / max($pembangunans->count(), 1), 1) }}
                                </h3>
                            </div>
                            <i class="fas fa-chart-line fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="row" id="dokumentasiContainer">
            @forelse($dokumentasis as $d)
                <div class="col-md-3 mb-4 dokumentasi-item" data-group="{{ $d->group_id }}">
                    <div class="card dokumentasi-card h-100 border-0 shadow-sm rounded-4">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $d->foto) }}" class="card-img-top rounded-top-4"
                                alt="Dokumentasi" style="height: 220px; width: 100%; object-fit: cover; cursor: pointer;"
                                onclick="showLightbox('{{ asset('storage/' . $d->foto) }}', '{{ addslashes(strip_tags($d->keterangan)) }}')">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-primary rounded-pill px-2 py-1">
                                    <i class="fas fa-calendar me-1"></i> {{ date('d/m/Y', strtotime($d->tanggal_upload)) }}
                                </span>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-2"
                                style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                <!-- ✅ GANTI $d->pembangunan -> $d->proyek -->
                                <h6 class="text-white mb-0">{{ Str::limit($d->proyek->nama_pembangunan ?? '-', 35) }}</h6>
                                <small class="text-white-50"><i class="fas fa-map-marker-alt me-1"></i>
                                    {{ Str::limit($d->proyek->lokasi ?? '-', 25) }}</small>
                            </div>
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="badge bg-dark rounded-pill px-2 py-1">
                                    <i class="fas fa-images me-1"></i> Multiple
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <p class="card-text small text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ Str::limit(strip_tags($d->keterangan), 50) ?: 'Tidak ada keterangan' }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-0 d-flex justify-content-between pb-3">
                            <button class="btn btn-sm btn-info rounded-3" onclick="showDetail('{{ $d->group_id }}')"
                                title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning rounded-3" onclick="editData('{{ $d->group_id }}')"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger rounded-3" onclick="deleteData('{{ $d->group_id }}')"
                                title="Hapus Semua">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state text-center py-5">
                        <i class="fas fa-camera fa-4x text-muted mb-3"></i>
                        <h5>Belum Ada Dokumentasi</h5>
                        <p class="text-muted">Silakan upload dokumentasi pembangunan terlebih dahulu.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Modal Tambah dengan Multiple Upload -->
        <div class="modal fade" id="tambahModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-0"
                        style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                        <h5 class="modal-title text-white"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Dokumentasi
                            Baru
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('dokumentasi.store') }}" method="POST" enctype="multipart/form-data"
                        id="formTambah">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Pilih Pembangunan <span
                                            class="text-danger">*</span></label>
                                    <select name="id_pembangunan" class="form-select rounded-3" required>
                                        <option value="">-- Pilih Pembangunan --</option>
                                        @foreach ($pembangunans as $p)
                                            <option value="{{ $p->id_pembangunan }}">{{ $p->nama_pembangunan }} -
                                                {{ $p->lokasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Upload <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_upload" class="form-control rounded-3"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Upload Foto (Bisa pilih banyak) <span
                                            class="text-danger">*</span></label>
                                    <div class="upload-area" id="uploadArea">
                                        <input type="file" name="fotos[]" id="fotoInput" accept="image/*" multiple
                                            style="display: none;">
                                        <div class="upload-placeholder text-center py-5 rounded-4"
                                            style="border: 2px dashed #cbd5e1; cursor: pointer; transition: all 0.3s;">
                                            <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-3"></i>
                                            <p class="mb-1 text-muted">Klik untuk pilih beberapa file gambar</p>
                                            <small class="text-muted">Format: JPG, JPEG, PNG. Maks 5MB per file. Bisa pilih
                                                banyak</small>
                                        </div>
                                        <div id="imagePreviewContainer" class="mt-3 row g-2" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Keterangan (untuk semua foto)</label>
                                    <textarea name="keterangan" id="summernoteKeterangan" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 pb-4 px-4">
                            <button type="button" class="btn btn-secondary rounded-3 px-4"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-0"
                        style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                        <h5 class="modal-title text-white"><i class="fas fa-edit me-2"></i> Edit Dokumentasi</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Pilih Pembangunan</label>
                                    <select name="id_pembangunan" id="edit_id_pembangunan" class="form-select rounded-3"
                                        required>
                                        @foreach ($pembangunans as $p)
                                            <option value="{{ $p->id_pembangunan }}">{{ $p->nama_pembangunan }} -
                                                {{ $p->lokasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tanggal Upload</label>
                                    <input type="date" name="tanggal_upload" id="edit_tanggal"
                                        class="form-control rounded-3" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Foto Saat Ini</label>
                                    <div id="existingPhotosContainer" class="row g-2 mb-3"></div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Tambah Foto Baru (opsional, bisa pilih
                                        banyak)</label>
                                    <div class="upload-area" id="editUploadArea">
                                        <input type="file" name="fotos[]" id="editFotoInput" accept="image/*"
                                            multiple style="display: none;">
                                        <div class="upload-placeholder text-center py-4 rounded-4"
                                            style="border: 2px dashed #cbd5e1; cursor: pointer; transition: all 0.3s;">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                            <p class="mb-0 text-muted">Klik untuk tambah foto baru (bisa pilih banyak)</p>
                                            <small class="text-muted">Format: JPG, JPEG, PNG. Maks 5MB per file</small>
                                        </div>
                                        <div id="editImagePreviewContainer" class="mt-3 row g-2" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Keterangan (untuk semua foto)</label>
                                    <textarea name="keterangan" id="summernoteKeteranganEdit" class="form-control" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 pb-4 px-4">
                            <button type="button" class="btn btn-secondary rounded-3 px-4"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-3 px-4">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Detail -->
        <div class="modal fade" id="detailModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-0"
                        style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                        <h5 class="modal-title text-white"><i class="fas fa-info-circle me-2"></i> Detail Dokumentasi</h5>
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

        <!-- Modal Hapus Group -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded-4">
                    <div class="modal-header bg-danger text-white border-0">
                        <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                        <p>Apakah Anda yakin ingin menghapus semua foto dalam grup ini?</p>
                        <small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary rounded-3 px-4"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger rounded-3 px-4">Hapus Semua</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div class="modal fade" id="lightboxModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-body text-center p-0">
                        <button type="button"
                            class="btn btn-light btn-lg rounded-circle position-absolute top-0 end-0 m-3"
                            data-bs-dismiss="modal" style="z-index: 1060; width: 45px; height: 45px;">
                            <i class="fas fa-times"></i>
                        </button>
                        <img id="lightboxImage" src="" alt="Full Size"
                            style="max-width: 95%; max-height: 85vh; border-radius: 16px; box-shadow: 0 25px 50px rgba(0,0,0,0.3);">
                        <p id="lightboxCaption" class="text-white mt-3 fs-5"></p>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <link href="{{ asset('summernote/summernote-bs5.min.css') }}" rel="stylesheet">
        <style>
            .dokumentasi-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .dokumentasi-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
            }

            .upload-placeholder:hover {
                border-color: #1e3c72 !important;
                background: #f8fafc;
            }

            .note-editor.note-frame {
                border-radius: 12px;
                border: 1px solid #e5e7eb;
            }

            .note-editor.note-frame .note-editing-area .note-editable {
                background: white;
                min-height: 120px;
            }

            .note-toolbar {
                background: #f8f9fa;
                border-radius: 12px 12px 0 0;
                padding: 8px !important;
            }

            .dokumentasi-item {
                animation: fadeInUp 0.5s ease forwards;
                opacity: 0;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .preview-thumb {
                position: relative;
                display: inline-block;
            }

            .preview-thumb img {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 8px;
            }

            .preview-thumb .remove-preview {
                position: absolute;
                top: -8px;
                right: -8px;
                width: 22px;
                height: 22px;
                border-radius: 50%;
                background: red;
                color: white;
                border: none;
                font-size: 12px;
                cursor: pointer;
            }

            /* Style untuk modal detail */
            .info-detail-item {
                margin-bottom: 16px;
            }

            .info-detail-label {
                font-size: 11px;
                color: #64748b;
                margin-bottom: 4px;
                font-weight: 500;
            }

            .info-detail-value {
                font-size: 14px;
                color: #1e293b;
                font-weight: 500;
            }

            .detail-keterangan-content {
                font-size: 14px;
                line-height: 1.6;
                color: #334155;
            }

            .detail-keterangan-content p {
                margin-bottom: 12px;
            }

            .detail-keterangan-content ul,
            .detail-keterangan-content ol {
                margin: 10px 0;
                padding-left: 20px;
            }

            .detail-keterangan-content li {
                margin-bottom: 5px;
            }

            .detail-keterangan-content strong,
            .detail-keterangan-content b {
                color: #1e3c72;
            }

            .detail-keterangan-content img {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
                margin: 10px 0;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('summernote/summernote-bs5.min.js') }}"></script>
        <script src="{{ asset('summernote/lang/summernote-id-ID.min.js') }}"></script>

        <script>
            // Base URL untuk AJAX (berfungsi di XAMPP & artisan serve)
            var baseUrl = '{{ url('/') }}';

            let selectedFiles = [];
            let editSelectedFiles = [];
            let currentGroupId = null;
            let existingFotos = [];

            $(document).ready(function() {
                // Run animation
                $('.dokumentasi-item').each(function(index) {
                    $(this).css('animation-delay', (index * 0.05) + 's');
                });

                // Summernote untuk modal tambah
                if ($('#summernoteKeterangan').length && !$('#summernoteKeterangan').hasClass('note-editor')) {
                    $('#summernoteKeterangan').summernote({
                        placeholder: 'Tulis deskripsi dokumentasi di sini...',
                        tabsize: 2,
                        height: 150,
                        lang: 'id-ID',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['insert', ['link']],
                            ['view', ['codeview', 'help']]
                        ]
                    });
                }

                // Upload area click handler
                $('.upload-placeholder').on('click', function() {
                    $(this).siblings('input[type="file"]').click();
                });

                // Preview image for tambah modal
                $('#fotoInput').on('change', function(e) {
                    const files = Array.from(e.target.files);
                    selectedFiles = files;
                    updateImagePreview();
                });

                // Preview image for edit modal
                $('#editFotoInput').on('change', function(e) {
                    const files = Array.from(e.target.files);
                    editSelectedFiles = files;
                    updateEditImagePreview();
                });

                // Destroy summernote when modal tambah closed
                $('#tambahModal').on('hidden.bs.modal', function() {
                    if ($('#summernoteKeterangan').hasClass('note-editor')) {
                        $('#summernoteKeterangan').summernote('destroy');
                    }
                    $('#fotoInput').val('');
                    selectedFiles = [];
                    $('#imagePreviewContainer').empty().hide();
                    $('.upload-placeholder').show();
                });

                // Destroy summernote when modal edit closed
                $('#editModal').on('hidden.bs.modal', function() {
                    if ($('#summernoteKeteranganEdit').hasClass('note-editor')) {
                        $('#summernoteKeteranganEdit').summernote('destroy');
                    }
                    editSelectedFiles = [];
                    $('#editImagePreviewContainer').empty().hide();
                    $('#editUploadArea .upload-placeholder').show();
                });

                // Reinitialize summernote when modal tambah shown
                $('#tambahModal').on('shown.bs.modal', function() {
                    if (!$('#summernoteKeterangan').hasClass('note-editor')) {
                        $('#summernoteKeterangan').summernote({
                            placeholder: 'Tulis deskripsi dokumentasi di sini...',
                            tabsize: 2,
                            height: 150,
                            lang: 'id-ID',
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'italic', 'underline', 'clear']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['insert', ['link']],
                                ['view', ['codeview', 'help']]
                            ]
                        });
                    }
                });
            });

            function updateImagePreview() {
                const container = $('#imagePreviewContainer');
                container.empty();

                if (selectedFiles.length > 0) {
                    container.show();
                    selectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewHtml = `
                                <div class="col-auto position-relative preview-thumb" data-index="${index}">
                                    <img src="${e.target.result}" alt="Preview" class="border rounded-3">
                                    <button type="button" class="btn btn-danger btn-sm remove-preview" onclick="removeSelectedFile(${index})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                            container.append(previewHtml);
                        }
                        reader.readAsDataURL(file);
                    });
                    $('.upload-placeholder').hide();
                } else {
                    container.hide();
                    $('.upload-placeholder').show();
                }
            }

            function updateEditImagePreview() {
                const container = $('#editImagePreviewContainer');
                container.empty();

                if (editSelectedFiles.length > 0) {
                    container.show();
                    editSelectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewHtml = `
                                <div class="col-auto position-relative preview-thumb" data-index="${index}">
                                    <img src="${e.target.result}" alt="Preview" class="border rounded-3">
                                    <button type="button" class="btn btn-danger btn-sm remove-preview" onclick="removeEditSelectedFile(${index})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                            container.append(previewHtml);
                        }
                        reader.readAsDataURL(file);
                    });
                    $('#editUploadArea .upload-placeholder').hide();
                } else {
                    container.hide();
                    $('#editUploadArea .upload-placeholder').show();
                }
            }

            window.removeSelectedFile = function(index) {
                selectedFiles.splice(index, 1);
                updateImagePreview();
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                document.getElementById('fotoInput').files = dataTransfer.files;
            }

            window.removeEditSelectedFile = function(index) {
                editSelectedFiles.splice(index, 1);
                updateEditImagePreview();
                const dataTransfer = new DataTransfer();
                editSelectedFiles.forEach(file => dataTransfer.items.add(file));
                document.getElementById('editFotoInput').files = dataTransfer.files;
            }

            function showLightbox(imageUrl, caption) {
                $('#lightboxImage').attr('src', imageUrl);
                $('#lightboxCaption').text(caption || 'Dokumentasi Pembangunan');
                $('#lightboxModal').modal('show');
            }

            function stripHtmlTags(html) {
                if (!html) return '-';
                return html.replace(/<[^>]*>/g, '');
            }

            // ========== EDIT DATA FUNCTION - FIXED with BASE URL ==========
            function editData(groupId) {
                currentGroupId = groupId;
                const btn = event.currentTarget;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;

                $.ajax({
                    url: baseUrl + '/dokumentasi/group/' + groupId + '/get-data',
                    type: 'GET',
                    dataType: 'json',
                    timeout: 10000,
                    success: function(data) {
                        console.log('Data edit diterima:', data);

                        // Isi form edit
                        $('#edit_id_pembangunan').val(data.id_pembangunan);

                        // Set tanggal upload
                        if (data.tanggal_upload) {
                            let tanggal = data.tanggal_upload;
                            if (tanggal.match(/^\d{4}-\d{2}-\d{2}$/)) {
                                $('#edit_tanggal').val(tanggal);
                            } else {
                                let dateObj = new Date(tanggal);
                                if (!isNaN(dateObj.getTime())) {
                                    $('#edit_tanggal').val(dateObj.toISOString().split('T')[0]);
                                }
                            }
                        }

                        $('#editForm').attr('action', baseUrl + '/dokumentasi/' + data.dokumentasi_ids[0]);

                        // Simpan data keterangan untuk nanti
                        window.editKeteranganData = data.keterangan || '';
                        existingFotos = data.fotos || [];
                        displayExistingPhotos(existingFotos);

                        // Reset preview
                        editSelectedFiles = [];
                        $('#editImagePreviewContainer').empty().hide();
                        $('#editUploadArea .upload-placeholder').show();
                        $('#editFotoInput').val('');

                        // Tampilkan modal
                        $('#editModal').modal('show');

                        // Inisialisasi Summernote dan set konten setelah modal terbuka
                        setTimeout(function() {
                            // Destroy existing summernote
                            if ($('#summernoteKeteranganEdit').hasClass('note-editor')) {
                                $('#summernoteKeteranganEdit').summernote('destroy');
                            }

                            // Inisialisasi summernote baru
                            $('#summernoteKeteranganEdit').summernote({
                                placeholder: 'Tulis deskripsi dokumentasi di sini...',
                                tabsize: 2,
                                height: 200,
                                lang: 'id-ID',
                                toolbar: [
                                    ['style', ['style']],
                                    ['font', ['bold', 'italic', 'underline', 'clear']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    ['insert', ['link', 'picture']],
                                    ['view', ['fullscreen', 'codeview', 'help']]
                                ]
                            });

                            // Set konten summernote
                            $('#summernoteKeteranganEdit').summernote('code', window.editKeteranganData ||
                                '');
                        }, 300);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: 'Terjadi kesalahan saat memuat data. Silakan coba lagi.',
                            confirmButtonColor: '#1e3c72'
                        });
                    },
                    complete: function() {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                });
            }

            function displayExistingPhotos(fotos) {
                const container = $('#existingPhotosContainer');
                container.empty();

                if (fotos && fotos.length > 0) {
                    fotos.forEach((foto) => {
                        const photoHtml = `
                            <div class="col-md-3 mb-2">
                                <div class="position-relative">
                                    <img src="${baseUrl}/storage/${foto}" class="img-fluid rounded-3" style="height: 100px; width: 100%; object-fit: cover; cursor: pointer;" 
                                         onclick="showLightbox('${baseUrl}/storage/${foto}', 'Foto Dokumentasi')">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" 
                                            onclick="removeExistingPhoto('${foto}', this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        container.append(photoHtml);
                    });
                } else {
                    container.html('<p class="text-muted">Belum ada foto</p>');
                }
            }

            window.removeExistingPhoto = function(fotoPath, btn) {
                Swal.fire({
                    title: 'Hapus Foto?',
                    text: 'Foto ini akan dihapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: baseUrl + '/dokumentasi/delete-photo',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                foto: fotoPath,
                                group_id: currentGroupId
                            },
                            success: function(response) {
                                $(btn).closest('.col-md-3').remove();
                                Swal.fire('Terhapus!', 'Foto berhasil dihapus.', 'success');
                            },
                            error: function() {
                                Swal.fire('Error!', 'Gagal menghapus foto.', 'error');
                            }
                        });
                    }
                });
            };

            // ========== SHOW DETAIL FUNCTION - RAPI with BASE URL ==========
            function showDetail(groupId) {
                $('#detailContent').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat data detail dokumentasi...</p>
                    </div>
                `);
                $('#detailModal').modal('show');

                $.ajax({
                    url: baseUrl + '/dokumentasi/' + groupId,
                    type: 'GET',
                    dataType: 'json',
                    timeout: 30000,
                    success: function(data) {
                        // Galeri Foto
                        let fotosHtml = '';
                        if (data.fotos && data.fotos.length > 0) {
                            data.fotos.forEach(foto => {
                                fotosHtml += `
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                            <img src="${baseUrl}/storage/${foto}" class="card-img-top" style="height: 180px; width: 100%; object-fit: cover; cursor: pointer;" 
                                                 onclick="showLightbox('${baseUrl}/storage/${foto}', 'Foto Dokumentasi')" alt="Dokumentasi">
                                            <div class="card-body p-2 text-center bg-light">
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i> ${formatDate(data.tanggal_upload)}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        // Status Badge
                        let statusBadge = '';
                        if (data.pembangunan?.status == 'direncanakan') {
                            statusBadge =
                                '<span class="badge bg-secondary rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i> Direncanakan</span>';
                        } else if (data.pembangunan?.status == 'berjalan') {
                            statusBadge =
                                '<span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-spinner fa-pulse me-1"></i> Sedang Berjalan</span>';
                        } else if (data.pembangunan?.status == 'selesai') {
                            statusBadge =
                                '<span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> Selesai</span>';
                        }

                        let html = `
                            <div class="row">
                                <div class="col-12">
                                    <!-- Card Informasi Dokumentasi -->
                                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                                        <div class="card-header bg-light border-0 py-3">
                                            <strong><i class="fas fa-info-circle text-primary me-2"></i>Informasi Dokumentasi</strong>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-detail-item">
                                                        <div class="info-detail-label"><i class="fas fa-building me-1"></i> Nama Pembangunan</div>
                                                        <div class="info-detail-value">${escapeHtml(data.pembangunan?.nama_pembangunan || '-')}</div>
                                                    </div>
                                                    <div class="info-detail-item">
                                                        <div class="info-detail-label"><i class="fas fa-map-marker-alt me-1"></i> Lokasi</div>
                                                        <div class="info-detail-value">${escapeHtml(data.pembangunan?.lokasi || '-')}</div>
                                                    </div>
                                                    <div class="info-detail-item">
                                                        <div class="info-detail-label"><i class="fas fa-chart-line me-1"></i> Status Pembangunan</div>
                                                        <div class="info-detail-value">${statusBadge}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-detail-item">
                                                        <div class="info-detail-label"><i class="fas fa-calendar-alt me-1"></i> Tanggal Upload</div>
                                                        <div class="info-detail-value">${formatDate(data.tanggal_upload)}</div>
                                                    </div>
                                                    <div class="info-detail-item">
                                                        <div class="info-detail-label"><i class="fas fa-images me-1"></i> Jumlah Foto</div>
                                                        <div class="info-detail-value">${data.fotos?.length || 0} Foto</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Keterangan Dokumentasi -->
                                    ${data.keterangan ? `
                                                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                                                        <div class="card-header bg-light border-0 py-3">
                                                            <strong><i class="fas fa-align-left text-primary me-2"></i>Keterangan Dokumentasi</strong>
                                                        </div>
                                                        <div class="card-body p-4">
                                                            <div class="detail-keterangan-content">
                                                                ${data.keterangan}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ` : ''}

                                    <!-- Galeri Foto -->
                                    <div class="card shadow-sm border-0 rounded-4">
                                        <div class="card-header bg-light border-0 py-3">
                                            <strong><i class="fas fa-camera text-primary me-2"></i>Galeri Foto</strong>
                                            <span class="badge bg-primary rounded-pill ms-2">${data.fotos?.length || 0} Foto</span>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="row g-3">
                                                ${fotosHtml || '<div class="col-12 text-center text-muted py-5"><i class="fas fa-image fa-4x mb-3 d-block text-secondary"></i><h6>Belum Ada Foto</h6><p class="small">Silakan upload foto dokumentasi</p></div>'}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#detailContent').html(html);
                    },
                    error: function(xhr, status, error) {
                        $('#detailContent').html(`
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                                <h5 class="text-danger">Gagal Memuat Data</h5>
                                <p class="text-muted">Gagal memuat data detail. Silakan coba lagi.</p>
                                <button class="btn btn-primary mt-2 rounded-3 px-4" onclick="showDetail('${groupId}')">
                                    <i class="fas fa-sync-alt me-2"></i> Coba Lagi
                                </button>
                            </div>
                        `);
                    }
                });
            }

            function deleteData(groupId) {
                $('#deleteForm').attr('action', baseUrl + '/dokumentasi/' + groupId);
                $('#deleteModal').modal('show');
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
        </script>
    @endpush
