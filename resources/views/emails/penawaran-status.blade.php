<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Penawaran Proyek</title>
</head>

<body style="margin:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#222;">

    <div style="max-width:680px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">

        <div style="padding:24px;background:#1e3c72;color:#fff;">
            <h2 style="margin:0 0 6px;">
                Sistem Monitoring Desa Kutabuluh
            </h2>

            <div style="font-size:14px;">
                Pemberitahuan Status Penawaran
            </div>
        </div>

        <div style="padding:28px;">

            <p>
                Yth.
                <strong>{{ $rekanan->nama_user }}</strong>,
            </p>

            {{-- STATUS DITERIMA --}}
            @if ($status === 'diterima')

                <h3 style="color:#198754;">
                    Penawaran Anda Disetujui
                </h3>

                <p>
                    Selamat, penawaran Anda telah
                    <strong>disetujui</strong>
                    untuk proyek berikut:
                </p>

            {{-- STATUS DIPERTIMBANGKAN --}}
            @elseif ($status === 'dipertimbangkan')

                <h3 style="color:#b58100;">
                    Penawaran Anda Dipertimbangkan
                </h3>

                <p>
                    Penawaran Anda saat ini masih dalam proses
                    <strong>pertimbangan</strong>.
                </p>

            {{-- STATUS DITOLAK --}}
            @else

                <h3 style="color:#dc3545;">
                    Penawaran Anda Ditolak
                </h3>

                <p>
                    Penawaran Anda untuk proyek berikut telah
                    <strong>ditolak</strong>.
                </p>

            @endif


            {{-- DETAIL PROYEK --}}
            <table
                cellpadding="8"
                cellspacing="0"
                width="100%"
                style="border-collapse:collapse;margin:20px 0;"
            >

                <tr>
                    <td style="border-bottom:1px solid #eee;width:42%;">
                        <strong>Proyek</strong>
                    </td>

                    <td style="border-bottom:1px solid #eee;">
                        {{ $proyek->nama_pembangunan }}
                    </td>
                </tr>


                <tr>
                    <td style="border-bottom:1px solid #eee;">
                        <strong>Lokasi</strong>
                    </td>

                    <td style="border-bottom:1px solid #eee;">
                        {{ $proyek->lokasi }}
                    </td>
                </tr>


                <tr>
                    <td style="border-bottom:1px solid #eee;">
                        <strong>Nilai Penawaran</strong>
                    </td>

                    <td style="border-bottom:1px solid #eee;">
                        Rp
                        {{ number_format(
                            (float) $penawaran->harga_penawaran_rekanan,
                            0,
                            ',',
                            '.'
                        ) }}
                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>Status</strong>
                    </td>

                    <td>

                        @if ($status === 'diterima')

                            <strong style="color:#198754;">
                                Disetujui
                            </strong>

                        @elseif ($status === 'dipertimbangkan')

                            <strong style="color:#b58100;">
                                Dipertimbangkan
                            </strong>

                        @else

                            <strong style="color:#dc3545;">
                                Ditolak
                            </strong>

                        @endif

                    </td>
                </tr>

            </table>


            {{-- CATATAN --}}
            @if ($penawaran->catatan)

                <div
                    style="padding:14px;background:#f8f9fa;border-radius:8px;margin-bottom:20px;"
                >

                    <strong>Catatan:</strong>

                    <br>

                    {{ $penawaran->catatan }}

                </div>

            @endif


            <p>
                Silakan login ke sistem untuk melihat informasi
                proyek dan penawaran secara lengkap.
            </p>


            <p style="margin-top:28px;">

                Hormat kami,

                <br>

                <strong>
                    Sistem Monitoring dan Evaluasi
                    Pembangunan Desa Kutabuluh
                </strong>

            </p>

        </div>

    </div>

</body>

</html>