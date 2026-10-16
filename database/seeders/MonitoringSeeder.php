<?php

namespace Database\Seeders;

use App\Models\Monitoring;
use App\Models\Proyek;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua proyek yang ada
        $proyeks = Proyek::all();

        if ($proyeks->count() == 0) {
            $this->command->info('Tidak ada proyek ditemukan. Silakan jalankan ProyekSeeder terlebih dahulu.');

            return;
        }

        // Data monitoring untuk setiap proyek
        $monitorings = [];

        foreach ($proyeks as $proyek) {
            // Tentukan jumlah monitoring per proyek (3-6)
            $jumlah = rand(3, 6);

            for ($i = 0; $i < $jumlah; $i++) {
                // Progres meningkat setiap tahap
                $progres = rand(5, 20) + ($i * rand(10, 20));
                if ($progres > 100) {
                    $progres = 100;
                }

                // Status progres berdasarkan progres
                $statusProgres = $this->getStatusProgres($progres);

                // Status verifikasi random (60% approved, 25% pending, 15% rejected)
                $randStatus = rand(1, 100);
                if ($randStatus <= 60) {
                    $statusVerifikasi = 'approved';
                    $catatanVerifikasi = null;
                } elseif ($randStatus <= 85) {
                    $statusVerifikasi = 'pending';
                    $catatanVerifikasi = null;
                } else {
                    $statusVerifikasi = 'rejected';
                    $catatanVerifikasi = $this->getRandomRejectionNote();
                }

                // Dana terpakai (random 0 - 50% dari anggaran)
                $danaTerpakai = $progres > 0 ? rand(0, round($proyek->anggaran * 0.5)) : 0;

                // Tanggal monitoring (random antara tanggal mulai dan sekarang)
                $startDate = Carbon::parse($proyek->tanggal_mulai);
                $endDate = Carbon::now();
                $randomDate = Carbon::createFromTimestamp(
                    rand($startDate->timestamp, $endDate->timestamp)
                );

                // Keterangan
                $keterangan = $this->getRandomKeterangan($statusProgres);

                // Komentar admin (hanya jika approved dan random 50%)
                $komentarAdmin = null;
                if ($statusVerifikasi == 'approved' && rand(1, 100) <= 50) {
                    $komentarAdmin = $this->getRandomAdminComment();
                }

                $monitorings[] = [
                    'id_pembangunan' => $proyek->id_pembangunan,
                    'tanggal_monitoring' => $randomDate->format('Y-m-d'),
                    'progres' => $progres,
                    'status_progres' => $statusProgres,
                    'keterangan' => $keterangan,
                    'komentar_admin' => $komentarAdmin,
                    'foto_before' => $this->getRandomFoto('before'),
                    'foto_after' => $this->getRandomFoto('after'),
                    'dana_terpakai' => $danaTerpakai,
                    'status_verifikasi' => $statusVerifikasi,
                    'catatan_verifikasi' => $catatanVerifikasi,
                    'created_at' => $randomDate->format('Y-m-d H:i:s'),
                    'updated_at' => $randomDate->format('Y-m-d H:i:s'),
                ];
            }
        }

        // Insert data
        foreach ($monitorings as $data) {
            Monitoring::create($data);
        }

        $this->command->info('Berhasil menambahkan '.count($monitorings).' data monitoring.');
    }

    /**
     * Get status progres berdasarkan persentase
     */
    private function getStatusProgres($progres): string
    {
        if ($progres >= 100) {
            return 'Selesai';
        }
        if ($progres >= 90) {
            return 'Pembersihan';
        }
        if ($progres >= 75) {
            return 'Pekerjaan Finishing';
        }
        if ($progres >= 60) {
            return 'Pekerjaan Atap';
        }
        if ($progres >= 40) {
            return 'Pekerjaan Dinding';
        }
        if ($progres >= 25) {
            return 'Pekerjaan Pondasi';
        }
        if ($progres >= 10) {
            return 'Persiapan Lahan';
        }

        return 'Perencanaan';
    }

    /**
     * Get random keterangan berdasarkan status progres
     */
    private function getRandomKeterangan($statusProgres): string
    {
        $keterangan = [
            'Perencanaan' => [
                'Melakukan perencanaan dan konsultasi dengan tim teknis.',
                'Menyusun rancangan anggaran dan jadwal pelaksanaan.',
                'Koordinasi dengan dinas terkait untuk perizinan.',
                'Melakukan survei awal lokasi pembangunan.',
            ],
            'Persiapan Lahan' => [
                'Membersihkan lahan dari semak dan pohon yang mengganggu.',
                'Melakukan pengukuran dan pemetaan lahan.',
                'Menyiapkan akses jalan untuk alat berat.',
                'Pembersihan lahan dari material bekas.',
            ],
            'Pekerjaan Pondasi' => [
                'Menggali tanah untuk pondasi dengan kedalaman yang ditentukan.',
                'Memasang batu kali dan cor beton untuk pondasi.',
                'Pengecoran pondasi dengan campuran semen yang sesuai standar.',
                'Memasang besi tulangan pada pondasi.',
            ],
            'Pekerjaan Dinding' => [
                'Memasang bata merah dengan adukan semen yang baik.',
                'Pemasangan dinding sesuai dengan gambar rencana.',
                'Pemasangan plesteran dinding.',
                'Pemasangan keramik pada dinding kamar mandi.',
            ],
            'Pekerjaan Atap' => [
                'Memasang rangka atap dari kayu atau baja ringan.',
                'Pemasangan genteng dengan rapi dan kuat.',
                'Pemasangan talang air dan instalasi pipa.',
                'Pengecatan bagian atap yang terlihat.',
            ],
            'Pekerjaan Finishing' => [
                'Melakukan pengecatan dinding interior dan eksterior.',
                'Pemasangan lantai keramik atau parket.',
                'Pemasangan instalasi listrik dan lampu.',
                'Pemasangan pintu dan jendela.',
            ],
            'Pembersihan' => [
                'Membersihkan sisa material dan sampah bangunan.',
                'Pembersihan area sekitar proyek.',
                'Pembersihan debu dan kotoran pada bangunan.',
                'Pembersihan dan penataan area parkir.',
            ],
            'Selesai' => [
                'Proyek telah selesai dan siap untuk digunakan.',
                'Serah terima proyek kepada pemerintah desa.',
                'Proyek sudah 100% selesai dan berfungsi dengan baik.',
                'Proyek selesai sesuai dengan jadwal dan anggaran.',
            ],
        ];

        $list = $keterangan[$statusProgres] ?? $keterangan['Perencanaan'];

        return $list[array_rand($list)];
    }

    /**
     * Get random admin comment
     */
    private function getRandomAdminComment(): string
    {
        $comments = [
            'Progres berjalan sesuai dengan jadwal yang ditentukan. Pertahankan!',
            'Hasil pekerjaan sudah baik, namun perlu perhatian pada bagian detail finishing.',
            'Kualitas pekerjaan sudah sesuai dengan spesifikasi teknis.',
            'Lanjutkan dengan baik, pastikan setiap tahap dilalui dengan teliti.',
            'Pekerjaan sudah sesuai target, tetap jaga kualitas.',
            'Ada beberapa catatan kecil untuk bagian pondasi, silakan diperbaiki.',
            'Progres sangat baik, lebih cepat dari jadwal. Teruskan!',
            'Mohon perhatikan kembali pada bagian drainase agar tidak terjadi genangan air.',
            'Hasil kerja tim sangat memuaskan, tingkatkan terus.',
            'Sudah sesuai dengan RAB yang disetujui. Lanjutkan.',
        ];

        return $comments[array_rand($comments)];
    }

    /**
     * Get random rejection note
     */
    private function getRandomRejectionNote(): string
    {
        $notes = [
            'Foto dokumentasi kurang jelas. Mohon upload ulang dengan resolusi yang lebih baik.',
            'Progres yang dilaporkan tidak sesuai dengan kondisi di lapangan. Mohon update yang akurat.',
            'Dana terpakai tidak sesuai dengan progres yang dilaporkan. Silakan perbaiki.',
            'Keterangan yang diberikan tidak lengkap. Mohon tambahkan detail pekerjaan.',
            'Foto before dan after tidak menunjukkan perubahan yang signifikan.',
            'Status progres tidak sesuai dengan progres persentase yang dilaporkan.',
            'Mohon lengkapi data dengan foto dokumentasi yang lebih detail.',
            'Progres yang dilaporkan terlalu tinggi dari target mingguan.',
            'Dana yang dilaporkan tidak sesuai dengan bukti pengeluaran.',
            'Mohon update ulang dengan data yang lebih akurat dan lengkap.',
        ];

        return $notes[array_rand($notes)];
    }

    /**
     * Get random foto path
     */
    private function getRandomFoto($type): ?string
    {
        // Jika random 30% tidak ada foto
        if (rand(1, 100) <= 30) {
            return null;
        }

        // Simulasi path foto (tidak ada file nyata)
        $fotos = [
            'before' => [
                'monitoring_before/photo_before_1.jpg',
                'monitoring_before/photo_before_2.jpg',
                'monitoring_before/photo_before_3.jpg',
                'monitoring_before/photo_before_4.jpg',
            ],
            'after' => [
                'monitoring_after/photo_after_1.jpg',
                'monitoring_after/photo_after_2.jpg',
                'monitoring_after/photo_after_3.jpg',
                'monitoring_after/photo_after_4.jpg',
            ],
        ];

        $list = $fotos[$type] ?? $fotos['before'];

        return $list[array_rand($list)];
    }
}
