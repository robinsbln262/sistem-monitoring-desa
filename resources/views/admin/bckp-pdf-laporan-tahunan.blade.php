<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahunan {{ $periode }}</title>

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
            font-size: 20pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .cover-tahun {
            font-size: 16pt;
            font-weight: bold;
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
        
        <div class="cover-judul-laporan">LAPORAN TAHUNAN</div>
        <div class="cover-tahun">TAHUN {{ $periode }}</div>

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
            <h3>LAPORAN TAHUNAN</h3>
            <p>Periode {{ $periode }}</p>
        </div>

        <!-- RINGKASAN -->
        <div class="section-title">A. RINGKASAN TAHUNAN</div>
        <table class="info-table">
            <tr><th>Total Proyek</th><td>{{ $proyeks->count() }} Proyek</td></tr>
            <tr><th>Total Anggaran</th><td>Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</td></tr>
            <tr><th>Proyek Selesai</th><td>{{ $totalSelesai }} Proyek</td></tr>
            <tr><th>Proyek Berjalan</th><td>{{ $totalBerjalan }} Proyek</td></tr>
            <tr><th>Proyek Direncanakan</th><td>{{ $totalDirencanakan }} Proyek</td></tr>
            <tr><th>Rekanan Terlibat</th><td>{{ $proyeks->whereNotNull('rekanan_terpilih')->count() }} Rekanan</td></tr>
        </table>

        <!-- DAFTAR PROYEK -->
        <div class="section-title">B. DAFTAR PROYEK TAHUN {{ $periode }}</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Nama Proyek</th>
                    <th width="15%">Lokasi</th>
                    <th width="15%">Anggaran</th>
                    <th width="10%">Status</th>
                    <th width="15%">Rekanan</th>
                    <th width="10%">Progres</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proyeks as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $p->nama_pembangunan }}</td>
                    <td>{{ $p->lokasi }}</td>
                    <td>Rp {{ number_format($p->anggaran, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($p->status == 'direncanakan')
                            <span class="badge-secondary">Direncanakan</span>
                        @elseif($p->status == 'berjalan')
                            <span class="badge-warning">Berjalan</span>
                        @else
                            <span class="badge-success">Selesai</span>
                        @endif
                    </td>
                    <td>
                        @if($p->rekananSetuju->count() > 0)
                            @foreach($p->rekananSetuju as $r)
                                {{ $r->nama_user }}
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $p->progress }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- RINCIAN PENGELUARAN -->
        <div class="section-title">C. REKAPITULASI PENGGUNAAN ANGGARAN</div>

        @foreach($proyeks as $i => $p)
            @php
                $pengeluarans = $p->pengeluarans ?? collect();
                $totalPengeluaranProyek = $pengeluarans->sum('jumlah');
                $sisaAnggaranProyek = max(0, (float) $p->anggaran - (float) $totalPengeluaranProyek);

                $kategoriLabels = [
                    'upah_tenaga_kerja' => 'Upah Tenaga Kerja',
                    'material' => 'Material',
                    'sewa_alat' => 'Sewa Alat / Kendaraan',
                    'transportasi' => 'Transportasi',
                    'administrasi' => 'Administrasi',
                    'lainnya' => 'Pengeluaran Lainnya',
                ];
            @endphp

            <div style="margin-top: 15px; page-break-inside: avoid;">
                <table class="info-table">
                    <tr>
                        <th style="width:25%;">Proyek</th>
                        <td><strong>{{ $i + 1 }}. {{ $p->nama_pembangunan }}</strong></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $p->lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Anggaran</th>
                        <td>Rp {{ number_format($p->anggaran, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Dana Terpakai</th>
                        <td>Rp {{ number_format($totalPengeluaranProyek, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Sisa Anggaran</th>
                        <td>Rp {{ number_format($sisaAnggaranProyek, 0, ',', '.') }}</td>
                    </tr>
                </table>

                @if($pengeluarans->count())
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="16%">Kategori</th>
                                <th>Uraian Pengeluaran</th>
                                <th width="10%">Volume</th>
                                <th width="14%">Harga Satuan</th>
                                <th width="15%">Jumlah</th>
                                <th width="11%">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengeluarans as $pengeluaran)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $kategoriLabels[$pengeluaran->kategori] ?? ucfirst(str_replace('_', ' ', $pengeluaran->kategori)) }}</td>
                                    <td>
                                        <strong>{{ $pengeluaran->uraian }}</strong>
                                        @if($pengeluaran->keterangan)
                                            <br><small>{{ $pengeluaran->keterangan }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ rtrim(rtrim(number_format($pengeluaran->volume, 2, ',', '.'), '0'), ',') }}
                                        {{ $pengeluaran->satuan }}
                                    </td>
                                    <td>Rp {{ number_format($pengeluaran->harga_satuan, 0, ',', '.') }}</td>
                                    <td><strong>Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</strong></td>
                                    <td class="text-center">
                                        {{ optional($pengeluaran->tanggal_pengeluaran)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align:right;">TOTAL PENGELUARAN</th>
                                <th colspan="2">
                                    Rp {{ number_format($totalPengeluaranProyek, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p style="font-style:italic; margin: 5px 0 10px 0;">
                        Belum ada rincian penggunaan anggaran yang dicatat untuk proyek ini.
                    </p>
                @endif
            </div>
        @endforeach

        <div class="section-title">D. RINGKASAN REALISASI ANGGARAN</div>
        <table class="info-table">
            <tr>
                <th>Total Anggaran Seluruh Proyek</th>
                <td><strong>Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th>Total Dana Terpakai</th>
                <td><strong>Rp {{ number_format($totalDanaTerpakai ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th>Total Sisa Anggaran</th>
                <td><strong>Rp {{ number_format($totalSisaAnggaran ?? ($totalAnggaran - ($totalDanaTerpakai ?? 0)), 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <th>Persentase Realisasi</th>
                <td>
                    <strong>
                        {{ $totalAnggaran > 0 ? number_format((($totalDanaTerpakai ?? 0) / $totalAnggaran) * 100, 2, ',', '.') : '0,00' }}%
                    </strong>
                </td>
            </tr>
        </table>

        <!-- KESIMPULAN -->

        <div class="section-title">E. KESIMPULAN</div>
        <p class="text-justify">
            Pada periode {{ $periode }}, Desa Kutabuluh telah melaksanakan 
            <strong>{{ $proyeks->count() }} proyek</strong> dengan total anggaran 
            <strong>Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</strong>. 
            Dari jumlah tersebut, <strong>{{ $totalSelesai }} proyek</strong> telah selesai dilaksanakan, 
            <strong>{{ $totalBerjalan }} proyek</strong> masih dalam tahap pelaksanaan, 
            dan <strong>{{ $totalDirencanakan }} proyek</strong> masih dalam tahap perencanaan.
        </p>
        <p class="text-justify mt-1">
            @if($totalSelesai == $proyeks->count())
                Seluruh proyek telah selesai dilaksanakan dengan baik.
            @elseif($totalSelesai > $totalBerjalan)
                Mayoritas proyek telah selesai dan beberapa masih berjalan.
            @elseif($totalBerjalan > $totalSelesai)
                Sebagian besar proyek masih dalam pelaksanaan dan akan terus dimonitor.
            @else
                Proyek terus berjalan dan akan dievaluasi secara berkala.
            @endif
        </p>

        <!-- PENUTUP -->
        <div class="section-title">F. PENUTUP</div>
        <p class="text-justify">
            Demikian laporan tahunan ini dibuat sebagai bentuk pertanggungjawaban dan transparansi 
            pelaksanaan pembangunan di Desa Kutabuluh. Semoga dapat menjadi bahan evaluasi dan 
            perencanaan pembangunan yang lebih baik di masa mendatang.
        </p>

        <!-- TANDA TANGAN -->
        <div class="tanda-tangan">
            <table class="ttd-table">
                <tr>
                    <td style="width: 60%;"></td>
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