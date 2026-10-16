@extends('layouts.app')

@section('content')
    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2 text-primary"></i> Manajemen Laporan</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="fas fa-plus me-1"></i> Buat Laporan Baru
            </button>
        </div>

        <!-- Stats Summary -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">TOTAL LAPORAN</small>
                                <h3 class="mb-0 mt-1">{{ $totalLaporan }}</h3>
                            </div>
                            <i class="fas fa-file-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">LAPORAN BULAN INI</small>
                                <h3 class="mb-0 mt-1">{{ $laporanBulanIni }}</h3>
                            </div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-white-50">JENIS LAPORAN</small>
                                <h3 class="mb-0 mt-1">{{ $laporanPembangunan + $laporanMonitoring + $laporanEvaluasi }}</h3>
                            </div>
                            <i class="fas fa-chart-pie fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark border-0 rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-dark-50">AKTIFITAS TERAKHIR</small>
                                @if($laporans->first())
                                    <h6 class="mb-0 mt-1 fw-bold">
                                        {{ \Carbon\Carbon::parse($laporans->first()->created_at)->locale('id')->translatedFormat('d F Y') }}
                                    </h6>
                                @else
                                    <h6 class="mb-0 mt-1">Belum ada aktifitas</h6>
                                @endif
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Jenis Laporan -->
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <h6 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i> Statistik Jenis Laporan</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="jenisLaporanChart" height="200"></canvas>
                        <div class="row mt-3 text-center">
                            <div class="col-4">
                                <span class="badge bg-primary">Pembangunan</span>
                                <p class="mt-1 mb-0 fw-bold">{{ $laporanPembangunan }}</p>
                            </div>
                            <div class="col-4">
                                <span class="badge bg-info">Monitoring</span>
                                <p class="mt-1 mb-0 fw-bold">{{ $laporanMonitoring }}</p>
                            </div>
                            <div class="col-4">
                                <span class="badge bg-success">Evaluasi</span>
                                <p class="mt-1 mb-0 fw-bold">{{ $laporanEvaluasi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 pt-3">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i> Informasi Laporan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Total Pembangunan</small>
                                        <strong>{{ $pembangunans->count() }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Laporan Evaluasi</small>
                                        <strong>{{ $laporanEvaluasi }}</strong>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                        <i class="fas fa-chart-line text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Laporan Monitoring</small>
                                        <strong>{{ $laporanMonitoring }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Aktifitas Terakhir</small>
                                        <strong>
                                            @if($laporans->first())
                                            {{ \Carbon\Carbon::parse($laporans->first()->created_at)->translatedFormat('d M
                                            Y') }}
                                            @else
                                            -
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="table-responsive mt-4">
            <table class="table table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pembangunan</th>
                        <th>Jenis Laporan</th>
                        <th>Periode</th>
                        <th>Tanggal Cetak</th>
                        <th>Dibuat Oleh</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporans as $index => $l)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $l->pembangunan->nama_pembangunan }}</td>
                            <td>
                                @if($l->jenis_laporan == 'pembangunan')
                                    <span class="badge bg-primary rounded-pill px-3 py-2"><i class="fas fa-building me-1"></i>
                                        Pembangunan</span>
                                @elseif($l->jenis_laporan == 'monitoring')
                                    <span class="badge bg-info rounded-pill px-3 py-2"><i class="fas fa-chart-line me-1"></i>
                                        Monitoring</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i>
                                        Evaluasi</span>
                                @endif
                            </td>
                            <td>{{ $l->periode }}</td>
                            <td>{{ date('d/m/Y', strtotime($l->tanggal_cetak)) }}</td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">
                                    <i class="fas fa-user me-1"></i> {{ $l->user->nama_user }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('laporan.preview-pdf', $l->id_laporan) }}"
                                        class="btn btn-sm btn-info btn-action" target="_blank" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('laporan.cetak-pdf', $l->id_laporan) }}"
                                        class="btn btn-sm btn-danger btn-action" title="Download PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger btn-action" onclick="deleteData({{ $l->id_laporan }})"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $laporans->links() }}
        </div>
    </div>

    <!-- Modal Buat Laporan -->
    <div class="modal fade" id="tambahModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <h5 class="modal-title text-white"><i class="fas fa-plus me-2"></i> Buat Laporan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('laporan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Pilih Pembangunan <span
                                        class="text-danger">*</span></label>
                                <select name="id_pembangunan" class="form-select rounded-3" required>
                                    <option value="">-- Pilih Pembangunan --</option>
                                    @if(isset($pembangunans) && $pembangunans->count() > 0)
                                        @foreach($pembangunans as $p)
                                            <option value="{{ $p->id_pembangunan }}">{{ $p->nama_pembangunan }} - {{ $p->lokasi }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>Belum ada data pembangunan</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jenis Laporan <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_laporan" class="form-select rounded-3" required>
                                    <option value="pembangunan">
                                        📄 Laporan Pembangunan
                                    </option>
                                    <option value="monitoring">
                                        📊 Laporan Monitoring
                                    </option>
                                    <option value="evaluasi">
                                        ✅ Laporan Evaluasi
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Periode Laporan <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="periode" class="form-control rounded-3"
                                    placeholder="Contoh: Januari 2024" required>
                                <small class="text-muted">Contoh: Januari 2024, Triwulan I 2024, Semester I 2024</small>
                            </div>
                        </div>

                        <!-- Preview Ringkasan Laporan -->
                        <div class="alert alert-info mt-3 rounded-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Laporan akan mencakup data lengkap pembangunan,
                            riwayat monitoring, evaluasi, dan dokumentasi yang tersedia.
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-secondary rounded-3 px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="fas fa-file-alt me-2"></i> Buat Laporan
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
                    <p>Apakah Anda yakin ingin menghapus laporan ini?</p>
                    <small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary rounded-3 px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-3 px-4">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        // Chart Jenis Laporan
        var ctx = document.getElementById('jenisLaporanChart').getContext('2d');
        var chartData = {
            labels: ['Pembangunan', 'Monitoring', 'Evaluasi'],
            datasets: [{
                data: [{{ $laporanPembangunan }}, {{ $laporanMonitoring }}, {{ $laporanEvaluasi }}],
                backgroundColor: ['#1e3c72', '#2a5298', '#10b981'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });

        // Delete Data Function dengan Base URL
        function deleteData(id) {
            $('#deleteForm').attr('action', baseUrl + '/laporan/' + id);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush