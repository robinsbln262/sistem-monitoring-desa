@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="fas fa-user-check me-2 text-primary"></i> Status Rekanan</h5>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                <i class="fas fa-users me-1"></i> Total Respon: {{ $rekananProyek->total() }}
            </span>
        </div>

        <!-- STATISTIK -->
        <div class="row mb-4">
            <div class="col-md-3 mb-2">
                <div class="card bg-success text-white border-0 rounded-3">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">DISETUJUI</small>
                                <h4 class="mb-0">{{ $totalSetuju ?? 0 }}</h4>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card bg-danger text-white border-0 rounded-3">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">DITOLAK</small>
                                <h4 class="mb-0">{{ $totalTolak ?? 0 }}</h4>
                            </div>
                            <i class="fas fa-times-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card bg-warning text-white border-0 rounded-3">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">MENINJAU</small>
                                <h4 class="mb-0">{{ $totalMeninjau ?? 0 }}</h4>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="card bg-info text-white border-0 rounded-3">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">TOTAL REKANAN</small>
                                <h4 class="mb-0">{{ $totalRekanan ?? 0 }}</h4>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL -->
        <div class="table-responsive">
            <table class="table table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Rekanan</th>
                        <th>Bidang Keahlian</th>
                        <th>Proyek</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Tanggal Respon</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekananProyek as $index => $rp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $rp->rekanan->nama_user }}</strong>
                                <br>
                                <small class="text-muted">{{ $rp->rekanan->no_telp ?: '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $rp->rekanan->bidang_keahlian ?: 'Umum' }}</span>
                            </td>
                            <td>
                                <strong>{{ $rp->proyek->nama_pembangunan }}</strong>
                                <br>
                                <small class="text-muted">{{ $rp->proyek->lokasi }}</small>
                            </td>
                            <td>
                                @if($rp->status == 'setuju')
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Disetujui
                                    </span>
                                @elseif($rp->status == 'tolak')
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                        <i class="fas fa-clock me-1"></i> Meninjau
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($rp->catatan)
                                    <span class="text-muted small" data-bs-toggle="tooltip" title="{{ $rp->catatan }}">
                                        {{ Str::limit($rp->catatan, 30) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ date('d/m/Y H:i', strtotime($rp->tanggal_respon)) }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Belum ada data respons dari rekanan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $rekananProyek->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
                pageLength: 10,
                responsive: true
            });
        });
    </script>
@endpush