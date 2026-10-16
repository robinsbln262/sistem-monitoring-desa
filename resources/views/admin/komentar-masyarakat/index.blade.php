@extends('layouts.app')

@section('content')
<div class="table-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0"><i class="fas fa-comments me-2 text-primary"></i> Komentar Masyarakat</h5>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge bg-secondary rounded-pill px-3 py-2">
                <i class="fas fa-list me-1"></i> Total: {{ $stats['total'] }}
            </span>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                <i class="fas fa-clock me-1"></i> Pending: {{ $stats['pending'] }}
            </span>
            <span class="badge bg-success rounded-pill px-3 py-2">
                <i class="fas fa-check me-1"></i> Approved: {{ $stats['approved'] }}
            </span>
            <span class="badge bg-danger rounded-pill px-3 py-2">
                <i class="fas fa-times me-1"></i> Rejected: {{ $stats['total'] - $stats['approved'] - $stats['pending'] }}
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover" id="dataTable">
            <thead class="table-light">
                <tr>
                    <th width="4%">No</th>
                    <th>Proyek</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Komentar</th>
                    <th width="10%">Foto Bukti</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($komentars as $index => $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('publik.show', $k->id_pembangunan) }}" target="_blank" class="text-decoration-none">
                                <i class="fas fa-external-link-alt me-1 small"></i>
                                {{ Str::limit($k->proyek->nama_pembangunan ?? 'Proyek', 25) }}
                            </a>
                        </td>
                        <td class="fw-bold">{{ $k->nama }}</td>
                        <td>{{ $k->email ?? '-' }}</td>
                        <td>
                            <span data-bs-toggle="tooltip" title="{{ $k->komentar }}">
                                {{ Str::limit($k->komentar, 40) }}
                            </span>
                        </td>
                        <td>
                            @if($k->foto)
                                <img src="{{ asset('uploads/komentar_masyarakat/' . $k->foto) }}" 
                                     alt="Foto Bukti" 
                                     class="img-thumbnail komentar-thumb" 
                                     style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                     onclick="showLightboxAdmin('{{ asset('uploads/komentar_masyarakat/' . $k->foto) }}', 'Foto Bukti Lapangan oleh: {{ $k->nama }}')">
                            @else
                                <span class="badge bg-light text-muted border">Tanpa Foto</span>
                            @endif
                        </td>
                        <td>
                            @if($k->status == 'pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i> Pending
                                </span>
                            @elseif($k->status == 'approved')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i> Approved
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i> Rejected
                                </span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $k->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                            </small>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                @if($k->status == 'pending')
                                    <form action="{{ route('admin.komentar-masyarakat.approve', $k->id_komentar) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success btn-action" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.komentar-masyarakat.reject', $k->id_komentar) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger btn-action" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.komentar-masyarakat.destroy', $k->id_komentar) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-action" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @if($k->status == 'approved')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Aktif
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-comments fa-3x mb-3 d-block"></i>
                            <h5>Belum Ada Komentar</h5>
                            <p>Belum ada komentar dari masyarakat.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $komentars->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-action {
        padding: 4px 10px;
        margin: 2px;
        font-size: 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
    .table td {
        vertical-align: middle;
    }
    .komentar-thumb {
        transition: transform 0.2s ease;
    }
    .komentar-thumb:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            pageLength: 10,
            responsive: true,
            order: [[7, 'desc']] // Diubah ke kolom ke-7 (index 7) sesuai urutan tanggal
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Function Lightbox Preview Foto Bukti Admin
    function showLightboxAdmin(imageUrl, caption) {
        const modalHtml = `
            <div class="modal fade" id="lightboxAdminModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-body text-center p-0">
                            <button type="button" class="btn btn-light btn-lg rounded-circle position-absolute top-0 end-0 m-3"
                                data-bs-dismiss="modal" style="z-index:1060; width:40px; height:40px;">
                                <i class="fas fa-times"></i>
                            </button>
                            <img src="${imageUrl}" alt="Full Size" style="max-width:90%; max-height:80vh; border-radius:12px; box-shadow:0 25px 50px rgba(0,0,0,.4);">
                            <p class="text-white mt-3 fs-6">${caption || ''}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#lightboxAdminModal').remove();
        $('body').append(modalHtml);
        $('#lightboxAdminModal').modal('show');
    }
</script>
@endpush