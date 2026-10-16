<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan {{ ucfirst($laporan->jenis_laporan) }} - {{ $pembangunan->nama_pembangunan }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-kab-karo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-kab-karo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo-kab-karo.png') }}" type="image/x-icon">

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
            padding-top: 50px;
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
        
        .cover-periode {
            font-size: 13pt;
            margin-bottom: 5px;
        }
        
        .cover-nomor {
            font-size: 11pt;
            margin-bottom: 15px;
            color: #555;
        }
        
        .cover-nama-pembangunan {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .cover-lokasi {
            font-size: 12pt;
            color: #555;
        }
        
        .cover-footer {
            position: absolute;
            bottom: 60px;
            left: 0;
            right: 0;
            text-align: center;
        }
        
        .cover-footer .cover-tahun {
            font-size: 16pt;
            font-weight: bold;
        }

        /* ========== KOP SURAT RESMI ========== */
        .kop-surat {
            margin-bottom: 15px;
            overflow: hidden;
            width: 100%;
        }

        .kop-kiri {
            float: left;
            width: 70%;
        }

        .kop-kanan {
            float: right;
            width: 25%;
            text-align: right;
        }

        .logo-garuda {
            width: 70px;
            height: auto;
        }

        .pemerintah-kabupaten {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .pemerintah-kecamatan {
            font-size: 12pt;
            font-weight: bold;
            margin: 2px 0;
        }

        .pemerintah-desa {
            font-size: 15pt;
            font-weight: bold;
            margin: 5px 0;
            text-decoration: underline;
        }

        .alamat-desa {
            font-size: 9pt;
            margin: 3px 0;
            line-height: 1.3;
        }

        .clearfix {
            clear: both;
        }

        .kop-line {
            margin-top: 8px;
            border-bottom: 2px solid #000000;
        }

        /* ========== TABEL ========== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .info-table th {
            background-color: #f5f5f5;
            width: 28%;
            font-weight: bold;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            background: #f0f0f0;
            padding: 6px 12px;
            margin: 15px 0 10px 0;
            border-left: 5px solid #1e3c72;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            text-align: left;
            font-size: 10pt;
            vertical-align: top;
        }

        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        /* ========== DOKUMENTASI ========== */
        .dokumentasi-keterangan {
            font-size: 11pt;
            text-align: justify;
            color: #333;
            margin-bottom: 20px;
            padding: 12px 15px;
            background: #f0f5f9;
            border-radius: 8px;
            border-left: 4px solid #1e3c72;
            line-height: 1.5;
        }

        .dokumentasi-wrapper {
            margin: 10px 0;
            overflow: hidden;
        }

        .dokumentasi-item {
            float: left;
            width: 23%;
            margin: 0 1% 15px 1%;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #fafafa;
            text-align: center;
            page-break-inside: avoid;
        }

        .dokumentasi-item img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .clearfix-dok {
            clear: both;
        }

        /* ========== TANDA TANGAN ========== */
        .tanda-tangan {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .ttd-table {
            width: 100%;
            margin-top: 20px;
            border: none;
        }

        .ttd-table td {
            border: none;
            padding: 5px;
        }

        .ttd-content {
            text-align: center;
            width: 260px;
        }

        .ttd-stempel {
            font-size: 9pt;
            margin-top: 3px;
        }

        /* ========== UTILITY ========== */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-justify {
            text-align: justify;
        }

        .break-word {
            word-wrap: break-word;
            word-break: break-word;
        }

        td,
        th {
            word-break: break-word;
        }
    </style>
</head>

<body>

    <!-- ==================== COVER (Tanpa Header & Footer) ==================== -->
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
        
        <div class="cover-judul-laporan">LAPORAN {{ strtoupper($laporan->jenis_laporan) }}</div>
        {{-- <div class="cover-periode">Periode: {{ $laporan->periode }}</div> --}}
        <div class="cover-nomor">Nomor: 001/SISMOLEV/{{ $laporan->id_laporan }}/{{ date('Y') }}</div>
        
        <div class="cover-nama-pembangunan">{{ $pembangunan->nama_pembangunan }}</div>
        <div class="cover-lokasi">{{ $pembangunan->lokasi }}</div>

        <div class="cover-footer">
            <div class="cover-tahun">{{ date('Y') }}</div>
        </div>
    </div>

    <!-- ==================== RESET NOMOR HALAMAN SETELAH COVER ==================== -->
    <setpagecounter page="1" />

    <!-- ==================== HEADER KOP (Halaman 1 isi) ==================== -->
    <htmlpageheader name="firstHeader">
        <div class="kop-surat">
            <div class="kop-kiri">
                <div class="pemerintah-kabupaten">PEMERINTAH KABUPATEN KARO</div>
                <div class="pemerintah-kecamatan">KECAMATAN KUTABULUH</div>
                <div class="pemerintah-desa">DESA KUTABULUH</div>
                <div class="alamat-desa">
                    Jl. Raya Kutabuluh, Desa Kutabuluh, Kec. Kutabuluh, Kab. Karo<br>
                    Provinsi Sumatera Utara, Kode Pos 22155
                </div>
            </div>
            <div class="kop-kanan">
                <img src="{{ public_path('images/logo-kab-karo.png') }}" class="logo-garuda" alt="Logo"
                    onerror="this.style.display='none'">
            </div>
            <div class="clearfix"></div>
            <div class="kop-line"></div>
        </div>
    </htmlpageheader>

    <!-- ==================== HEADER HALAMAN BERIKUTNYA ==================== -->
    <htmlpageheader name="nextHeader">
        <div style="text-align: left; font-size: 9pt; color: #666; padding-bottom: 5px; border-bottom: 1px solid #ccc;">
            {{-- <em>{{ $pembangunan->nama_pembangunan }} - {{ $pembangunan->lokasi }}</em> --}}
        </div>
    </htmlpageheader>

    <!-- ==================== FOOTER HALAMAN ISI ==================== -->
    <htmlpagefooter name="myFooter">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; text-align: left; font-size: 8pt; color: #666; border: none;">
                    SISMOLEV - Desa Kutabuluh - {{ $laporan->periode }}
                </td>
                <td style="width: 30%; text-align: center; font-size: 8pt; color: #666; border: none;">
                    {{ $pembangunan->nama_pembangunan }} - {{ $pembangunan->lokasi }}
                </td>
                <td style="width: 20%; text-align: right; font-size: 9pt; color: #333; border: none;">
                    <strong>Halaman {PAGENO}</strong>
                </td>
            </tr>
        </table>
        <div style="border-top: 1px solid #ccc; margin-top: 3px;"></div>
    </htmlpagefooter>

    <!-- ==================== SET HEADER & FOOTER ==================== -->
    <sethtmlpageheader name="firstHeader" page="1" value="on" />
    <sethtmlpageheader name="nextHeader" page="2-" value="on" />
    <sethtmlpagefooter name="myFooter" page="1-" value="on" />

    <!-- ==================== HALAMAN 1: KOP & ISI ==================== -->
    
    <!-- DATA DASAR -->
    <table class="info-table">
        <tr>
            <th width="28%">Periode Laporan</th>
            <td>{{ $laporan->periode }}</td>
        </tr>
        <tr>
            <th>Tanggal Cetak</th>
            <td>{{ \Carbon\Carbon::parse($laporan->tanggal_cetak)->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>Dibuat Oleh</th>
            <td>{{ $laporan->user->nama_user }}
                ({{ $laporan->user->level == 'kepala_desa' ? 'Kepala Desa' : ($laporan->user->level == 'admin' ? 'Administrator' : 'Perangkat Desa') }})
            </td>
        </tr>
    </table>

    <!-- ==================== URUTAN DINAMIS ==================== -->
    @php
        $sectionCounter = 0;
        $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
        $hasMonitoring = isset($monitorings) && count($monitorings) > 0;
        $hasEvaluasi = isset($evaluasis) && count($evaluasis) > 0;
        $hasDokumentasi = isset($dokumentasis) && count($dokumentasis) > 0;
    @endphp

    <!-- A. INFORMASI PEMBANGUNAN -->
    @php $huruf = $letters[$sectionCounter]; $sectionCounter++; @endphp
    <div class="section-title">{{ $huruf }}. INFORMASI PEMBANGUNAN</div>
    <table class="info-table">
        <tr>
            <th width="28%">Nama Pembangunan</th>
            <td>{{ $pembangunan->nama_pembangunan }}</td>
        </tr>
        <tr>
            <th>Lokasi</th>
            <td>{{ $pembangunan->lokasi }}</td>
        </tr>
        <tr>
            <th>Tahun Anggaran</th>
            <td>{{ $pembangunan->tahun_anggaran }}</td>
        </tr>
        <tr>
            <th>Sumber Dana</th>
            <td>{{ $pembangunan->sumber_dana }}</td>
        </tr>
        <tr>
            <th>Anggaran</th>
            <td>Rp {{ number_format($pembangunan->anggaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tanggal Pelaksanaan</th>
            <td>{{ \Carbon\Carbon::parse($pembangunan->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }} s.d.
                {{ \Carbon\Carbon::parse($pembangunan->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($pembangunan->status == 'direncanakan') Direncanakan
                @elseif($pembangunan->status == 'berjalan') Sedang Berjalan
                @else Selesai @endif
            </td>
        </tr>
        <tr>
            <th>Progres Terkini</th>
            <td>{{ $pembangunan->progress }}%</td>
        </tr>
        <tr>
            <th>Penanggung Jawab / Mandor</th>
            <td>{{ $pembangunan->mandor ?: '-' }}</td>
        </tr>
    </table>

    <!-- B. RIWAYAT MONITORING -->
    @if($hasMonitoring)
        @php $huruf = $letters[$sectionCounter]; $sectionCounter++; @endphp
        <div style="page-break-before: always;"></div>
        <div class="section-title">{{ $huruf }}. RIWAYAT MONITORING</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="18%">Tanggal Monitoring</th>
                    <th width="8%">Progres</th>
                    <th width="27%">Status Pekerjaan</th>
                    <th width="42%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monitorings as $index => $m)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($m->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td class="text-center">{{ $m->progres }}%</td>
                        <td>{{ $m->status_progres }}</td>
                        <td class="break-word">{!! $m->keterangan ?: '-' !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- C. HASIL EVALUASI -->
    @if($hasEvaluasi)
        @php $huruf = $letters[$sectionCounter]; $sectionCounter++; @endphp
        <div style="page-break-before: always;"></div>
        <div class="section-title">{{ $huruf }}. HASIL EVALUASI</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal Evaluasi</th>
                    <th width="35%">Hasil Evaluasi</th>
                    <th width="20%">Kendala</th>
                    <th width="25%">Rekomendasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluasis as $index => $e)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($e->tanggal_evaluasi)->locale('id')->translatedFormat('d F Y') }}</td>
                        <td class="break-word">{!! strip_tags($e->hasil_evaluasi) !!}</td>
                        <td class="break-word">{!! strip_tags($e->kendala) ?: '-' !!}</td>
                        <td class="break-word">{!! strip_tags($e->rekomendasi) ?: '-' !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- D. DOKUMENTASI KEGIATAN -->
    @if(isset($dokumentasis) && count($dokumentasis) > 0)
        @php $huruf = $letters[$sectionCounter]; $sectionCounter++; @endphp
        <div style="page-break-before: always;"></div>
        <div class="section-title">{{ $huruf }}. DOKUMENTASI KEGIATAN</div>

        <div class="dokumentasi-keterangan">
            @php
                $keteranganUtama = '';
                foreach ($dokumentasis as $dok) {
                    if (!empty($dok->keterangan)) {
                        $keteranganUtama = $dok->keterangan;
                        break;
                    }
                }
                if (empty($keteranganUtama)) {
                    $keteranganUtama = 'Dokumentasi kegiatan pembangunan';
                }
            @endphp
            {{ strip_tags($keteranganUtama) }}
        </div>

        <div class="dokumentasi-wrapper">
            @foreach($dokumentasis as $index => $dok)
                <div class="dokumentasi-item">
                    <img src="{{ public_path('storage/' . $dok->foto) }}" alt="Dokumentasi {{ $index + 1 }}"
                        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'200\' height=\'150\'%3E%3Crect width=\'200\' height=\'150\' fill=\'%23cccccc\'/%3E%3Ctext x=\'100\' y=\'75\' dominant-baseline=\'middle\' text-anchor=\'middle\' fill=\'%23666666\'%3EGambar Tidak Tersedia%3C/text%3E%3C/svg%3E'">
                </div>
            @endforeach
            <div class="clearfix-dok"></div>
        </div>
    @endif

    <!-- E. KESIMPULAN -->
    @php $huruf = $letters[$sectionCounter]; $sectionCounter++; @endphp
    <div style="page-break-before: always;"></div>
    <div class="section-title">{{ $huruf }}. KESIMPULAN</div>
    <p class="text-justify">
        Berdasarkan data yang telah dikumpulkan, pembangunan <strong>{{ $pembangunan->nama_pembangunan }}</strong>
        di Desa Kutabuluh telah berjalan dengan progres <strong>{{ $pembangunan->progress }}%</strong>
        dan status <strong>
            @if($pembangunan->status == 'direncanakan') Direncanakan
            @elseif($pembangunan->status == 'berjalan') Sedang Berjalan
            @else Selesai @endif
        </strong>.
    </p>

    <!-- F. PENUTUP -->
    @php $huruf = $letters[$sectionCounter]; @endphp
    <div class="section-title">{{ $huruf }}. PENUTUP</div>
    <p class="text-justify">
        Demikian laporan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <!-- ==================== TANDA TANGAN ==================== -->
    <div class="tanda-tangan">
        <table class="ttd-table">
            <tr>
                <td style="width: 60%;"></td>
                <td class="ttd-content">
                    <div>Kutabuluh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 8px;">Kepala Desa Kutabuluh,</div>
                    <br><br><br>
                    <div><u><strong>({{ $kepalaDesa->nama_user ?? '_____________________' }})</strong></u></div>
                    <div class="ttd-stempel">Cap/Stempel Desa Kutabuluh</div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>