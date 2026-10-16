<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Proyek - {{ $proyek->nama_pembangunan }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-kab-karo.png') }}">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', 'Times', serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000000;
            background: white;
            padding: 0;
        }

        /* ========== COVER ========== */
        .cover-page {
            text-align: center;
            padding-top: 80px;
            page-break-after: always;
            position: relative;
            min-height: 100%;
        }
        
        .cover-logo {
            width: 180px;
            height: auto;
            margin: 0 auto 30px auto;
            display: block;
        }
        
        .cover-title-utama {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .cover-title-sub {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .cover-title-desa {
            font-size: 17pt;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        
        .cover-alamat {
            font-size: 9pt;
            margin-bottom: 35px;
            line-height: 1.4;
        }
        
        .cover-line {
            width: 60%;
            margin: 0 auto 35px auto;
            border-bottom: 3px solid #1e3c72;
        }
        
        .cover-judul-laporan {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .cover-nama-proyek {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .cover-tahun {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        
        .cover-footer {
            position: absolute;
            bottom: 60px;
            left: 0;
            right: 0;
            text-align: center;
        }

        /* ========== KOP SURAT ========== */
        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000000;
            padding-bottom: 8px;
        }

        .pemerintah-kabupaten {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .pemerintah-kecamatan {
            font-size: 12pt;
            font-weight: bold;
        }

        .pemerintah-desa {
            font-size: 15pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 3px;
        }

        .alamat-desa {
            font-size: 9pt;
            margin-top: 5px;
        }

        /* ========== JUDUL ========== */
        .judul-laporan {
            text-align: center;
            margin: 20px 0 15px 0;
        }

        .judul-laporan h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .judul-laporan p {
            font-size: 11pt;
            margin-top: 3px;
        }

        /* ========== TABEL ========== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table th, .info-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .info-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 35%;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            background: #f0f0f0;
            padding: 6px 10px;
            margin: 15px 0 10px 0;
            border-left: 4px solid #1e3c72;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10pt;
        }

        .data-table th, .data-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .data-table .text-end {
            text-align: right;
        }
        .data-table .text-center {
            text-align: center;
        }

        /* ========== TANDA TANGAN ========== */
        .tanda-tangan {
            margin-top: 30px;
        }

        .ttd-table {
            width: 100%;
            margin-top: 20px;
        }

        .ttd-table td {
            border: none;
            padding: 5px;
        }

        .ttd-content {
            text-align: center;
            width: 250px;
        }

        /* ========== UTILITY ========== */
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .text-bold { font-weight: bold; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }

        .badge-success { background: #10b981; color: white; padding: 2px 8px; }
        .badge-warning { background: #f59e0b; color: white; padding: 2px 8px; }
        .badge-secondary { background: #6c757d; color: white; padding: 2px 8px; }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }

        .page-number {
            text-align: right;
            font-size: 9pt;
            margin-top: 10px;
        }

        /* ========== PROGRESS BAR ========== */
        .progress-bar-bg {
            background: #e9ecef;
            height: 14px;
            border-radius: 7px;
            overflow: hidden;
            width: 100%;
            margin-top: 3px;
        }
        .progress-bar-fill {
            height: 100%;
            background: #10b981;
            border-radius: 7px;
        }
        .progress-bar-fill.warning {
            background: #f59e0b;
        }
        .progress-bar-fill.danger {
            background: #ef4444;
        }
    </style>
</head>
<body>

    <!-- ==================== COVER ==================== -->
    <div class="cover-page">
        <img src="{{ public_path('images/logo-kab-karo.png') }}" class="cover-logo" alt="Logo"
            onerror="this.style.display='none'">
        
        <div class="cover-title-utama">PEMERINTAH KABUPATEN KARO</div>
        <div class="cover-title-sub">KECAMATAN KUTABULUH</div>
        <div class="cover-title-desa">DESA KUTABULUH</div>
        <div class="cover-alamat">
            Jl. Raya Kutabuluh, Desa Kutabuluh, Kec. Kutabuluh, Kab. Karo<br>
            Provinsi Sumatera Utara, Kode Pos 22155
        </div>
        
        <div class="cover-line"></div>
        
        <div class="cover-judul-laporan">LAPORAN PROYEK</div>
        <div class="cover-nama-proyek">{{ $proyek->nama_pembangunan }}</div>
        <div class="cover-tahun">Tahun Anggaran {{ $proyek->tahun_anggaran }}</div>

        <div class="cover-footer">
            <div style="font-size: 12pt; font-weight: bold;">SISMOLEV</div>
            <div style="font-size: 10pt;">Sistem Informasi Monitoring dan Evaluasi Pembangunan</div>
            <div style="font-size: 9pt; margin-top: 5px;">Desa Kutabuluh - Kabupaten Karo</div>
        </div>
    </div>

    <!-- ==================== HALAMAN ISI ==================== -->
    <div style="padding: 20px 0;">

        <!-- KOP SURAT -->
        <div class="kop-surat">
            <div class="pemerintah-kabupaten">PEMERINTAH KABUPATEN KARO</div>
            <div class="pemerintah-kecamatan">KECAMATAN KUTABULUH</div>
            <div class="pemerintah-desa">DESA KUTABULUH</div>
            <div class="alamat-desa">
                Jl. Raya Kutabuluh, Desa Kutabuluh, Kec. Kutabuluh, Kab. Karo<br>
                Provinsi Sumatera Utara, Kode Pos 22155
            </div>
        </div>

        <!-- JUDUL -->
        <div class="judul-laporan">
            <h3>LAPORAN PROYEK</h3>
            <p>{{ $proyek->nama_pembangunan }}</p>
            <p style="font-size: 10pt; font-weight: normal;">Tahun Anggaran {{ $proyek->tahun_anggaran }}</p>
        </div>

        <!-- ===== INFORMASI UMUM PROYEK ===== -->
        <div class="section-title">A. INFORMASI UMUM PROYEK</div>
        <table class="info-table">
            <tr><th>Nama Proyek</th><td>{{ $proyek->nama_pembangunan }}</td></tr>
            <tr><th>Lokasi</th><td>{{ $proyek->lokasi }}</td></tr>
            <tr><th>Bidang Pekerjaan</th><td>{{ $proyek->bidang_pekerjaan ?: '-' }}</td></tr>
            <tr><th>Tahun Anggaran</th><td>{{ $proyek->tahun_anggaran }}</td></tr>
            <tr><th>Tanggal Mulai</th><td>{{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><th>Tanggal Selesai</th><td>{{ \Carbon\Carbon::parse($proyek->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}</td></tr>
            <tr><th>Sumber Dana</th><td>{{ $proyek->sumber_dana }}</td></tr>
            <tr><th>Total Anggaran</th><td>Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</td></tr>
            <tr><th>Status Proyek</th>
                <td>
                    @if($proyek->status == 'direncanakan')
                        <span class="badge-secondary">Direncanakan</span>
                    @elseif($proyek->status == 'berjalan')
                        <span class="badge-warning">Sedang Berjalan</span>
                    @else
                        <span class="badge-success">Selesai</span>
                    @endif
                </td>
            </tr>
            <tr><th>Progres</th>
                <td>
                    {{ $proyek->progress }}%
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill {{ $proyek->progress >= 100 ? 'success' : ($proyek->progress >= 80 ? 'warning' : '') }}" 
                             style="width: {{ $proyek->progress }}%;"></div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- ===== REKANAN PELAKSANA ===== -->
        <div class="section-title">B. REKANAN PELAKSANA</div>
        <table class="info-table">
            @if(isset($rekanan) && $rekanan)
                <tr><th>Nama Rekanan</th><td>{{ $rekanan->nama_user }}</td></tr>
                <tr><th>Bidang Keahlian</th><td>{{ $rekanan->bidang_keahlian ?: '-' }}</td></tr>
                <tr><th>No. Telepon</th><td>{{ $rekanan->no_telp ?: '-' }}</td></tr>
                <tr><th>Alamat</th><td>{{ $rekanan->alamat ?: '-' }}</td></tr>
                @if(isset($hargaDeal) && $hargaDeal)
                    <tr><th>Harga Deal</th><td>Rp {{ number_format($hargaDeal, 0, ',', '.') }}</td></tr>
                @endif
            @else
                <tr><td colspan="2" class="text-center">Belum ada rekanan yang ditunjuk</td></tr>
            @endif
        </table>

        <!-- ===== RINCIAN ANGGARAN PER TAHAP ===== -->
        <div class="section-title">C. RINCIAN PENGGUNAAN ANGGARAN PER TAHAP</div>

        @php
            $approvedMonitorings = $monitorings->where('status_verifikasi', 'approved')->sortBy('tanggal_monitoring');
            $totalDanaTerpakai = $approvedMonitorings->sum('dana_terpakai');
            $sisaAnggaran = $proyek->anggaran - $totalDanaTerpakai;
        @endphp

        <table class="info-table" style="margin-bottom: 15px;">
            <tr>
                <th style="width: 33%;">Total Dana Terpakai</th>
                <td style="width: 33%;">Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}</td>
                <th style="width: 34%;">Sisa Anggaran</th>
                <td>Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if($approvedMonitorings->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="10%">Progres</th>
                        <th width="20%">Status Pekerjaan</th>
                        <th width="20%">Dana Terpakai</th>
                        <th width="15%">% dari Anggaran</th>
                        <th width="15%">Kumulatif</th>
                    </tr>
                </thead>
                <tbody>
                    @php $kumulatif = 0; @endphp
                    @foreach($approvedMonitorings as $i => $m)
                        @php
                            $kumulatif += $m->dana_terpakai ?? 0;
                            $persentase = $proyek->anggaran > 0 ? round(($m->dana_terpakai / $proyek->anggaran) * 100, 2) : 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-center">{{ $m->progres }}%</td>
                            <td>
                                <strong>{{ $m->status_progres }}</strong>
                                @if($m->keterangan)
                                    <br><small>{{ Str::limit(strip_tags($m->keterangan), 60) }}</small>
                                @endif
                            </td>
                            <td class="text-end">Rp {{ number_format($m->dana_terpakai ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $persentase }}%</td>
                            <td class="text-end">Rp {{ number_format($kumulatif, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">TOTAL</th>
                        <th class="text-end">Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}</th>
                        <th class="text-center">{{ $proyek->anggaran > 0 ? round(($totalDanaTerpakai / $proyek->anggaran) * 100, 2) : 0 }}%</th>
                        <th class="text-end">Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <!-- Progress Bar Anggaran -->
            <div style="margin-top: 10px;">
                <div style="display: flex; justify-content: space-between; font-size: 10pt;">
                    <span>Penggunaan Anggaran</span>
                    <span>{{ $proyek->anggaran > 0 ? round(($totalDanaTerpakai / $proyek->anggaran) * 100, 1) : 0 }}%</span>
                </div>
                <div class="progress-bar-bg" style="height: 20px;">
                    @php
                        $persenAnggaran = $proyek->anggaran > 0 ? ($totalDanaTerpakai / $proyek->anggaran) * 100 : 0;
                        $classBar = $persenAnggaran >= 100 ? 'danger' : ($persenAnggaran >= 80 ? 'warning' : '');
                    @endphp
                    <div class="progress-bar-fill {{ $classBar }}" 
                         style="width: {{ min($persenAnggaran, 100) }}%; display: flex; align-items: center; justify-content: center; color: white; font-size: 9pt;">
                        {{ number_format($persenAnggaran, 1) }}%
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 8pt; margin-top: 2px;">
                    <span>Rp 0</span>
                    <span>Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</span>
                </div>
            </div>
        @else
            <p class="text-center" style="padding: 20px 0;">Belum ada tahap monitoring yang terverifikasi</p>
        @endif

        <!-- ===== RIWAYAT MONITORING ===== -->
        <div class="section-title">D. RIWAYAT MONITORING</div>
        @if($monitorings->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="10%">Progres</th>
                        <th width="20%">Status</th>
                        <th width="15%">Dana</th>
                        <th width="15%">Status Verifikasi</th>
                        <th width="20%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monitorings->sortBy('tanggal_monitoring') as $i => $m)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}</td>
                            <td class="text-center">{{ $m->progres }}%</td>
                            <td>{{ $m->status_progres }}</td>
                            <td class="text-end">Rp {{ number_format($m->dana_terpakai ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($m->status_verifikasi == 'pending')
                                    <span class="badge-warning">Pending</span>
                                @elseif($m->status_verifikasi == 'approved')
                                    <span class="badge-success">Approved</span>
                                @elseif($m->status_verifikasi == 'rejected')
                                    <span class="badge-secondary">Rejected</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $m->keterangan ? Str::limit(strip_tags($m->keterangan), 40) : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center" style="padding: 20px 0;">Belum ada data monitoring</p>
        @endif

        <!-- ===== KESIMPULAN ===== -->
        <div class="section-title">E. KESIMPULAN</div>
        <p class="text-justify">
            Proyek <strong>{{ $proyek->nama_pembangunan }}</strong> dengan total anggaran 
            <strong>Rp {{ number_format($proyek->anggaran, 0, ',', '.') }}</strong> 
            telah mencapai progres <strong>{{ $proyek->progress }}%</strong>.
        </p>
        <p class="text-justify mt-1">
            @if($proyek->status == 'selesai')
                Proyek ini telah selesai dilaksanakan dengan total dana terpakai 
                <strong>Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}</strong> 
                dari total anggaran yang tersedia.
            @elseif($proyek->status == 'berjalan')
                Proyek ini masih dalam tahap pelaksanaan dengan total dana terpakai 
                <strong>Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}</strong>.
            @else
                Proyek ini masih dalam tahap perencanaan.
            @endif
        </p>

        <!-- ===== TANDA TANGAN ===== -->
        <div class="tanda-tangan">
            <table class="ttd-table">
                <tr>
                    <td style="width: 60%;">
                        <div style="font-size: 10pt;">Diketahui oleh,</div>
                    </td>
                    <td class="ttd-content">
                        <div>Kutabuluh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
                        <div style="margin-top: 5px;">Kepala Desa Kutabuluh,</div>
                        <br><br><br>
                        <div><u><strong>({{ $kepalaDesa->nama_user ?? '_____________________' }})</strong></u></div>
                        <div style="font-size: 9pt; margin-top: 3px;">Cap/Stempel Desa Kutabuluh</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div>Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh (SISMOLEV)</div>
            <div class="page-number">Halaman {PAGENO}</div>
        </div>
    </div>

</body>
</html>