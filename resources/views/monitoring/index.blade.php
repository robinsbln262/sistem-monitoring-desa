@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i> Riwayat Monitoring Pembangunan</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus me-1"></i> Tambah Monitoring
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
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
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $p->nama_pembangunan }}</td>
                            <td>{{ $p->lokasi }}</td>
                            <td>
                                @if($p->status == 'direncanakan')
                                    <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i> Direncanakan</span>
                                @elseif($p->status == 'berjalan')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-spinner fa-pulse me-1"></i> Berjalan</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> Selesai</span>
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
                                    @foreach($riwayat->take(3) as $r)
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
                                            @if($r->dana_terpakai)
                                                <div class="small text-success mt-1">
                                                    <i class="fas fa-money-bill-wave me-1"></i>
                                                    Dana: Rp {{ number_format($r->dana_terpakai, 0, ',', '.') }}
                                                </div>
                                            @endif

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
                                                              onclick="alert('Catatan Penolakan:\n\n{{ addslashes($r->catatan_verifikasi) }}')" 
                                                              title="Lihat Catatan Penolakan">
                                                            <i class="fas fa-info-circle me-1"></i> Lihat Catatan
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-question-circle me-1"></i> Unknown
                                                    </span>
                                                @endif
                                            </div>

                                            @if($r->komentar_admin)
                                                <div class="mt-1">
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-comment me-1"></i> Ada Komentar
                                                    </span>
                                                </div>
                                            @endif
                                            {{-- ===== FOTO DOKUMENTASI LANGSUNG ===== --}}
                                            @if($r->foto_before || $r->foto_after)
                                                <div class="row mt-2 g-2">
                                                    @if($r->foto_before)
                                                        @php $fotoBeforeUrl = $r->foto_before_url ?? asset('storage/' . $r->foto_before); @endphp
                                                        <div class="col-6">
                                                            <div class="monitoring-photo-card">
                                                                <div class="monitoring-photo-label before"><i class="fas fa-camera me-1"></i> SEBELUM</div>
                                                                <img src="{{ $fotoBeforeUrl }}" class="monitoring-photo-thumb" alt="Foto Sebelum" loading="lazy"
                                                                    onclick="showLightbox(@js($fotoBeforeUrl), @js('Foto Sebelum - ' . $r->status_progres))"
                                                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';" title="Klik untuk memperbesar">
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($r->foto_after)
                                                        @php $fotoAfterUrl = $r->foto_after_url ?? asset('storage/' . $r->foto_after); @endphp
                                                        <div class="col-6">
                                                            <div class="monitoring-photo-card">
                                                                <div class="monitoring-photo-label after"><i class="fas fa-camera me-1"></i> SESUDAH</div>
                                                                <img src="{{ $fotoAfterUrl }}" class="monitoring-photo-thumb" alt="Foto Sesudah" loading="lazy"
                                                                    onclick="showLightbox(@js($fotoAfterUrl), @js('Foto Sesudah - ' . $r->status_progres))"
                                                                    onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';" title="Klik untuk memperbesar">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <!-- Tombol Edit hanya jika belum approved -->
                                                @if($r->status_verifikasi != 'approved')
                                                    <button class="btn btn-sm btn-warning btn-action"
                                                        onclick="editData({{ $r->id_monitoring }})" title="Edit Riwayat">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-secondary btn-action" disabled
                                                        title="Tidak dapat diedit (sudah disetujui)">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif

                                                <!-- Tombol Hapus hanya jika belum approved -->
                                                @if($r->status_verifikasi != 'approved')
                                                    <button class="btn btn-sm btn-danger btn-action"
                                                        onclick="deleteData({{ $r->id_monitoring }})" title="Hapus Riwayat">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-secondary btn-action" disabled
                                                        title="Tidak dapat dihapus (sudah disetujui)">
                                                        <i class="fas fa-lock"></i>
                                                    </button>
                                                @endif

                                                @if(Auth::user()->level == 'admin')
                                                    @if($r->komentar_admin)
                                                        <button class="btn btn-sm btn-info btn-action"
                                                            onclick="lihatKomentar('{{ addslashes($r->komentar_admin) }}')" title="Lihat Komentar">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-primary btn-action"
                                                            onclick="tambahKomentar({{ $r->id_monitoring }})" title="Beri Komentar">
                                                            <i class="fas fa-comment"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($riwayat->count() > 3)
                                        <div class="text-center mt-2">
                                            <span class="badge bg-secondary rounded-pill" style="cursor: pointer;"
                                                onclick="showDetailRiwayat({{ $p->id_pembangunan }})">
                                                <i class="fas fa-ellipsis-h me-1"></i> +{{ $riwayat->count() - 3 }} riwayat lainnya
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-group-vertical btn-group-sm" role="group">
                                    <button class="btn btn-sm btn-primary mb-1"
                                        onclick="tambahMonitoring({{ $p->id_pembangunan }}, '{{ addslashes($p->nama_pembangunan) }}')"
                                        title="Tambah Monitoring">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-chart-line fa-3x mb-3 d-block"></i>
                                <h5>Belum Ada Data Monitoring</h5>
                                <p>Silakan tambahkan monitoring pembangunan terlebih dahulu.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH MONITORING -->
    <!-- ========================================== -->
    <div class="modal fade" id="tambahModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i> Tambah Monitoring</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pilih Pembangunan <span class="text-danger">*</span></label>
                                <select name="id_pembangunan" id="select_pembangunan" class="form-select" required>
                                    <option value="">-- Pilih Pembangunan --</option>
                                    @foreach($allPembangunans as $p)
                                        <option value="{{ $p->id_pembangunan }}" data-progres="{{ $p->progress }}">
                                            {{ $p->nama_pembangunan }} - {{ $p->lokasi }} (Progres: {{ $p->progress }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Monitoring <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_monitoring" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Progres (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="progres" id="progres_input" class="form-control" min="0" max="100" required>
                                    <div class="input-group-append">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="fas fa-magic"></i> Rekomendasi
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" onclick="setProgres(10)">🔨 10% - Persiapan Awal</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="setProgres(25)">🏗️ 25% - Pondasi</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="setProgres(50)">📊 50% - Setengah Jalan</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="setProgres(75)">🎯 75% - Hampir Selesai</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="setProgres(90)">🎨 90% - Finishing</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="setProgres(100)">✅ 100% - Selesai</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    Progres terakhir: <strong id="progres_terakhir_text">0</strong>% |
                                    Rekomendasi: <strong id="progres_rekomendasi_text" class="text-success">-</strong>
                                </small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Progres <span class="text-danger">*</span></label>
                                <select name="status_progres" id="status_progres_select" class="form-select" required>
                                    <option value="">-- Pilih Status Progres --</option>
                                    <option value="Perencanaan">📋 Perencanaan</option>
                                    <option value="Persiapan Lahan">🚜 Persiapan Lahan</option>
                                    <option value="Pekerjaan Pondasi">🏗️ Pekerjaan Pondasi</option>
                                    <option value="Pekerjaan Dinding">🧱 Pekerjaan Dinding</option>
                                    <option value="Pekerjaan Atap">🏠 Pekerjaan Atap</option>
                                    <option value="Pekerjaan Finishing">🎨 Pekerjaan Finishing</option>
                                    <option value="Pembersihan">🧹 Pembersihan</option>
                                    <option value="Selesai">✅ Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dana Terpakai (Rp)</label>
                                <input type="number" name="dana_terpakai" class="form-control rounded-3" step="0.01" min="0" placeholder="Contoh: 5000000">
                                <small class="text-muted">Jumlah dana yang telah digunakan hingga progres ini</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" id="summernoteTambah" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto Sebelum (Before)</label>
                                <input type="file" name="foto_before" class="form-control rounded-3" accept="image/*">
                                <small class="text-muted">Foto kondisi awal sebelum pengerjaan</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Foto Sesudah (After)</label>
                                <input type="file" name="foto_after" class="form-control rounded-3" accept="image/*">
                                <small class="text-muted">Foto kondisi setelah pengerjaan</small>
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

    <!-- ========================================== -->
    <!-- MODAL DETAIL RIWAYAT MONITORING -->
    <!-- ========================================== -->
    <div class="modal fade" id="detailRiwayatModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-history me-2"></i> Riwayat Monitoring Lengkap</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailRiwayatContent">
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

    <!-- ========================================== -->
    <!-- MODAL EDIT MONITORING -->
    <!-- ========================================== -->
    <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i> Edit Monitoring</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
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
                                <label class="form-label">Tanggal Monitoring</label>
                                <input type="date" name="tanggal_monitoring" id="edit_tanggal" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Progres (%)</label>
                                <input type="number" name="progres" id="edit_progres" class="form-control" min="0" max="100" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Progres</label>
                                <select name="status_progres" id="edit_status" class="form-select" required>
                                    <option value="Perencanaan">📋 Perencanaan</option>
                                    <option value="Persiapan Lahan">🚜 Persiapan Lahan</option>
                                    <option value="Pekerjaan Pondasi">🏗️ Pekerjaan Pondasi</option>
                                    <option value="Pekerjaan Dinding">🧱 Pekerjaan Dinding</option>
                                    <option value="Pekerjaan Atap">🏠 Pekerjaan Atap</option>
                                    <option value="Pekerjaan Finishing">🎨 Pekerjaan Finishing</option>
                                    <option value="Pembersihan">🧹 Pembersihan</option>
                                    <option value="Selesai">✅ Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dana Terpakai (Rp)</label>
                                <input type="number" name="dana_terpakai" id="edit_dana_terpakai" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" id="summernoteEdit" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ganti Foto Before (Opsional)</label>
                                <input type="file" name="foto_before" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ganti Foto After (Opsional)</label>
                                <input type="file" name="foto_after" class="form-control" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
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

    <!-- ========================================== -->
    <!-- MODAL KOMENTAR (ADMIN) -->
    <!-- ========================================== -->
    <div class="modal fade" id="komentarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-comment me-2"></i> Beri Komentar
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="komentarForm" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Komentar <span class="text-danger">*</span></label>
                            <textarea name="komentar_admin" class="form-control rounded-3" rows="4" 
                                      placeholder="Tulis komentar atau catatan untuk progres ini..." required></textarea>
                            <small class="text-muted">Komentar akan terlihat oleh rekanan dan masyarakat</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3">
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
    <div class="modal fade" id="lihatKomentarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-comment me-2"></i> Komentar Admin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="lihatKomentarContent">
                    <div class="alert alert-warning">
                        <i class="fas fa-quote-left me-2"></i>
                        <span id="komentarText"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL HAPUS -->
    <!-- ========================================== -->
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
                        <p>Apakah Anda yakin ingin menghapus data monitoring ini?</p>
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
        #detailRiwayatContent .table td,
        #detailRiwayatContent .table th {
            vertical-align: middle;
            padding: 12px 10px;
        }
        #detailRiwayatContent .card-header {
            border-radius: 16px 16px 0 0;
        }
        .monitoring-card {
            border: 1px solid #eef2f6;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        .monitoring-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .monitoring-photo-card, .detail-photo-card { overflow:hidden; border:1px solid #e5e7eb; border-radius:9px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,.06); }
        .monitoring-photo-label, .detail-photo-label { padding:4px 7px; color:#fff; font-size:10px; font-weight:700; }
        .monitoring-photo-label.before, .detail-photo-label.before { background:#6c757d; }
        .monitoring-photo-label.after, .detail-photo-label.after { background:#198754; }
        .monitoring-photo-thumb { display:block; width:100%; height:100px; object-fit:cover; cursor:pointer; transition:.2s; }
        .monitoring-photo-thumb:hover, .detail-photo-thumb:hover { transform:scale(1.04); filter:brightness(.88); }
        .detail-monitoring-photos { display:flex; justify-content:center; gap:6px; flex-wrap:wrap; }
        .detail-photo-card { width:95px; }
        .detail-photo-thumb { display:block; width:100%; height:68px; object-fit:cover; cursor:pointer; transition:.2s; }
        #lightboxModal { z-index:1080; }
        #lightboxModal .modal-content, #lightboxModal .modal-body { background:transparent !important; }
        #lightboxModal .lightbox-image { display:block; max-width:95%; max-height:85vh; width:auto; height:auto; margin:0 auto; border-radius:16px; box-shadow:0 25px 50px rgba(0,0,0,.35); object-fit:contain; }
        @media(max-width:576px){ .monitoring-photo-thumb{height:85px;} .detail-photo-card{width:82px;} .detail-photo-thumb{height:60px;} #lightboxModal .lightbox-image{max-width:98%;max-height:78vh;} }
        .monitoring-card .foto-thumb {
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
        .monitoring-card .komentar-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 10px 14px;
            border-radius: 6px;
            margin-top: 10px;
        }
        .badge-progres {
            font-size: 0.9rem;
            padding: 6px 14px;
        }
        /* Tambahan untuk status verifikasi */
        .badge-verifikasi {
            font-size: 0.7rem;
            padding: 3px 10px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('summernote/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('summernote/lang/summernote-id-ID.min.js') }}"></script>

    <script>
        var baseUrl = '{{ url("/") }}';
        var csrfToken = '{{ csrf_token() }}';
        var userLevel = '{{ Auth::user()->level }}';

        $(document).ready(function () {
            // Auto-fill progres ketika memilih pembangunan
            $('#select_pembangunan').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var progresText = selectedOption.text();
                var match = progresText.match(/Progres: (\d+)%/);

                if (match) {
                    var progresTerakhir = parseInt(match[1]);
                    $('#progres_terakhir_text').text(progresTerakhir);

                    var rekomendasi = progresTerakhir + 10;
                    if (rekomendasi > 100) rekomendasi = 100;

                    $('#progres_input').val(rekomendasi);
                    $('#progres_rekomendasi_text').text(rekomendasi + '% (naik 10%)');
                    updateStatusProgres(rekomendasi);

                    $('#progres_input').off('input').on('input', function () {
                        var currentValue = parseInt($(this).val());
                        var progresTerakhirVal = parseInt($('#progres_terakhir_text').text());

                        if (currentValue < progresTerakhirVal) {
                            $(this).addClass('is-invalid');
                            $('#progres_rekomendasi_text').html(`<span class="text-danger">⚠️ Tidak boleh kurang dari ${progresTerakhirVal}%</span>`);
                        } else {
                            $(this).removeClass('is-invalid');
                            var naik = currentValue - progresTerakhirVal;
                            $('#progres_rekomendasi_text').html(currentValue + '% (naik ' + naik + '%)');
                            updateStatusProgres(currentValue);
                        }
                    });
                } else {
                    $('#progres_terakhir_text').text('0');
                    $('#progres_input').val(10);
                    $('#progres_rekomendasi_text').text('10% (awal pekerjaan)');
                    updateStatusProgres(10);
                }
            });

            // Trigger change awal
            setTimeout(function() {
                $('#select_pembangunan').trigger('change');
            }, 100);
        });

        var toolbarOptions = [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['misc', ['undo', 'redo']]
        ];

        // Summernote Tambah
        $('#tambahModal').on('shown.bs.modal', function () {
            setTimeout(function () {
                if ($('#summernoteTambah').length && !$('#summernoteTambah').hasClass('note-editor')) {
                    $('#summernoteTambah').summernote({
                        placeholder: 'Tulis keterangan detail progres pembangunan di sini...',
                        tabsize: 2,
                        height: 250,
                        lang: 'id-ID',
                        toolbar: toolbarOptions,
                        callbacks: {
                            onImageUpload: function (files) {
                                uploadImage(files[0], this);
                            }
                        }
                    });
                }
            }, 100);
        });

        $('#tambahModal').on('hidden.bs.modal', function () {
            if ($('#summernoteTambah').hasClass('note-editor')) {
                $('#summernoteTambah').summernote('destroy');
            }
            $('#select_pembangunan').val('');
            $('#progres_input').val('');
            $('#progres_terakhir_text').text('0');
            $('#progres_rekomendasi_text').text('-');
        });

        // Summernote Edit
        $('#editModal').on('shown.bs.modal', function () {
            setTimeout(function () {
                if ($('#summernoteEdit').length && !$('#summernoteEdit').hasClass('note-editor')) {
                    $('#summernoteEdit').summernote({
                        placeholder: 'Tulis keterangan detail progres pembangunan di sini...',
                        tabsize: 2,
                        height: 250,
                        lang: 'id-ID',
                        toolbar: toolbarOptions,
                        callbacks: {
                            onImageUpload: function (files) {
                                uploadImage(files[0], this);
                            }
                        }
                    });
                }
            }, 100);
        });

        $('#editModal').on('hidden.bs.modal', function () {
            if ($('#summernoteEdit').hasClass('note-editor')) {
                $('#summernoteEdit').summernote('destroy');
            }
        });

        function uploadImage(file, editor) {
            let formData = new FormData();
            formData.append('image', file);
            formData.append('_token', csrfToken);

            $.ajax({
                url: baseUrl + '/upload-image',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success) {
                        $(editor).summernote('insertImage', response.url);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Upload',
                            text: response.message || 'Gagal mengupload gambar',
                            confirmButtonColor: '#1e3c72'
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Upload',
                        text: 'Terjadi kesalahan saat mengupload gambar',
                        confirmButtonColor: '#1e3c72'
                    });
                }
            });
        }

        function updateStatusProgres(progres) {
            if (progres >= 100) $('#status_progres_select').val('Selesai');
            else if (progres >= 90) $('#status_progres_select').val('Pembersihan');
            else if (progres >= 75) $('#status_progres_select').val('Pekerjaan Finishing');
            else if (progres >= 60) $('#status_progres_select').val('Pekerjaan Atap');
            else if (progres >= 40) $('#status_progres_select').val('Pekerjaan Dinding');
            else if (progres >= 25) $('#status_progres_select').val('Pekerjaan Pondasi');
            else if (progres >= 10) $('#status_progres_select').val('Persiapan Lahan');
            else $('#status_progres_select').val('Perencanaan');
        }

        function setProgres(progres) {
            var progresTerakhir = parseInt($('#progres_terakhir_text').text());
            if (progres >= progresTerakhir) {
                $('#progres_input').val(progres);
                $('#progres_input').trigger('input');
                updateStatusProgres(progres);
                Swal.fire({
                    icon: 'success',
                    title: 'Progres Diatur',
                    text: 'Progres diatur ke ' + progres + '%',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Valid',
                    text: 'Progres tidak boleh kurang dari ' + progresTerakhir + '%!',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }

        function tambahMonitoring(id, nama) {
            $('#select_pembangunan').val(id).trigger('change');
            $('#tambahModal').modal('show');
        }

        // ADMIN - Tambah Komentar
        function tambahKomentar(id) {
            $('#komentarForm').attr('action', baseUrl + '/monitoring/' + id + '/komentar');
            $('#komentarModal').modal('show');
        }

        // ADMIN - Lihat Komentar
        function lihatKomentar(komentar) {
            $('#komentarText').text(komentar);
            $('#lihatKomentarModal').modal('show');
        }

        // ==========================================
        // SHOW DETAIL RIWAYAT - FIXED
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
                success: function (response) {
                    if (response.success) {
                        let html = buildDetailRiwayatHtml(response);
                        $('#detailRiwayatContent').html(html);
                    } else {
                        throw new Error(response.error || 'Gagal memuat data');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', xhr.status, error);
                    console.error('Response:', xhr.responseText);
                    
                    let errorMessage = 'Gagal memuat riwayat monitoring. Silakan coba lagi.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.error) {
                            errorMessage = response.error;
                        }
                    } catch(e) {}
                    
                    $('#detailRiwayatContent').html(`
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                            <h5 class="text-danger">Gagal Memuat Data</h5>
                            <p class="text-muted">${errorMessage}</p>
                            <p class="text-muted small">Status: ${xhr.status}</p>
                            <button class="btn btn-primary mt-3 rounded-3 px-4" onclick="showDetailRiwayat(${idPembangunan})">
                                <i class="fas fa-sync-alt me-2"></i> Coba Lagi
                            </button>
                        </div>
                    `);
                }
            });
        }

        // ==========================================
        // BUILD DETAIL RIWAYAT HTML
        // ==========================================
        function buildDetailRiwayatHtml(response) {
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
                                    <th width="18%">Dokumentasi</th>
                                    <th width="13%">Komentar</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                response.monitorings.forEach((m, index) => {
                    let danaHtml = m.dana_terpakai ? 'Rp ' + new Intl.NumberFormat('id-ID').format(m.dana_terpakai) : '-';
                    
                    let komentarHtml = '';
                    if (m.komentar_admin) {
                        komentarHtml = `<span class="badge bg-warning text-dark" style="cursor:pointer;" onclick="lihatKomentar('${escapeHtml(m.komentar_admin)}')">
                            <i class="fas fa-comment me-1"></i> Lihat
                        </span>`;
                    } else if (userLevel == 'admin') {
                        komentarHtml = `<button class="btn btn-sm btn-outline-primary" onclick="tambahKomentar(${m.id_monitoring})">
                            <i class="fas fa-plus me-1"></i>
                        </button>`;
                    } else {
                        komentarHtml = `<span class="badge bg-secondary">-</span>`;
                    }

                    // ===== FOTO THUMBNAIL DI DETAIL =====
                    let fotoHtml = '';
                    let fotoBeforeUrl = m.foto_before_url || '';
                    let fotoAfterUrl = m.foto_after_url || '';
                    if (!fotoBeforeUrl && m.foto_before) fotoBeforeUrl = baseUrl.replace(/\/$/, '') + '/storage/' + String(m.foto_before).replace(/^\//, '');
                    if (!fotoAfterUrl && m.foto_after) fotoAfterUrl = baseUrl.replace(/\/$/, '') + '/storage/' + String(m.foto_after).replace(/^\//, '');
                    if (fotoBeforeUrl || fotoAfterUrl) {
                        fotoHtml = `<div class="detail-monitoring-photos">
                            ${fotoBeforeUrl ? `<div class="detail-photo-card"><div class="detail-photo-label before"><i class="fas fa-camera me-1"></i> SEBELUM</div><img src="${escapeHtml(fotoBeforeUrl)}" class="detail-photo-thumb" alt="Foto Sebelum" loading="lazy" onclick='showLightbox(${JSON.stringify(fotoBeforeUrl)}, ${JSON.stringify('Foto Sebelum - ' + (m.status_progres || 'Monitoring'))})' onerror="this.onerror=null;this.src='${baseUrl}/images/no-image.png';" title="Klik untuk memperbesar"></div>` : ''}
                            ${fotoAfterUrl ? `<div class="detail-photo-card"><div class="detail-photo-label after"><i class="fas fa-camera me-1"></i> SESUDAH</div><img src="${escapeHtml(fotoAfterUrl)}" class="detail-photo-thumb" alt="Foto Sesudah" loading="lazy" onclick='showLightbox(${JSON.stringify(fotoAfterUrl)}, ${JSON.stringify('Foto Sesudah - ' + (m.status_progres || 'Monitoring'))})' onerror="this.onerror=null;this.src='${baseUrl}/images/no-image.png';" title="Klik untuk memperbesar"></div>` : ''}
                        </div>`;
                    }

                    // ===== BADGE STATUS VERIFIKASI DI DETAIL =====
                    let verifikasiBadge = '';
                    if (m.status_verifikasi == 'pending') {
                        verifikasiBadge = `<span class="badge bg-warning text-dark badge-verifikasi"><i class="fas fa-clock me-1"></i> Pending</span>`;
                    } else if (m.status_verifikasi == 'approved') {
                        verifikasiBadge = `<span class="badge bg-success badge-verifikasi"><i class="fas fa-check-circle me-1"></i> Approved</span>`;
                    } else if (m.status_verifikasi == 'rejected') {
                        verifikasiBadge = `<span class="badge bg-danger badge-verifikasi"><i class="fas fa-times-circle me-1"></i> Rejected</span>`;
                    } else {
                        verifikasiBadge = `<span class="badge bg-secondary badge-verifikasi">-</span>`;
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
                            <td class="text-center">
                                ${fotoHtml || '<span class="text-muted small"><i class="fas fa-image me-1"></i> Tidak ada foto</span>'}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                                    ${verifikasiBadge}
                                    ${komentarHtml}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    ${m.status_verifikasi != 'approved' ? 
                                        `<button class="btn btn-sm btn-warning" onclick="editData(${m.id_monitoring})" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>` :
                                        `<button class="btn btn-sm btn-secondary" disabled title="Tidak dapat diedit">
                                            <i class="fas fa-lock"></i>
                                        </button>`
                                    }
                                    ${m.status_verifikasi != 'approved' ?
                                        `<button class="btn btn-sm btn-danger" onclick="deleteData(${m.id_monitoring})" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>` :
                                        `<button class="btn btn-sm btn-secondary" disabled title="Tidak dapat dihapus">
                                            <i class="fas fa-lock"></i>
                                        </button>`
                                    }
                                    ${(m.foto_before || m.foto_after) ? `<button class="btn btn-sm btn-info" onclick='showLightbox(${JSON.stringify(fotoBeforeUrl || fotoAfterUrl)}, "Dokumentasi")' title="Lihat Foto"><i class="fas fa-image"></i></button>` : ''}
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
        // EDIT DATA
        // ==========================================
        function editData(id) {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            $.ajax({
                url: baseUrl + '/monitoring/' + id + '/get-data',
                type: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function (data) {
                    // Cek apakah data bisa diedit
                    if (data.is_approved) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Diedit',
                            text: 'Data monitoring sudah disetujui dan tidak dapat diedit!',
                            confirmButtonColor: '#1e3c72'
                        });
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                        return;
                    }

                    $('#edit_id_pembangunan').val(data.id_pembangunan);
                    $('#edit_tanggal').val(data.tanggal_monitoring_formatted);
                    $('#edit_progres').val(data.progres);
                    $('#edit_status').val(data.status_progres);
                    $('#edit_dana_terpakai').val(data.dana_terpakai || '');
                    $('#editForm').attr('action', baseUrl + '/monitoring/' + id);

                    $('#editModal').modal('show');

                    setTimeout(function () {
                        if ($('#summernoteEdit').length) {
                            if (!$('#summernoteEdit').hasClass('note-editor')) {
                                $('#summernoteEdit').summernote({
                                    placeholder: 'Tulis keterangan detail progres pembangunan di sini...',
                                    tabsize: 2,
                                    height: 250,
                                    lang: 'id-ID',
                                    toolbar: toolbarOptions,
                                    callbacks: {
                                        onImageUpload: function (files) {
                                            uploadImage(files[0], this);
                                        }
                                    }
                                });
                            }
                            $('#summernoteEdit').summernote('code', data.keterangan || '');
                        }
                    }, 200);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', xhr.responseText);
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

        // ==========================================
        // DELETE DATA
        // ==========================================
        function deleteData(id) {
            // Cek dulu status verifikasi via AJAX
            $.ajax({
                url: baseUrl + '/monitoring/' + id + '/get-data',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.is_approved) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Dapat Dihapus',
                            text: 'Data monitoring sudah disetujui dan tidak dapat dihapus!',
                            confirmButtonColor: '#1e3c72'
                        });
                        return;
                    }
                    $('#deleteForm').attr('action', baseUrl + '/monitoring/' + id);
                    $('#deleteModal').modal('show');
                },
                error: function () {
                    // Fallback: tetap tampilkan modal hapus
                    $('#deleteForm').attr('action', baseUrl + '/monitoring/' + id);
                    $('#deleteModal').modal('show');
                }
            });
        }

        // ==========================================
        // LIGHTBOX FOTO
        // ==========================================
        function showLightbox(imageUrl, caption) {
            if (!imageUrl || String(imageUrl).trim() === '') {
                Swal.fire({ icon: 'warning', title: 'Foto Tidak Tersedia', text: 'Maaf, foto tidak ditemukan.', confirmButtonColor: '#1e3c72' });
                return;
            }
            let fullUrl = String(imageUrl).trim();
            if (!fullUrl.startsWith('http://') && !fullUrl.startsWith('https://') && !fullUrl.startsWith('/')) {
                fullUrl = baseUrl.replace(/\/$/, '') + '/storage/' + fullUrl.replace(/^\//, '');
            }
            $('#lightboxModal').remove();
            $('body').append(`
                <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content bg-transparent border-0">
                            <div class="modal-body text-center p-0 position-relative">
                                <button type="button" class="btn btn-light btn-lg rounded-circle position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" style="z-index:1060;width:45px;height:45px;"><i class="fas fa-times"></i></button>
                                <img id="lightboxImage" src="${escapeHtml(fullUrl)}" alt="Full Size" class="lightbox-image" onerror="this.onerror=null;this.src='${baseUrl}/images/no-image.png';">
                                <p id="lightboxCaption" class="text-white mt-3 fs-5 mb-0">${escapeHtml(caption || 'Dokumentasi')}</p>
                            </div>
                        </div>
                    </div>
                </div>`);
            const el=document.getElementById('lightboxModal');
            const modal=new bootstrap.Modal(el);
            el.addEventListener('hidden.bs.modal',()=>el.remove(),{once:true});
            modal.show();
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
    </script>
@endpush