@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="fas fa-check-circle me-2 text-primary"></i> Riwayat Evaluasi Pembangunan</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus me-1"></i> Tambah Evaluasi
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pembangunan</th>
                        <th>Lokasi</th>
                        <th>Progres</th>
                        <th>Status Evaluasi</th>
                        <th width="35%">Riwayat Evaluasi</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedEvaluasis as $index => $item)
                        @php
                            $p = $item['pembangunan'];
                            $riwayat = $item['riwayat'];
                            $evaluasiTerbaru = $item['evaluasi_terbaru'];
                            $progres = $p->monitorings->first()?->progres ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $pembangunans->firstItem() + $index }}</td>
                            <td class="fw-bold">{{ $p->nama_pembangunan }}</td>
                            <td>{{ $p->lokasi }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                        <div class="progress-bar {{ $progres == 100 ? 'bg-success' : 'bg-primary' }}"
                                            style="width: {{ $progres }}%"></div>
                                    </div>
                                    <span class="small fw-bold">{{ $progres }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $lastHasil = strtolower($evaluasiTerbaru->hasil_evaluasi ?? '');
                                    if (strpos($lastHasil, 'berhasil') !== false) {
                                        $badgeClass = 'bg-success';
                                        $badgeText = '✅ Berhasil';
                                    } elseif (strpos($lastHasil, 'kurang') !== false || strpos($lastHasil, 'gagal') !== false) {
                                        $badgeClass = 'bg-danger';
                                        $badgeText = '⚠️ Kurang Berhasil';
                                    } else {
                                        $badgeClass = 'bg-warning text-dark';
                                        $badgeText = '📊 Perlu Perhatian';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">{{ $badgeText }}</span>
                            </td>
                            <td>
                                <div class="riwayat-list">
                                    @foreach($riwayat->take(3) as $r)
                                        @php
                                            $hasilRingkas = Str::limit(strip_tags($r->hasil_evaluasi), 60);
                                            $statusIcon = '';
                                            if (strpos(strtolower($r->hasil_evaluasi), 'berhasil') !== false) {
                                                $statusIcon = '✅';
                                            } elseif (strpos(strtolower($r->hasil_evaluasi), 'kurang') !== false) {
                                                $statusIcon = '⚠️';
                                            } else {
                                                $statusIcon = '📝';
                                            }
                                        @endphp
                                        <div class="riwayat-item mb-2 p-2 bg-light rounded">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="badge bg-info text-white">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ date('d/m/Y', strtotime($r->tanggal_evaluasi)) }}
                                                </span>
                                                <span class="fw-bold">{{ $statusIcon }}</span>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                <i class="fas fa-info-circle me-1"></i> {{ $hasilRingkas ?: '-' }}
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-sm btn-warning btn-action"
                                                    onclick="editData({{ $r->id_evaluasi }})" title="Edit Evaluasi">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-action"
                                                    onclick="deleteData({{ $r->id_evaluasi }})" title="Hapus Evaluasi">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($riwayat->count() > 3)
                                        <div class="text-center mt-2">
                                            <span class="badge bg-secondary rounded-pill" style="cursor: pointer;"
                                                onclick="showDetailRiwayat({{ $p->id_pembangunan }})">
                                                <i class="fas fa-ellipsis-h me-1"></i> +{{ $riwayat->count() - 3 }} evaluasi lainnya
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical btn-group-sm" role="group">
                                    <button class="btn btn-sm btn-primary mb-1"
                                        onclick="tambahEvaluasi({{ $p->id_pembangunan }}, '{{ addslashes($p->nama_pembangunan) }}')"
                                        title="Tambah Evaluasi">
                                        <i class="fas fa-plus-circle me-1"></i> Tambah
                                    </button>
                                    <button class="btn btn-sm btn-info" onclick="showDetailRiwayat({{ $p->id_pembangunan }})"
                                        title="Lihat Semua Riwayat">
                                        <i class="fas fa-history me-1"></i> Riwayat
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x mb-3 d-block text-muted"></i>
                                <h5 class="text-muted">Belum Ada Data Evaluasi</h5>
                                <p class="text-muted">Silakan tambahkan evaluasi pembangunan terlebih dahulu.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ========================================== --}}
        {{-- PAGINATION — VENDOR CUSTOM --}}
        {{-- ========================================== --}}
        @if($pembangunans->hasPages())
            <div class="mt-4">
                {{ $pembangunans->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah Evaluasi -->
    <div class="modal fade" id="tambahModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Tambah Evaluasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('evaluasi.store') }}" method="POST" id="formTambahEvaluasi">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pilih Pembangunan <span class="text-danger">*</span></label>
                                <select name="id_pembangunan" id="select_pembangunan" class="form-select" required>
                                    <option value="">-- Pilih Pembangunan --</option>
                                    @foreach($allPembangunans as $p)
                                        <option value="{{ $p->id_pembangunan }}">
                                            {{ $p->nama_pembangunan }} - {{ $p->lokasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Evaluasi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_evaluasi" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Hasil Evaluasi <span class="text-danger">*</span></label>
                                <textarea name="hasil_evaluasi" id="summernoteHasil" class="form-control" rows="5"></textarea>
                                <small class="text-muted">Tip: Gunakan kata kunci "berhasil" atau "kurang" untuk status otomatis</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Kendala yang Ditemukan</label>
                                <textarea name="kendala" id="summernoteKendala" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Rekomendasi</label>
                                <textarea name="rekomendasi" id="summernoteRekomendasi" class="form-control" rows="3"></textarea>
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

    <!-- Modal Detail Riwayat Evaluasi -->
    <div class="modal fade" id="detailRiwayatModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-history me-2"></i> Riwayat Evaluasi Lengkap</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailRiwayatContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat riwayat evaluasi...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Evaluasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pilih Pembangunan</label>
                                <select name="id_pembangunan" id="edit_id_pembangunan" class="form-select" required>
                                    @foreach($allPembangunans as $p)
                                        <option value="{{ $p->id_pembangunan }}">{{ $p->nama_pembangunan }} - {{ $p->lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Evaluasi</label>
                                <input type="date" name="tanggal_evaluasi" id="edit_tanggal" class="form-control" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Hasil Evaluasi</label>
                                <textarea name="hasil_evaluasi" id="summernoteHasilEdit" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Kendala</label>
                                <textarea name="kendala" id="summernoteKendalaEdit" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Rekomendasi</label>
                                <textarea name="rekomendasi" id="summernoteRekomendasiEdit" class="form-control" rows="3"></textarea>
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

    <!-- Modal Hapus -->
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
                        <p>Apakah Anda yakin ingin menghapus data evaluasi ini?</p>
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

@push('styles')
    <link href="{{ asset('summernote/summernote-bs5.min.css') }}" rel="stylesheet">
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
        }

        .note-editor.note-frame {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .note-editor.note-frame .note-editing-area .note-editable {
            background: white;
            min-height: 200px;
        }

        .note-toolbar {
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
            padding: 8px !important;
        }

        .btn-action {
            padding: 2px 6px;
            margin: 0 2px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('summernote/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('summernote/lang/summernote-id-ID.min.js') }}"></script>

    <script>
        var baseUrl = '{{ url("/") }}';

        $(document).ready(function() {
            // GA PAKE DATATABLES!

            // Summernote
            if ($('#summernoteHasil').length && !$('#summernoteHasil').hasClass('note-editor')) {
                $('#summernoteHasil').summernote({
                    placeholder: 'Tulis hasil evaluasi pembangunan di sini...',
                    tabsize: 2,
                    height: 200,
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

            if ($('#summernoteKendala').length && !$('#summernoteKendala').hasClass('note-editor')) {
                $('#summernoteKendala').summernote({
                    placeholder: 'Tulis kendala yang ditemukan di sini...',
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

            if ($('#summernoteRekomendasi').length && !$('#summernoteRekomendasi').hasClass('note-editor')) {
                $('#summernoteRekomendasi').summernote({
                    placeholder: 'Tulis rekomendasi tindak lanjut di sini...',
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

        function tambahEvaluasi(id, nama) {
            $('#select_pembangunan').val(id);
            $('#tambahModal').modal('show');
        }

        function showDetailRiwayat(idPembangunan) {
            $('#detailRiwayatContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Memuat riwayat evaluasi...</p>
                </div>
            `);
            $('#detailRiwayatModal').modal('show');

            $.ajax({
                url: baseUrl + '/evaluasi/pembangunan/' + idPembangunan,
                type: 'GET',
                dataType: 'json',
                timeout: 15000,
                success: function(response) {
                    if (response.success) {
                        let html = `
                            <div class="card shadow-sm border-0 rounded-4">
                                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><i class="fas fa-building text-primary me-2"></i> ${escapeHtml(response.pembangunan.nama_pembangunan)}</strong>
                                        <span class="badge bg-secondary ms-2">${escapeHtml(response.pembangunan.lokasi)}</span>
                                    </div>
                                    <span class="badge bg-info">Progres: ${response.pembangunan.progress}%</span>
                                </div>
                                <div class="card-body p-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Hasil Evaluasi</th>
                                                    <th>Kendala</th>
                                                    <th>Rekomendasi</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        `;

                        if (response.evaluasis && response.evaluasis.length > 0) {
                            response.evaluasis.forEach((e, index) => {
                                html += `
                                    <tr>
                                        <td class="text-center">${index + 1}</td>
                                        <td>${formatDate(e.tanggal_evaluasi)}</td>
                                        <td>${stripHtmlTags(e.hasil_evaluasi)}</td>
                                        <td>${stripHtmlTags(e.kendala)}</td>
                                        <td>${stripHtmlTags(e.rekomendasi)}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning" onclick="editData(${e.id_evaluasi})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteData(${e.id_evaluasi})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            html += `<tr><td colspan="6" class="text-center">Belum ada data</td></tr>`;
                        }

                        html += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#detailRiwayatContent').html(html);
                    }
                },
                error: function() {
                    $('#detailRiwayatContent').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                            <h5 class="text-danger">Gagal Memuat Data</h5>
                            <button class="btn btn-primary mt-3" onclick="showDetailRiwayat(${idPembangunan})">
                                <i class="fas fa-sync-alt me-2"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                }
            });
        }

        function editData(id) {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            $.ajax({
                url: baseUrl + '/evaluasi/' + id + '/get-data',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#edit_id_pembangunan').val(data.id_pembangunan);
                    $('#edit_tanggal').val(data.tanggal_evaluasi_formatted);
                    $('#editForm').attr('action', baseUrl + '/evaluasi/' + id);

                    setTimeout(function() {
                        if ($('#summernoteHasilEdit').length && !$('#summernoteHasilEdit').hasClass('note-editor')) {
                            $('#summernoteHasilEdit').summernote({
                                placeholder: 'Tulis hasil evaluasi...',
                                tabsize: 2,
                                height: 200,
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
                            $('#summernoteKendalaEdit').summernote({
                                placeholder: 'Tulis kendala...',
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
                            $('#summernoteRekomendasiEdit').summernote({
                                placeholder: 'Tulis rekomendasi...',
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
                        $('#summernoteHasilEdit').summernote('code', data.hasil_evaluasi || '');
                        $('#summernoteKendalaEdit').summernote('code', data.kendala || '');
                        $('#summernoteRekomendasiEdit').summernote('code', data.rekomendasi || '');
                    }, 100);

                    $('#editModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error!', 'Gagal memuat data', 'error');
                },
                complete: function() {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            });
        }

        function deleteData(id) {
            $('#deleteForm').attr('action', baseUrl + '/evaluasi/' + id);
            $('#deleteModal').modal('show');
        }

        function stripHtmlTags(html) {
            if (!html) return '-';
            return html.replace(/<[^>]*>/g, '');
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
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