@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i> Kelola Pengguna Sistem</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus me-1"></i> Tambah User
            </button>
        </div>

        <!-- Stats Summary -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">TOTAL PENGGUNA</small>
                                <h3 class="mb-0 mt-1">{{ $users->count() }}</h3>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">ADMINISTRATOR</small>
                                <h3 class="mb-0 mt-1">{{ $users->where('level', 'admin')->count() }}</h3>
                            </div>
                            <i class="fas fa-shield-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">PERANGKAT DESA</small>
                                <h3 class="mb-0 mt-1">{{ $users->where('level', 'perangkat_desa')->count() }}</h3>
                            </div>
                            <i class="fas fa-user-check fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">KEPALA DESA</small>
                                <h3 class="mb-0 mt-1">{{ $users->where('level', 'kepala_desa')->count() }}</h3>
                            </div>
                            <i class="fas fa-user-tie fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>Tanggal Dibuat</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $u)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-sm" style="width: 35px; height: 35px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($u->nama_user, 0, 2)) }}
                                    </div>
                                    <span>{{ $u->nama_user }}</span>
                                </div>
                            </td>
                            <td>{{ $u->username }}</td>
                            <td>
                                @if($u->level == 'admin')
                                    <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-shield-alt me-1"></i> Administrator</span>
                                @elseif($u->level == 'perangkat_desa')
                                    <span class="badge bg-primary rounded-pill px-3 py-2"><i class="fas fa-user-check me-1"></i> Perangkat Desa</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-user-tie me-1"></i> Kepala Desa</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-calendar-alt me-1 text-muted"></i> 
                                {{ $u->created_at ? date('d/m/Y', strtotime($u->created_at)) : '-' }}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-action rounded-3" onclick="editData({{ $u->id_user }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($u->id_user != Auth::id())
                                    <button class="btn btn-sm btn-danger btn-action rounded-3" onclick="deleteData({{ $u->id_user }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white"><i class="fas fa-plus me-2"></i> Tambah Pengguna Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" name="nama_user" class="form-control border-start-0 rounded-end-3" placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-at text-muted"></i>
                                </span>
                                <input type="text" name="username" class="form-control border-start-0 rounded-end-3" placeholder="Masukkan username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" name="password" id="passwordTambah" class="form-control border-start-0" placeholder="Minimal 6 karakter" required>
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('passwordTambah', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Password minimal 6 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-tag text-muted"></i>
                                </span>
                                <select name="level" class="form-select border-start-0 rounded-end-3" required>
                                    <option value="admin">👑 Administrator</option>
                                    <option value="perangkat_desa">👥 Perangkat Desa</option>
                                    <option value="kepala_desa">🏛️ Kepala Desa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white"><i class="fas fa-edit me-2"></i> Edit Pengguna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <input type="text" name="nama_user" id="edit_nama" class="form-control border-start-0 rounded-end-3" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-at text-muted"></i>
                                </span>
                                <input type="text" name="username" id="edit_username" class="form-control border-start-0 rounded-end-3" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password (kosongkan jika tidak diubah)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-key text-muted"></i>
                                </span>
                                <input type="password" name="password" id="passwordEdit" class="form-control border-start-0" placeholder="Minimal 6 karakter">
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('passwordEdit', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Level</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3">
                                    <i class="fas fa-tag text-muted"></i>
                                </span>
                                <select name="level" id="edit_level" class="form-select border-start-0 rounded-end-3" required>
                                    <option value="admin">👑 Administrator</option>
                                    <option value="perangkat_desa">👥 Perangkat Desa</option>
                                    <option value="kepala_desa">🏛️ Kepala Desa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="fas fa-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                    <p class="fw-bold">Apakah Anda yakin ingin menghapus pengguna ini?</p>
                    <small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-3 px-4">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Base URL untuk AJAX (berfungsi di XAMPP & artisan serve)
        var baseUrl = '{{ url("/") }}';

        $(document).ready(function () {
            $('#dataTable').DataTable({
                language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
                pageLength: 10,
                responsive: true
            });
        });

        // Toggle password visibility
        function togglePassword(fieldId, btn) {
            const field = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Edit Data Function dengan Base URL
        function editData(id) {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            $.ajax({
                url: baseUrl + '/users/' + id,
                type: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function (data) {
                    $('#edit_nama').val(data.nama_user);
                    $('#edit_username').val(data.username);
                    $('#edit_level').val(data.level);
                    $('#editForm').attr('action', baseUrl + '/users/' + id);
                    
                    // Reset password field
                    $('#passwordEdit').val('');
                    
                    $('#editModal').modal('show');
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat memuat data. Silakan coba lagi.',
                        confirmButtonColor: '#1e3c72'
                    });
                    console.error('Error:', error);
                },
                complete: function () {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            });
        }

        // Delete Data Function dengan Base URL
        function deleteData(id) {
            $('#deleteForm').attr('action', baseUrl + '/users/' + id);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush