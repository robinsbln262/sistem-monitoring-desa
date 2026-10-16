@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="user-avatar-lg mx-auto mb-3">
                        <div class="avatar-circle">
                            {{ strtoupper(substr(Auth::user()->nama_user, 0, 2)) }}
                        </div>
                    </div>
                    <h5 class="mb-1">{{ Auth::user()->nama_user }}</h5>
                    <p class="text-muted small mb-3">
                        @if(Auth::user()->level == 'admin')
                            <i class="fas fa-shield-alt text-danger me-1"></i> Administrator
                        @elseif(Auth::user()->level == 'rekanan')
                            <i class="fas fa-user-tie text-success me-1"></i> Rekanan
                        @elseif(Auth::user()->level == 'kepala_desa')
                            <i class="fas fa-user-check text-primary me-1"></i> Kepala Desa
                        @endif
                    </p>
                    <hr>
                    <div class="text-start">
                        <p class="mb-2"><i class="fas fa-user me-2 text-primary"></i> <strong>Username:</strong>
                            {{ Auth::user()->username }}</p>
                        <p class="mb-2"><i class="fas fa-phone me-2 text-primary"></i> <strong>No. Telepon:</strong>
                            {{ Auth::user()->no_telp ?? '-' }}</p>
                        @if(Auth::user()->bidang_keahlian)
                            <p class="mb-2"><i class="fas fa-tools me-2 text-primary"></i> <strong>Bidang Keahlian:</strong>
                                {{ Auth::user()->bidang_keahlian }}</p>
                        @endif
                        <p class="mb-2"><i class="fas fa-calendar-alt me-2 text-primary"></i> <strong>Bergabung:</strong>
                            {{ Auth::user()->created_at ? \Carbon\Carbon::parse(Auth::user()->created_at)->locale('id')->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <!-- Edit Profil -->
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2 text-primary"></i> Edit Profil</h5>
                </div>
                <div class="card-body p-4">
                    <form id="profileForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_user" class="form-control rounded-3"
                                value="{{ Auth::user()->nama_user }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control rounded-3" value="{{ Auth::user()->username }}" disabled>
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-envelope me-2"></i> Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                class="form-control rounded-3"
                                value="{{ old('email', Auth::user()->email ?? '') }}"
                                placeholder="contoh@email.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">
                                Email ini digunakan untuk menerima pemberitahuan status penawaran proyek.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="no_telp" class="form-control rounded-3"
                                value="{{ Auth::user()->no_telp }}">
                        </div>
                        @if(Auth::user()->level == 'rekanan')
                        <div class="mb-3">
                            <label class="form-label">Bidang Keahlian</label>
                            <input type="text" name="bidang_keahlian" class="form-control rounded-3"
                                value="{{ Auth::user()->bidang_keahlian }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control rounded-3" rows="2">{{ Auth::user()->alamat }}</textarea>
                        </div>
                        @endif
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Upload CV untuk Rekanan -->
            @if(Auth::user()->level == 'rekanan')
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0"><i class="fas fa-file-pdf me-2 text-danger"></i> Upload CV / Profil</h5>
                </div>
                <div class="card-body p-4">
                    @if(Auth::user()->cv)
                        <div class="alert alert-success d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-check-circle me-2"></i>
                                CV sudah diupload:
                            @if($user->cv)
                                <a href="{{ asset('uploads/cv_rekanan/' . basename($user->cv)) }}"
                                target="_blank"
                                class="btn btn-primary">
                                    <i class="fas fa-file-pdf me-1"></i>
                                    Lihat CV
                                </a>
                            @endif
                            </div>
                            <form action="{{ route('rekanan.delete-cv') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus CV?')">
                                    <i class="fas fa-trash me-1"></i> Hapus CV
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Anda belum mengupload CV. Upload CV untuk meningkatkan kredibilitas Anda sebagai rekanan.
                        </div>
                    @endif

                    <form action="{{ route('rekanan.upload-cv') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload CV / Profil <span class="text-danger">*</span></label>
                            <input type="file" name="cv" class="form-control rounded-3" accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Format: PDF, DOC, DOCX | Maks 5MB</small>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="fas fa-upload me-2"></i> Upload CV
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Ubah Password -->
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0"><i class="fas fa-key me-2 text-warning"></i> Ubah Password</h5>
                </div>
                <div class="card-body p-4">
                    <form id="passwordForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" class="form-control rounded-3" required>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="form-control rounded-3" required>
                        </div>
                        <button type="submit" class="btn btn-warning rounded-3 px-4">
                            <i class="fas fa-sync-alt me-2"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
            color: white;
            margin: 0 auto;
        }
    </style>
@endsection

@push('scripts')
    <script>
        // Update Profile
        $('#profileForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("profile.update") }}',
                type: 'PUT',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonColor: '#1e3c72'
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    if (errors) {
                        $.each(errors, function (key, value) {
                            errorMsg += value[0] + '\n';
                        });
                    } else if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg,
                        confirmButtonColor: '#1e3c72'
                    });
                }
            });
        });

        // Update Password
        $('#passwordForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("profile.change-password") }}',
                type: 'PUT',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonColor: '#1e3c72'
                    }).then(() => {
                        $('#passwordForm')[0].reset();
                    });
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    if (errors) {
                        $.each(errors, function (key, value) {
                            errorMsg += value[0] + '\n';
                        });
                    } else if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg = 'Terjadi kesalahan. Silakan coba lagi.';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg,
                        confirmButtonColor: '#1e3c72'
                    });
                }
            });
        });
    </script>
@endpush