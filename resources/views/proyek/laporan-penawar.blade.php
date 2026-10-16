<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Penawar Proyek</title>

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

        /* ========== TOOLBAR (HANYA TAMPIL DI BROWSER) ========== */
        .toolbar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 9999;
        }

        .btn-toolbar {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 12pt;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
        }

        .btn-toolbar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        }

        .btn-print {
            background: #1e3c72;
            color: #fff;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
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

        /* ========== STATUS BADGE ========== */
        .status {
            display: inline-block;
            padding: 2px 8px;
            font-size: 9pt;
            font-weight: bold;
            border-radius: 3px;
        }

        .status-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-warning {
            background: #fff3cd;
            color: #664d03;
        }

        .status-danger {
            background: #f8d7da;
            color: #842029;
        }

        .status-info {
            background: #cff4fc;
            color: #055160;
        }

        .status-secondary {
            background: #e2e3e5;
            color: #41464b;
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
        .text-right { text-align: right; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }

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

        .project-block {
            page-break-inside: avoid;
        }

        /* ========== PRINT ========== */
        @media print {
            @page {
                size: A4 landscape;
                margin: 15mm 12mm;
            }

            body {
                padding: 0;
                font-size: 10pt;
            }

            .toolbar {
                display: none !important;
            }

            .cover-page {
                page-break-after: always;
            }

            .project-block {
                page-break-inside: avoid;
            }

            .data-table {
                font-size: 9pt;
            }

            .data-table th {
                background: #f5f5f5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .status {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .section-title {
                background: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    {{-- =====================================================
         TOOLBAR (HANYA TAMPIL DI BROWSER, TIDAK DICETAK)
    ====================================================== --}}
    <div class="toolbar">
        <button type="button" class="btn-toolbar btn-print" onclick="window.print()">
            🖨️ Cetak / Simpan PDF
        </button>
        <a href="{{ route('proyek.index') }}" class="btn-toolbar btn-back">
            ← Kembali
        </a>
    </div>

    {{-- =====================================================
         COVER PAGE
    ====================================================== --}}
    <div class="cover-page">
        <img src="{{ asset('images/logo-kab-karo.png') }}" class="cover-logo" alt="Logo"
             onerror="this.style.display='none'">

        <div class="cover-title-utama">PEMERINTAH KABUPATEN KARO</div>
        <div class="cover-title-sub">KECAMATAN KUTABULUH</div>
        <div class="cover-title-desa">DESA KUTABULUH</div>
        <div class="cover-alamat">
            Jl. Raya Kutabuluh, Desa Kutabuluh, Kec. Kutabuluh, Kab. Karo<br>
            Provinsi Sumatera Utara, Kode Pos 22155
        </div>

        <div class="cover-line"></div>

        <div class="cover-judul-laporan">LAPORAN DAFTAR PENAWAR</div>
        <div class="cover-tahun">DAFTAR PENAWAR PROYEK</div>

        <div class="cover-footer">
            <div style="font-size: 12pt; font-weight: bold;">SISMOLEV</div>
            <div style="font-size: 10pt;">Sistem Informasi Monitoring dan Evaluasi Pembangunan</div>
            <div style="font-size: 9pt; margin-top: 5px;">Desa Kutabuluh - Kabupaten Karo</div>
        </div>
    </div>

    {{-- =====================================================
         HALAMAN ISI
    ====================================================== --}}
    <div style="padding: 20px 0;">

        {{-- KOP SURAT --}}
        <div class="kop-surat">
            <div class="pemerintah-kabupaten">PEMERINTAH KABUPATEN KARO</div>
            <div class="pemerintah-kecamatan">KECAMATAN KUTABULUH</div>
            <div class="pemerintah-desa">DESA KUTABULUH</div>
            <div class="alamat-desa">
                Jl. Raya Kutabuluh, Desa Kutabuluh, Kec. Kutabuluh, Kab. Karo<br>
                Provinsi Sumatera Utara, Kode Pos 22155
            </div>
        </div>

        {{-- JUDUL --}}
        <div class="judul-laporan">
            <h3>LAPORAN DAFTAR PENAWAR PROYEK</h3>
            <p>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

        {{-- A. RINGKASAN --}}
        <div class="section-title">A. RINGKASAN PENAWARAN</div>
        <table class="info-table">
            <tr>
                <th>Total Proyek</th>
                <td>{{ $totalProyek }} Proyek</td>
            </tr>
            <tr>
                <th>Total Penawaran Masuk</th>
                <td>{{ $totalPenawaran }} Penawaran</td>
            </tr>
            <tr>
                <th>Penawaran Disetujui</th>
                <td><strong>{{ $totalDisetujui }}</strong> Penawaran</td>
            </tr>
            <tr>
                <th>Penawaran Dipertimbangkan</th>
                <td>{{ $totalDipertimbangkan }} Penawaran</td>
            </tr>
            <tr>
                <th>Penawaran Ditolak</th>
                <td>{{ $totalDitolak }} Penawaran</td>
            </tr>
        </table>

        {{-- B. DAFTAR PENAWAR PER PROYEK --}}
        <div class="section-title">B. DAFTAR PENAWAR PER PROYEK</div>

        @forelse($proyeks as $proyek)
            <div class="project-block" style="margin-bottom: 20px;">

                {{-- INFO PROYEK --}}
                <table class="info-table" style="margin-bottom: 8px;">
                    <tr>
                        <th style="width: 25%;">Nama Proyek</th>
                        <td><strong>{{ $loop->iteration }}. {{ $proyek->nama_pembangunan }}</strong></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $proyek->lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tahun Anggaran</th>
                        <td>{{ $proyek->tahun_anggaran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Anggaran</th>
                        <td>Rp {{ number_format($proyek->anggaran ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @if($proyek->harga_penawaran)
                        <tr>
                            <th>Harga Penawaran Acuan</th>
                            <td>Rp {{ number_format($proyek->harga_penawaran, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </table>

                {{-- TABEL PENAWAR --}}
                @if($proyek->rekananProyek && $proyek->rekananProyek->count())
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th width="16%">Nama Rekanan</th>
                                <th width="11%">No. Telepon</th>
                                <th width="12%">Bidang Keahlian</th>
                                <th width="6%">CV</th>
                                <th width="14%">Harga Penawaran</th>
                                <th width="10%">Tanggal</th>
                                <th width="10%">Status</th>
                                <th width="17%">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proyek->rekananProyek as $index => $penawaran)
                                @php
                                    $rekanan = $penawaran->rekanan;
                                    $status = $penawaran->status_penawaran;

                                    switch ($status) {
                                        case 'diterima':
                                            $statusText = 'Disetujui';
                                            $statusClass = 'status-success';
                                            break;
                                        case 'dipertimbangkan':
                                            $statusText = 'Dipertimbangkan';
                                            $statusClass = 'status-warning';
                                            break;
                                        case 'ditolak':
                                            $statusText = 'Ditolak';
                                            $statusClass = 'status-danger';
                                            break;
                                        case 'pending':
                                            $statusText = 'Menunggu';
                                            $statusClass = 'status-info';
                                            break;
                                        default:
                                            $statusText = ucfirst($status ?: '-');
                                            $statusClass = 'status-secondary';
                                            break;
                                    }

                                    $hargaAcuan = (float) ($proyek->harga_penawaran ?? 0);
                                    $hargaRekanan = (float) ($penawaran->harga_penawaran_rekanan ?? 0);
                                    $selisih = $hargaAcuan - $hargaRekanan;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $rekanan->nama_user ?? 'Rekanan' }}</strong>
                                        @if($rekanan && $rekanan->email)
                                            <br><small style="color:#666;">{{ $rekanan->email }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $rekanan->no_telp ?? '-' }}</td>
                                    <td>{{ $rekanan->bidang_keahlian ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($rekanan && $rekanan->cv)
                                            <span style="color:#198754; font-weight:bold;">✓ Ada</span>
                                        @else
                                            <span style="color:#999;">-</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <strong>Rp {{ number_format($hargaRekanan, 0, ',', '.') }}</strong>
                                        @if($hargaAcuan > 0 && $hargaRekanan > 0)
                                            <br>
                                            <small style="color:#666;">
                                                @if($selisih > 0)
                                                    Lebih rendah Rp {{ number_format($selisih, 0, ',', '.') }}
                                                @elseif($selisih < 0)
                                                    Lebih tinggi Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                                @else
                                                    Sama dengan acuan
                                                @endif
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($penawaran->created_at)
                                            {{ $penawaran->created_at->format('d-m-Y') }}
                                            <br>
                                            <small style="color:#666;">{{ $penawaran->created_at->format('H:i') }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="status {{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>{{ $penawaran->catatan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="font-style:italic; color:#666; margin: 5px 0;">
                        Belum ada rekanan yang mengikuti penawaran untuk proyek ini.
                    </p>
                @endif
            </div>
        @empty
            <div style="text-align:center; padding: 40px;">
                <h3>Belum Ada Data Penawaran</h3>
                <p style="color:#666;">Belum ada rekanan yang mengikuti penawaran proyek.</p>
            </div>
        @endforelse

        {{-- C. KESIMPULAN --}}
        <div class="section-title">C. KESIMPULAN</div>
        <p class="text-justify">
            Dari <strong>{{ $totalProyek }} proyek</strong> yang ditawarkan,
            terdapat <strong>{{ $totalPenawaran }} penawaran</strong> yang masuk dari para rekanan.
            Sebanyak <strong>{{ $totalDisetujui }} penawaran</strong> telah disetujui,
            <strong>{{ $totalDipertimbangkan }} penawaran</strong> masih dalam tahap pertimbangan,
            dan <strong>{{ $totalDitolak }} penawaran</strong> ditolak.
        </p>
        <p class="text-justify mt-1">
            @if($totalPenawaran > 0)
                Proses penawaran telah dilaksanakan sesuai dengan ketentuan yang berlaku,
                dengan memperhatikan kualifikasi, harga penawaran, dan kredibilitas masing-masing rekanan.
            @else
                Belum ada proses penawaran yang dilaksanakan pada periode ini.
            @endif
        </p>

        {{-- D. PENUTUP --}}
        <div class="section-title">D. PENUTUP</div>
        <p class="text-justify">
            Demikian laporan daftar penawar proyek ini dibuat sebagai bentuk transparansi dan
            pertanggungjawaban pelaksanaan pengadaan barang/jasa di Desa Kutabuluh.
            Semoga dapat menjadi bahan evaluasi dan referensi untuk kegiatan pengadaan di masa mendatang.
        </p>

        {{-- TANDA TANGAN --}}
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

        {{-- FOOTER --}}
        <div class="footer">
            <div>Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh (SISMOLEV)</div>
            <div class="page-number">Halaman {PAGENO}</div>
        </div>
    </div>

</body>
</html>