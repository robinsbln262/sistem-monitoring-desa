<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Laporan Proyek {{ $proyek->nama_pembangunan }}</title>
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

            /* ========== KOP SURAT (DARI LAPORAN TAHUNAN) ========== */

            .kop-surat {
                width: 100%;

                border-collapse: collapse;

                margin-bottom: 8px;

                border-bottom: 3px double #111;

                padding-bottom: 8px;
            }

            .kop-surat td {
                vertical-align: middle;

                padding-bottom: 4px;
            }

            .kop-logo-cell {
                width: 105px;

                text-align: left;
            }

            .kop-logo {
                display: block;

                width: 68px;

                height: auto;

                margin: 0 auto;
            }

            .kop-surat-content {
                text-align: center;
            }

            .kop-spacer {
                width: 105px;
            }

            .pemerintah-kabupaten {
                font-size: 11pt;

                font-weight: bold;

                letter-spacing: 1px;
            }

            .pemerintah-kecamatan {
                font-size: 9pt;

                font-weight: bold;
            }

            .pemerintah-desa {
                font-size: 12pt;

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

                margin: 9px 0 7px 0;
            }

            .judul-laporan h3 {
                font-size: 12pt;

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

            .info-table th,
            .info-table td {
                border: 1px solid #000000;

                padding: 3px 5px;

                text-align: left;

                vertical-align: top;
            }

            .info-table th {
                background-color: #f5f5f5;

                font-weight: bold;

                width: 30%;
            }

            .section-title {
                font-size: 9pt;

                font-weight: bold;

                background: #f0f0f0;

                padding: 4px 7px;

                margin: 8px 0 5px 0;

                border-left: 4px solid #1e3c72;
            }

            .data-table {
                width: 100%;

                border-collapse: collapse;

                margin-bottom: 6px;

                font-size: 9pt;
            }

            .data-table th,
            .data-table td {
                border: 1px solid #000000;

                padding: 3px 4px;

                text-align: left;

                vertical-align: top;
            }

            .data-table th {
                background-color: #f5f5f5;

                font-weight: bold;

                text-align: center;
            }

            .data-table .center {
                text-align: center;
            }

            .data-table .right {
                text-align: right;
            }

            /* ========== BADGE ========== */

            .badge {
                display: inline-block;

                padding: 1px 5px;

                font-size: 9pt;

                font-weight: bold;
            }

            .badge-success {
                background: #10b981;

                color: #ffffff;
            }

            .badge-warning {
                background: #f59e0b;

                color: #ffffff;
            }

            .badge-secondary {
                background: #6c757d;

                color: #ffffff;
            }

            .badge-info {
                background: #0ea5e9;

                color: #ffffff;
            }

            /* ========== PROGRESS BAR ========== */

            .progress-container {
                width: 100%;

                border: 1px solid #999;

                height: 14px;

                margin-top: 4px;
            }

            .progress-bar {
                height: 100%;

                background: #1e3c72;
            }

            /* ========== TEXT ========== */

            .text-justify {
                text-align: justify;
            }

            .text-center {
                text-align: center;
            }

            .text-green {
                color: #008000;
            }

            .text-red {
                color: #c00000;
            }

            /* ========== RATING STARS ========== */

            .rating-stars {
                color: #f59e0b;

                font-size: 9pt;

                letter-spacing: 2px;
            }

            /* ========== TANDA TANGAN ========== */

            .tanda-tangan {
                margin-top: 12px;
            }

            .ttd-table {
                width: 100%;

                margin-top: 8px;
            }

            .ttd-table td {
                border: none;

                padding: 5px;
            }

            .ttd-content {
                text-align: center;

                width: 250px;
            }

            /* ========== FOOTER ========== */

            .footer {
                margin-top: 8px;

                padding-top: 4px;

                border-top: 1px solid #ccc;

                text-align: center;

                font-size: 8pt;

                color: #666;
            }

            .page-number {
                text-align: right;

                font-size: 9pt;

                margin-top: 6px;
            }

            .small {
                font-size: 9pt;
            }
        </style>
    </head>
    <body>
        <!-- ==================== HALAMAN ISI ==================== -->
        <div style="padding: 6px 0;">
            <!-- KOP SURAT (MENGGUNAKAN STYLES & HEADER SEPERTI LAPORAN TAHUNAN) -->
            <htmlpageheader name="kopSurat">
                <table class="kop-surat">
                    <tr>
                        <td class="kop-logo-cell">
                            <img
                                src="{{ public_path('images/logo-kab-karo.png') }}"
                                class="kop-logo"
                                alt="Logo Kabupaten Karo"
                                onerror="this.style.display='none'">
                        </td>
                        <td class="kop-surat-content">
                            <div class="pemerintah-kabupaten">PEMERINTAH KABUPATEN KARO</div>

                            <div class="pemerintah-kecamatan">KECAMATAN KUTABULUH</div>

                            <div class="pemerintah-desa">DESA KUTABULUH</div>

                            <div class="alamat-desa">
                                Jl. Raya Kutabuluh, Desa Kutabuluh, Kec. Kutabuluh, Kab. Karo
                                <br>
                                Provinsi Sumatera Utara, Kode Pos 22155
                            </div>
                        </td>
                        <td class="kop-spacer"></td>
                    </tr>
                </table>
            </htmlpageheader>
            <sethtmlpageheader name="kopSurat" value="on" show-this-page="1" />
            <!-- JUDUL LAPORAN -->
            <div class="judul-laporan">
                <h3>LAPORAN PROYEK PEMBANGUNAN</h3>

                <p>Tahun Anggaran {{ $proyek->tahun_anggaran }}</p>
            </div>
            <!-- A. INFORMASI PROYEK -->
            <div class="section-title">A. INFORMASI PROYEK</div>

            <table class="info-table">
                <tr>
                    <th>Nama Proyek</th>
                    <td><strong>{{ $proyek->nama_pembangunan }}</strong></td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>{{ $proyek->lokasi ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Tahun Anggaran</th>
                    <td>{{ $proyek->tahun_anggaran ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Sumber Dana</th>
                    <td>{{ $proyek->sumber_dana ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Bidang Pekerjaan</th>
                    <td>{{ $proyek->bidang_pekerjaan ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Anggaran</th>
                    <td><strong>Rp {{ number_format($proyek->anggaran ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
                @if ($hargaDeal !== null)
                    <tr>
                        <th>Harga Deal / Penawaran</th>
                        <td>
                            <strong>Rp {{ number_format($hargaDeal, 0, ',', '.') }}</strong>
                            @if ($rekanan)
                                <br>
                                <span class="small">
                                    Disepakati dengan:
                                    <strong>{{ $rekanan->nama_user }}</strong>
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Selisih Anggaran</th>
                        <td>
                            <strong>Rp {{ number_format(abs($selisihAnggaran), 0, ',', '.') }}</strong>
                            @if ($selisihAnggaran > 0)
                                <span class="badge badge-success">Hemat</span>
                            @elseif ($selisihAnggaran < 0)
                                <span class="badge badge-warning">Over Budget</span>
                            @else
                                <span class="badge badge-secondary">Sama</span>
                            @endif
                        </td>
                    </tr>
                @endif

                <tr>
                    <th>Tanggal Pelaksanaan</th>
                    <td>
                        @if ($proyek->tanggal_mulai)
                            {{ \Carbon\Carbon::parse($proyek->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif

                        &nbsp; s/d &nbsp;
                        @if ($proyek->tanggal_selesai)
                            {{ \Carbon\Carbon::parse($proyek->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status Proyek</th>
                    <td>
                        @if ($proyek->status == 'direncanakan')
                            <span class="badge badge-secondary">Direncanakan</span>
                        @elseif ($proyek->status == 'berjalan')
                            <span class="badge badge-warning">Berjalan</span>
                        @else
                            <span class="badge badge-success">Selesai</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Progres Proyek</th>
                    <td>
                        <strong>{{ $proyek->progress ?? 0 }}%</strong>
                        <div class="progress-container">
                            <div class="progress-bar" style="width: {{ min(100, max(0, $proyek->progress ?? 0)) }}%;"></div>
                        </div>
                    </td>
                </tr>
                @if ($rekanan)
                    <tr>
                        <th>Rekanan Pelaksana</th>
                        <td>
                            <strong>{{ $rekanan->nama_user }}</strong>
                            @if ($rekanan->bidang_keahlian)
                                <br>
                                <span class="small">Bidang Keahlian: {{ $rekanan->bidang_keahlian }}</span>
                            @endif
                        </td>
                    </tr>
                @endif

                <tr>
                    <th>Catatan</th>
                    <td>{{ $proyek->catatan ?: '-' }}</td>
                </tr>
                <tr>
                    <th>Dibuat Oleh</th>
                    <td>{{ $proyek->user->nama_user ?? '-' }}</td>
                </tr>
            </table>
            <!-- B. RINGKASAN ANGGARAN DAN REALISASI -->
            <div class="section-title">B. RINGKASAN ANGGARAN DAN REALISASI</div>

            <table class="info-table">
                <tr>
                    <th>Total Anggaran Proyek</th>
                    <td class="right"><strong>Rp {{ number_format($proyek->anggaran ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <th>Total Dana Terpakai</th>
                    <td class="right text-green">
                        <strong>Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr>
                    <th>Sisa Anggaran</th>
                    <td class="right {{ $sisaAnggaran < 0 ? 'text-red' : 'text-green' }}">
                        <strong>Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                <tr>
                    <th>Persentase Penggunaan Anggaran</th>
                    <td class="right"><strong>{{ number_format($persentaseTerpakai, 1, ',', '.') }}%</strong></td>
                </tr>
            </table>
            <!-- C. RINCIAN PENGELUARAN -->
            <div class="section-title">C. RINCIAN PENGELUARAN / REALISASI ANGGARAN</div>
            @if (isset($pengeluarans) && $pengeluarans->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="45%">Uraian Pengeluaran</th>
                            <th width="20%">Jumlah</th>
                            <th width="30%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalPengeluaran = 0; @endphp
                        @foreach ($pengeluarans->sortBy('tanggal') as $index => $pengeluaran)
                            @php

                                $jumlah = (float) ($pengeluaran->jumlah ?? 0);

                                $totalPengeluaran += $jumlah;

                            @endphp
                            <tr>
                                <td class="center">{{ $index + 1 }}</td>
                                <td>{{ $pengeluaran->uraian ?? $pengeluaran->keterangan ?? '-' }}</td>
                                <td class="right">Rp {{ number_format($jumlah, 0, ',', '.') }}</td>
                                <td>{{ $pengeluaran->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="2" class="right"><strong>TOTAL PENGELUARAN</strong></td>
                            <td class="right">
                                <strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @else
                <table class="data-table">
                    <tr>
                        <td class="center">Belum terdapat data pengeluaran untuk proyek ini.</td>
                    </tr>
                </table>
            @endif

            <!-- D. RIWAYAT MONITORING -->
            <div class="section-title">D. RIWAYAT MONITORING PROYEK</div>
            @if (isset($monitorings) && $monitorings->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="14%">Tanggal</th>
                            <th width="10%">Progres</th>
                            <th width="15%">Status Progres</th>
                            <th width="18%">Dana Terpakai</th>
                            <th width="38%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monitorings as $index => $monitoring)
                            <tr>
                                <td class="center">{{ $index + 1 }}</td>
                                <td>
                                    @if ($monitoring->tanggal_monitoring)
                                        {{ \Carbon\Carbon::parse($monitoring->tanggal_monitoring)->locale('id')->translatedFormat('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="center"><strong>{{ $monitoring->progres ?? 0 }}%</strong></td>
                                <td>{{ $monitoring->status_progres ?? '-' }}</td>
                                <td class="right">
                                    Rp {{ number_format($monitoring->dana_terpakai ?? 0, 0, ',', '.') }}
                                </td>
                                <td>{{ strip_tags($monitoring->keterangan ?? '') ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="data-table">
                    <tr>
                        <td class="center">Belum terdapat riwayat monitoring yang telah disetujui.</td>
                    </tr>
                </table>
            @endif

            <!-- E. EVALUASI PROYEK -->
            <div class="section-title">E. EVALUASI PROYEK</div>
            @if (isset($evaluasis) && $evaluasis->count() > 0)
                <table class="info-table" style="margin-bottom: 6px;">
                    <tr>
                        <th>Status Evaluasi Keseluruhan</th>
                        <td>
                            @php

                                $badgeClass = 'badge-secondary';

                                if ($statusEvaluasi == 'Sangat Baik') {

                                    $badgeClass = 'badge-success';

                                } elseif ($statusEvaluasi == 'Baik') {

                                    $badgeClass = 'badge-info';

                                } elseif ($statusEvaluasi == 'Cukup' || $statusEvaluasi == 'Perlu Perbaikan') {

                                    $badgeClass = 'badge-warning';

                                }

                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusEvaluasi }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah Evaluasi</th>
                        <td><strong>{{ $totalEvaluasi ?? $evaluasis->count() }}</strong> kali evaluasi</td>
                    </tr>
                    @if (isset($evaluasiTerbaru) && $evaluasiTerbaru)
                        <tr>
                            <th>Evaluasi Terakhir</th>
                            <td>
                                {{ \Carbon\Carbon::parse($evaluasiTerbaru->tanggal_evaluasi)->locale('id')->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                    @endif
                </table>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th width="28%">Hasil Evaluasi</th>
                            <th width="27%">Kendala</th>
                            <th width="28%">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluasis as $index => $evaluasi)
                            <tr>
                                <td class="center">{{ $index + 1 }}</td>
                                <td>
                                    {{ $evaluasi->tanggal_evaluasi

                                    ? \Carbon\Carbon::parse($evaluasi->tanggal_evaluasi)->locale('id')->translatedFormat('d F Y')

                                    : '-' }}
                                </td>
                                <td>{{ strip_tags($evaluasi->hasil_evaluasi ?? '') ?: '-' }}</td>
                                <td>{{ strip_tags($evaluasi->kendala ?? '') ?: '-' }}</td>
                                <td>{{ strip_tags($evaluasi->rekomendasi ?? '') ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="data-table">
                    <tr>
                        <td class="center">Belum terdapat data evaluasi untuk proyek ini.</td>
                    </tr>
                </table>
            @endif

            <!-- E.2 EVALUASI KINERJA REKANAN -->
            @if (isset($evaluasiRekanan) && $evaluasiRekanan->count() > 0)
                <div class="section-title">E.2 EVALUASI KINERJA REKANAN</div>

                <table class="info-table" style="margin-bottom: 6px;">
                    <tr>
                        <th>Rata-rata Rating</th>
                        <td>
                            <strong>{{ number_format($rataRating ?? 0, 1, ',', '.') }} / 5.0</strong>
                            @php

                                $ratingVal = (int) round($rataRating ?? 0);

                                $fullStar  = str_repeat('*', $ratingVal);

                                $emptyStar = str_repeat('-', max(0, 5 - $ratingVal));

                            @endphp
                            <span class="rating-stars">{{ $fullStar }}{{ $emptyStar }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Evaluasi Rekanan</th>
                        <td>
                            <strong>{{ $totalEvaluasiRekanan ?? $evaluasiRekanan->count() }}</strong>
                            evaluasi (approved)
                        </td>
                    </tr>
                </table>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="11%">Tanggal</th>
                            <th width="15%">Kualitas</th>
                            <th width="12%">Tepat Waktu</th>
                            <th width="10%">Progress</th>
                            <th width="8%">Rating</th>
                            <th width="39%">Hasil Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluasiRekanan as $index => $er)
                            <tr>
                                <td class="center">{{ $index + 1 }}</td>
                                <td>
                                    {{ $er->tanggal_evaluasi

                                    ? \Carbon\Carbon::parse($er->tanggal_evaluasi)->locale('id')->translatedFormat('d F Y')

                                    : '-' }}
                                </td>
                                <td>
                                    @php

                                        $kualitasLabel = match($er->kualitas_pekerjaan) {

                                            'sangat_baik' => 'Sangat Baik',

                                            'baik'        => 'Baik',

                                            'cukup'       => 'Cukup',

                                            'kurang'      => 'Kurang',

                                            default       => '-',

                                        };

                                    @endphp
                                    {{ $kualitasLabel }}
                                </td>
                                <td class="center">
                                    @if ($er->selesai_tepat_waktu)
                                        <span class="badge badge-success">Tepat</span>
                                    @else
                                        <span class="badge badge-warning">
                                            Telat {{ $er->keterlambatan_hari ?? 0 }}h
                                        </span>
                                    @endif
                                </td>
                                <td class="center">{{ $er->persentase_penyelesaian ?? 0 }}%</td>
                                <td class="center"><strong>{{ $er->rating ?? 0 }}</strong>/5</td>
                                <td>{{ strip_tags($er->hasil_akhir ?? '') ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- G. PENUTUP -->
            <div class="section-title">G. PENUTUP</div>

            <div class="text-justify">
                <p>
                    Demikian laporan proyek pembangunan
                    <strong>{{ $proyek->nama_pembangunan }}</strong>
                    ini dibuat sebagai bentuk pertanggungjawaban, monitoring, evaluasi, dan transparansi
                    pelaksanaan pembangunan di Desa Kutabuluh.
                </p>
                <br>
                <p>
                    Berdasarkan data yang tercatat dalam sistem, proyek memiliki anggaran sebesar
                    <strong>Rp {{ number_format($proyek->anggaran ?? 0, 0, ',', '.') }}</strong>
                    dengan realisasi penggunaan anggaran sebesar
                    <strong>Rp {{ number_format($totalDanaTerpakai, 0, ',', '.') }}</strong>
                    dan sisa anggaran sebesar
                    <strong>Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</strong>
                    .
                </p>
                @if (isset($evaluasis) && $evaluasis->count() > 0)
                    <br>
                    <p>
                        Berdasarkan hasil evaluasi yang telah dilakukan, proyek ini memperoleh status evaluasi
                        <strong>{{ $statusEvaluasi }}</strong>
                        dengan total
                        <strong>{{ $totalEvaluasi ?? $evaluasis->count() }}</strong>
                        kali evaluasi.
                    </p>
                @endif

                @if (isset($evaluasiRekanan) && $evaluasiRekanan->count() > 0)
                    <br>
                    <p>
                        Dari sisi kinerja rekanan pelaksana, diperoleh rata-rata rating sebesar
                        <strong>{{ number_format($rataRating ?? 0, 1, ',', '.') }} / 5.0</strong>
                        berdasarkan
                        <strong>{{ $totalEvaluasiRekanan ?? $evaluasiRekanan->count() }}</strong>
                        evaluasi yang telah diverifikasi.
                    </p>
                @endif

                <br>
                <p>
                    Laporan ini diharapkan dapat menjadi bahan dokumentasi, evaluasi, serta pertanggungjawaban
                    dalam pelaksanaan pembangunan Desa Kutabuluh.
                </p>
            </div>
            <!-- TANDA TANGAN -->
            <div class="tanda-tangan">
                <table class="ttd-table">
                    <tr>
                        <td style="width: 60%;"></td>
                        <td class="ttd-content">
                            <div>Kutabuluh, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</div>

                            <div style="margin-top: 5px;">Kepala Desa Kutabuluh,</div>
                            <br>
                            <br>
                            <div><u><strong>({{ $kepalaDesa->nama_user ?? '_____________________' }})</strong></u></div>

                            <div style="font-size: 9pt; margin-top: 3px;">Cap/Stempel Desa Kutabuluh</div>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- FOOTER & NOMOR HALAMAN OTOMATIS -->
            <htmlpagefooter name="footerLaporan">
                <div class="footer">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td style="width: 70%; text-align: left; border: none; font-size: 8pt; color: #666;">
                                Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh (SISMOLEV)
                                <br>
                                Laporan Proyek:
                                <strong>{{ $proyek->nama_pembangunan }}</strong>
                            </td>
                            <td
                                style="width: 30%; text-align: right; border: none; font-size: 9pt; font-weight: bold; color: #333;">
                                Halaman {PAGENO} dari {nbpg}
                            </td>
                        </tr>
                    </table>
                </div>
            </htmlpagefooter>
            <sethtmlpagefooter name="footerLaporan" value="on" show-this-page="1" />
        </div>
    </body>
</html>