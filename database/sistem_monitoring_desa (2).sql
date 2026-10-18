-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 04:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_monitoring_desa`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dokumentasi`
--

CREATE TABLE `dokumentasi` (
  `id_dokumentasi` bigint(20) UNSIGNED NOT NULL,
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `group_id` varchar(50) DEFAULT NULL,
  `foto` varchar(255) NOT NULL,
  `keterangan` longtext DEFAULT NULL,
  `tanggal_upload` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokumentasi`
--

INSERT INTO `dokumentasi` (`id_dokumentasi`, `id_pembangunan`, `group_id`, `foto`, `keterangan`, `tanggal_upload`, `created_at`, `updated_at`) VALUES
(4, 5, '410466a7-9927-4f7c-81de-eb8b39198eb8', 'dokumentasi/VWuOmyh2oLQ5MIsRGGHqFujiPztRsGIeA019Ue2w.jpg', '<p><br><br><h1><span style=\"color: rgb(15, 17, 21); font-family: Menlo, Monaco, Consolas, &quot;Cascadia Mono&quot;, &quot;Ubuntu Mono&quot;, &quot;DejaVu Sans Mono&quot;, &quot;Liberation Mono&quot;, &quot;JetBrains Mono&quot;, &quot;Fira Code&quot;, Cousine, &quot;Roboto Mono&quot;, &quot;Courier New&quot;, Courier, sans-serif, system-ui; font-size: 14px; background-color: rgb(235, 238, 242);\">Dokumentasi Pembangunan Tembok Penahan Tanah di Desa Kutabuluh</span></h1></p>', '2025-01-15', '2026-07-22 14:26:47', '2026-07-22 14:26:47');

-- --------------------------------------------------------

--
-- Table structure for table `evaluasi`
--

CREATE TABLE `evaluasi` (
  `id_evaluasi` bigint(20) UNSIGNED NOT NULL,
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `tanggal_evaluasi` date NOT NULL,
  `hasil_evaluasi` text NOT NULL,
  `kendala` text DEFAULT NULL,
  `rekomendasi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluasi`
--

INSERT INTO `evaluasi` (`id_evaluasi`, `id_pembangunan`, `tanggal_evaluasi`, `hasil_evaluasi`, `kendala`, `rekomendasi`, `created_at`, `updated_at`) VALUES
(2, 8, '2026-08-27', '<p>cb1</p>', '<p>cb1</p>', '<p>cb1</p>', '2026-08-27 10:48:04', '2026-08-27 10:48:04'),
(3, 18, '2026-09-22', '<p>tes</p>', '<p>tes</p>', '<p>tes</p>', '2026-09-22 11:30:24', '2026-09-22 11:30:24'),
(4, 18, '2026-09-22', '<p>berhasil</p>', '<p>berhasil</p>', '<p>berhasil</p>', '2026-09-22 11:31:35', '2026-09-22 11:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `evaluasi_rekanan`
--

CREATE TABLE `evaluasi_rekanan` (
  `id_evaluasi_rekanan` bigint(20) UNSIGNED NOT NULL,
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `id_rekanan` bigint(20) UNSIGNED NOT NULL,
  `tanggal_evaluasi` date NOT NULL,
  `selesai_tepat_waktu` tinyint(1) NOT NULL DEFAULT 0,
  `keterlambatan_hari` int(11) NOT NULL DEFAULT 0,
  `kendala` text DEFAULT NULL,
  `hasil_akhir` text DEFAULT NULL,
  `kualitas_pekerjaan` enum('sangat_baik','baik','cukup','kurang') NOT NULL DEFAULT 'baik',
  `persentase_penyelesaian` int(11) NOT NULL DEFAULT 100,
  `rating` int(11) NOT NULL DEFAULT 5,
  `rekomendasi_rekanan` text DEFAULT NULL,
  `status_verifikasi` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `catatan_verifikasi` text DEFAULT NULL,
  `diverifikasi_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `tanggal_verifikasi` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `komentar_masyarakat`
--

CREATE TABLE `komentar_masyarakat` (
  `id_komentar` bigint(20) UNSIGNED NOT NULL,
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `komentar` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `tanggal_komentar` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `komentar_masyarakat`
--

INSERT INTO `komentar_masyarakat` (`id_komentar`, `id_pembangunan`, `nama`, `email`, `komentar`, `foto`, `status`, `tanggal_komentar`, `created_at`, `updated_at`) VALUES
(2, 9, 'Yoel PA`', 'yoelperanginangin4@gmail.com', 'tes123', NULL, 'approved', '2026-08-27 14:48:07', '2026-08-27 14:48:07', '2026-09-03 17:40:08'),
(3, 18, 'Yoel', 'yoelperanginangin4@gmail.com', 'TES dulu', 'komentar_6aa93abac2788_1789475514.png', 'approved', '2026-09-15 12:31:54', '2026-09-15 12:31:54', '2026-09-15 12:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` bigint(20) UNSIGNED NOT NULL,
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `jenis_laporan` varchar(50) NOT NULL,
  `periode` varchar(50) NOT NULL,
  `tanggal_cetak` date NOT NULL,
  `dibuat_oleh` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `id_pembangunan`, `jenis_laporan`, `periode`, `tanggal_cetak`, `dibuat_oleh`, `created_at`, `updated_at`) VALUES
(7, 2, 'pembangunan', '2026', '2026-07-06', 10, '2026-07-05 18:12:08', '2026-07-05 18:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_06_04_031636_create_users_table', 1),
(2, '2026_06_04_031637_create_pembangunan_table', 1),
(3, '2026_06_04_031638_create_monitoring_table', 1),
(4, '2026_06_04_031639_create_dokumentasi_table', 1),
(5, '2026_06_04_031639_create_evaluasi_table', 1),
(6, '2026_06_04_031642_create_laporan_table', 1),
(7, '2026_06_04_044301_create_cache_table', 1),
(8, '2026_06_04_044307_create_sessions_table', 1),
(9, '2026_06_04_044317_create_jobs_table', 1),
(10, '2026_06_11_103053_add_mandor_to_pembangunan_table', 2),
(11, '2026_07_30_043305_create_komentar_masyarakat_table', 3),
(12, '2026_08_10_133052_add_verifikasi_to_monitoring_table', 4),
(13, '2026_08_17_231730_create_pengeluaran_proyek_table', 5),
(14, '2026_08_24_160000_add_email_to_users_table', 6),
(15, '2026_08_26_134525_add_dimension_fields_to_proyek_table', 7),
(16, '2026_09_15_183537_add_foto_to_komentar_masyarakat_table', 8),
(17, '2026_09_16_000000_create_evaluasi_rekanan_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `monitoring`
--

CREATE TABLE `monitoring` (
  `id_monitoring` bigint(20) UNSIGNED NOT NULL,
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `tanggal_monitoring` date NOT NULL,
  `progres` int(11) NOT NULL DEFAULT 0,
  `status_progres` varchar(50) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `komentar_admin` text DEFAULT NULL,
  `status_verifikasi` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending' COMMENT 'Status verifikasi progres: pending, approved, rejected',
  `catatan_verifikasi` text DEFAULT NULL COMMENT 'Catatan dari admin saat verifikasi (terutama jika ditolak)',
  `foto_before` varchar(255) DEFAULT NULL,
  `foto_after` varchar(255) DEFAULT NULL,
  `dana_terpakai` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monitoring`
--

INSERT INTO `monitoring` (`id_monitoring`, `id_pembangunan`, `tanggal_monitoring`, `progres`, `status_progres`, `keterangan`, `komentar_admin`, `status_verifikasi`, `catatan_verifikasi`, `foto_before`, `foto_after`, `dana_terpakai`, `created_at`, `updated_at`) VALUES
(4, 2, '2026-07-29', 10, 'Persiapan Lahan', NULL, 'halooo kek man bray', 'approved', NULL, 'monitoring_before/nTZV2I8eI3khC9e4gDlKl8LH05p4D4mG6SbelDrX.jpg', 'monitoring_after/sOHfrMpLuJdz9XfYUgX7j1QDJvv9YGdjbhqisLiF.png', NULL, '2026-07-29 13:12:25', '2026-08-10 07:14:49'),
(5, 5, '2026-07-15', 5, 'Survey Lokasi', 'Melakukan survey lokasi dan pengukuran lahan untuk pembangunan tembok penahan tanah', NULL, 'approved', 'hm', NULL, NULL, NULL, '2026-07-29 15:12:36', '2026-08-10 06:54:14'),
(6, 5, '2026-07-20', 15, 'Pembersihan Lahan', 'Proses pembersihan lahan dari semak dan pohon yang mengganggu', NULL, 'approved', NULL, NULL, NULL, NULL, '2026-07-29 15:12:36', '2026-08-10 07:14:28'),
(7, 5, '2026-07-25', 25, 'Pengerukan Tanah', 'Pengerukan tanah untuk fondasi tembok penahan tanah', NULL, 'approved', NULL, NULL, NULL, NULL, '2026-07-29 15:12:36', '2026-08-10 07:14:40'),
(8, 5, '2026-07-29', 35, 'Pemasangan Fondasi', 'Pemasangan fondasi tembok penahan tanah dengan material batu dan semen', 'Progres berjalan sesuai jadwal. Lanjutkan!', 'pending', NULL, NULL, NULL, NULL, '2026-07-29 15:12:36', '2026-07-29 15:12:36'),
(9, 2, '2026-03-26', 20, 'Persiapan Lahan', 'Menyiapkan akses jalan untuk alat berat.', NULL, 'pending', NULL, 'monitoring_before/photo_before_2.jpg', 'monitoring_after/photo_after_4.jpg', 3339576.00, '2026-03-26 12:49:36', '2026-03-26 12:49:36'),
(10, 2, '2026-03-27', 31, 'Pekerjaan Pondasi', 'Memasang batu kali dan cor beton untuk pondasi.', 'Hasil kerja tim sangat memuaskan, tingkatkan terus.', 'approved', NULL, 'monitoring_before/photo_before_1.jpg', 'monitoring_after/photo_after_4.jpg', 17038834.00, '2026-03-26 22:38:35', '2026-03-26 22:38:35'),
(11, 2, '2026-02-06', 41, 'Pekerjaan Dinding', 'Pemasangan dinding sesuai dengan gambar rencana.', NULL, 'pending', NULL, 'monitoring_before/photo_before_3.jpg', 'monitoring_after/photo_after_3.jpg', 37206559.00, '2026-02-06 07:50:21', '2026-02-06 07:50:21'),
(12, 2, '2026-01-29', 68, 'Pekerjaan Atap', 'Pemasangan genteng dengan rapi dan kuat.', NULL, 'pending', NULL, 'monitoring_before/photo_before_1.jpg', 'monitoring_after/photo_after_1.jpg', 73234494.00, '2026-01-29 02:18:40', '2026-01-29 02:18:40'),
(13, 2, '2026-07-10', 70, 'Pekerjaan Atap', 'Memasang rangka atap dari kayu atau baja ringan.', NULL, 'pending', NULL, 'monitoring_before/photo_before_1.jpg', 'monitoring_after/photo_after_2.jpg', 63185260.00, '2026-07-09 20:48:38', '2026-07-09 20:48:38'),
(14, 3, '2026-06-01', 19, 'Persiapan Lahan', 'Membersihkan lahan dari semak dan pohon yang mengganggu.', 'Hasil kerja tim sangat memuaskan, tingkatkan terus.', 'approved', NULL, NULL, 'monitoring_after/photo_after_4.jpg', 66970087.00, '2026-06-01 13:51:21', '2026-06-01 13:51:21'),
(15, 3, '2026-05-15', 32, 'Pekerjaan Pondasi', 'Memasang besi tulangan pada pondasi.', 'Hasil pekerjaan sudah baik, namun perlu perhatian pada bagian detail finishing.', 'approved', NULL, 'monitoring_before/photo_before_3.jpg', NULL, 75551585.00, '2026-05-15 02:41:50', '2026-05-15 02:41:50'),
(16, 3, '2026-05-07', 33, 'Pekerjaan Pondasi', 'Memasang batu kali dan cor beton untuk pondasi.', NULL, 'approved', NULL, NULL, NULL, 93198195.00, '2026-05-07 15:47:09', '2026-05-07 15:47:09'),
(17, 3, '2026-05-24', 78, 'Pekerjaan Finishing', 'Pemasangan pintu dan jendela.', NULL, 'approved', NULL, NULL, NULL, 82022847.00, '2026-05-24 00:18:50', '2026-05-24 00:18:50'),
(18, 3, '2026-05-19', 80, 'Pekerjaan Finishing', 'Pemasangan pintu dan jendela.', NULL, 'pending', NULL, NULL, 'monitoring_after/photo_after_3.jpg', 111145320.00, '2026-05-19 07:49:47', '2026-05-19 07:49:47'),
(19, 3, '2026-04-10', 100, 'Selesai', 'Proyek selesai sesuai dengan jadwal dan anggaran.', NULL, 'pending', NULL, NULL, 'monitoring_after/photo_after_1.jpg', 89800137.00, '2026-04-10 05:49:32', '2026-04-10 05:49:32'),
(20, 4, '2026-06-02', 7, 'Perencanaan', 'Koordinasi dengan dinas terkait untuk perizinan.', NULL, 'approved', NULL, 'monitoring_before/photo_before_4.jpg', 'monitoring_after/photo_after_4.jpg', 12968355.00, '2026-06-01 20:54:40', '2026-06-01 20:54:40'),
(21, 4, '2026-05-22', 26, 'Pekerjaan Pondasi', 'Memasang batu kali dan cor beton untuk pondasi.', 'Ada beberapa catatan kecil untuk bagian pondasi, silakan diperbaiki.', 'approved', NULL, NULL, 'monitoring_after/photo_after_3.jpg', 70486296.00, '2026-05-22 01:14:46', '2026-05-22 01:14:46'),
(22, 4, '2026-03-03', 48, 'Pekerjaan Dinding', 'Memasang bata merah dengan adukan semen yang baik.', NULL, 'rejected', 'Mohon update ulang dengan data yang lebih akurat dan lengkap.', 'monitoring_before/photo_before_4.jpg', 'monitoring_after/photo_after_3.jpg', 37652977.00, '2026-03-02 18:54:25', '2026-03-02 18:54:25'),
(23, 4, '2026-03-01', 44, 'Pekerjaan Dinding', 'Pemasangan dinding sesuai dengan gambar rencana.', 'Kualitas pekerjaan sudah sesuai dengan spesifikasi teknis.', 'approved', NULL, 'monitoring_before/photo_before_1.jpg', NULL, 40464753.00, '2026-03-01 02:18:54', '2026-03-01 02:18:54'),
(24, 4, '2026-02-18', 62, 'Pekerjaan Atap', 'Pemasangan genteng dengan rapi dan kuat.', NULL, 'pending', NULL, 'monitoring_before/photo_before_3.jpg', 'monitoring_after/photo_after_4.jpg', 36309652.00, '2026-02-18 02:42:39', '2026-02-18 02:42:39'),
(25, 4, '2026-05-16', 100, 'Selesai', 'Proyek telah selesai dan siap untuk digunakan.', NULL, 'pending', NULL, NULL, NULL, 67263610.00, '2026-05-16 12:40:31', '2026-05-16 12:40:31'),
(26, 5, '2025-08-04', 19, 'Persiapan Lahan', 'Menyiapkan akses jalan untuk alat berat.', NULL, 'approved', NULL, 'monitoring_before/photo_before_1.jpg', 'monitoring_after/photo_after_2.jpg', 7476763.00, '2025-08-03 21:12:31', '2025-08-03 21:12:31'),
(27, 5, '2025-07-12', 29, 'Pekerjaan Pondasi', 'Memasang besi tulangan pada pondasi.', 'Pekerjaan sudah sesuai target, tetap jaga kualitas.', 'approved', NULL, 'monitoring_before/photo_before_1.jpg', 'monitoring_after/photo_after_3.jpg', 4680647.00, '2025-07-11 21:42:21', '2025-07-11 21:42:21'),
(28, 5, '2025-12-27', 45, 'Pekerjaan Dinding', 'Memasang bata merah dengan adukan semen yang baik.', NULL, 'approved', NULL, NULL, 'monitoring_after/photo_after_3.jpg', 4643175.00, '2025-12-27 00:02:06', '2025-12-27 00:02:06'),
(29, 5, '2025-03-12', 66, 'Pekerjaan Atap', 'Pemasangan talang air dan instalasi pipa.', NULL, 'pending', NULL, 'monitoring_before/photo_before_1.jpg', 'monitoring_after/photo_after_4.jpg', 12150945.00, '2025-03-12 06:45:43', '2025-03-12 06:45:43'),
(30, 5, '2025-10-14', 92, 'Pembersihan', 'Membersihkan sisa material dan sampah bangunan.', NULL, 'approved', NULL, 'monitoring_before/photo_before_4.jpg', 'monitoring_after/photo_after_4.jpg', 13454350.00, '2025-10-14 10:56:09', '2025-10-14 10:56:09'),
(31, 5, '2025-06-25', 100, 'Selesai', 'Serah terima proyek kepada pemerintah desa.', NULL, 'approved', NULL, 'monitoring_before/photo_before_1.jpg', 'monitoring_after/photo_after_4.jpg', 3955327.00, '2025-06-25 10:57:19', '2025-06-25 10:57:19'),
(32, 2, '2026-08-24', 20, 'Persiapan Lahan', NULL, NULL, 'approved', NULL, 'uploads/monitoring/before/1787559298_6a8bfd827ba55.png', 'uploads/monitoring/after/1787559298_6a8bfd827c24b.png', NULL, '2026-08-24 08:14:58', '2026-08-24 08:16:50'),
(33, 8, '2026-08-27', 10, 'Persiapan Lahan', '<p>tes</p>', NULL, 'approved', NULL, 'uploads/monitoring/before/1787826547_6a901173b5564.png', 'uploads/monitoring/after/1787826547_6a901173b8b5b.png', 100000.00, '2026-08-27 10:29:07', '2026-08-27 10:42:07'),
(35, 18, '2026-09-05', 100, 'Selesai', NULL, NULL, 'approved', 'okegas', 'uploads/monitoring/before/1788621521_6a9c32d1a1217.jpeg', 'uploads/monitoring/after/1788621521_6a9c32d1a1646.jpeg', 46661000.00, '2026-09-05 15:18:41', '2026-09-05 15:29:56'),
(36, 19, '2026-09-07', 10, 'Persiapan Lahan', NULL, NULL, 'approved', 'sesuai', 'uploads/monitoring/before/1788756283_6a9e413b2c760.jpeg', 'uploads/monitoring/after/1788756283_6a9e413b2ccea.jpeg', 500000.00, '2026-09-07 04:44:43', '2026-09-07 04:45:59');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `proyek_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `proyek_id`, `title`, `message`, `url`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 12, 9, 'Proyek Baru Tersedia', 'Proyek \"tes1\" telah dipublikasikan dan tersedia untuk ditawar.', 'http://localhost/sistem-monitoring-desa/public/rekanan/available', 0, '2026-08-27 11:07:06', '2026-08-27 11:07:06'),
(2, 13, 9, 'Proyek Baru Tersedia', 'Proyek \"tes1\" telah dipublikasikan dan tersedia untuk ditawar.', 'http://localhost/sistem-monitoring-desa/public/rekanan/available', 0, '2026-08-27 11:07:06', '2026-08-27 11:07:06'),
(3, 14, 9, 'Proyek Baru Tersedia', 'Proyek \"tes1\" telah dipublikasikan dan tersedia untuk ditawar.', 'http://localhost/sistem-monitoring-desa/public/rekanan/available', 0, '2026-08-27 11:07:06', '2026-08-27 11:07:06'),
(4, 15, 9, 'Proyek Baru Tersedia', 'Proyek \"tes1\" telah dipublikasikan dan tersedia untuk ditawar.', 'http://localhost/sistem-monitoring-desa/public/rekanan/available', 0, '2026-08-27 11:07:06', '2026-08-27 11:07:06'),
(5, 16, 9, 'Proyek Baru Tersedia', 'Proyek \"tes1\" telah dipublikasikan dan tersedia untuk ditawar.', 'http://localhost/sistem-monitoring-desa/public/rekanan/available', 0, '2026-08-27 11:07:07', '2026-08-27 11:07:07');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran_proyek`
--

CREATE TABLE `pengeluaran_proyek` (
  `id_pengeluaran` bigint(20) UNSIGNED NOT NULL,
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `kategori` enum('upah_tenaga_kerja','material','sewa_alat','transportasi','administrasi','lainnya') NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `volume` decimal(15,2) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jumlah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tanggal_pengeluaran` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengeluaran_proyek`
--

INSERT INTO `pengeluaran_proyek` (`id_pengeluaran`, `id_pembangunan`, `kategori`, `uraian`, `volume`, `satuan`, `harga_satuan`, `jumlah`, `tanggal_pengeluaran`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 5, 'upah_tenaga_kerja', 'tes', 2.00, NULL, 500000.00, 1000000.00, '2026-08-17', NULL, '2026-08-17 16:41:20', '2026-08-17 16:41:20'),
(2, 18, 'upah_tenaga_kerja', 'upah tukang', 20.00, 'orang', 163000.00, 3260000.00, '2025-07-13', NULL, '2026-09-05 15:58:37', '2026-09-05 15:58:37'),
(3, 18, 'upah_tenaga_kerja', 'pekerja', 82.00, 'orang', 114000.00, 9348000.00, '2025-07-13', NULL, '2026-09-05 15:59:39', '2026-09-06 18:13:30'),
(4, 18, 'upah_tenaga_kerja', 'pekerja+supir bongkar muat semen', 10.00, 'orang', 114000.00, 1140000.00, '2025-07-13', NULL, '2026-09-05 16:00:42', '2026-09-06 18:13:55'),
(5, 18, 'upah_tenaga_kerja', 'pekerja+supir bongkar muat sertu', 50.00, 'meter', 114000.00, 5700000.00, '2025-07-13', NULL, '2026-09-05 16:02:32', '2026-09-06 18:14:21'),
(6, 18, 'material', 'semen', 162.00, 'sak', 80000.00, 12960000.00, '2025-07-13', NULL, '2026-09-05 16:03:47', '2026-09-06 18:14:41'),
(7, 18, 'material', 'sertu', 42.00, 'meter', 163000.00, 6846000.00, '2025-07-13', NULL, '2026-09-05 16:05:23', '2026-09-06 18:15:04'),
(8, 18, 'material', 'papan', 20.00, 'lembar', 80000.00, 1600000.00, '2025-07-13', NULL, '2026-09-05 16:06:01', '2026-09-06 18:18:14'),
(9, 18, 'material', 'kayu broti 3x5', 4.00, 'batang', 90000.00, 360000.00, '2025-07-13', NULL, '2026-09-05 16:06:54', '2026-09-06 18:18:36'),
(10, 18, 'material', 'paku campur', 2.00, 'kg', 24000.00, 48000.00, '2025-07-13', NULL, '2026-09-05 16:07:38', '2026-09-06 18:19:00'),
(11, 18, 'material', 'cangkul+gagang', 6.00, 'buah', 75000.00, 450000.00, '2025-07-13', NULL, '2026-09-05 16:08:37', '2026-09-06 18:19:30'),
(12, 18, 'material', 'sekop+gagang', 6.00, 'buah', 66000.00, 396000.00, '2025-07-13', NULL, '2026-09-05 16:10:24', '2026-09-06 18:20:36'),
(13, 18, 'material', 'ember kecil', 12.00, 'buah', 10000.00, 120000.00, '2025-07-13', NULL, '2026-09-05 16:11:00', '2026-09-06 18:21:32'),
(14, 18, 'administrasi', 'plank kegiatan', 1.00, 'buah', 150000.00, 150000.00, '2025-07-13', NULL, '2026-09-05 16:12:06', '2026-09-06 18:27:50'),
(15, 18, 'sewa_alat', 'belanja sewa hartop lansir semen', 160.00, 'sak', 3000.00, 480000.00, '2025-07-13', NULL, '2026-09-05 16:13:50', '2026-09-06 18:27:05'),
(16, 18, 'sewa_alat', 'belanja sewa hartop lansir sertu', 35.00, 'meter', 80000.00, 2800000.00, '2025-07-13', NULL, '2026-09-05 16:14:29', '2026-09-06 18:22:33'),
(17, 18, 'administrasi', 'ketua', 1.00, 'orang', 430000.00, 430000.00, '2025-07-13', NULL, '2026-09-05 16:15:40', '2026-09-06 18:26:00'),
(18, 18, 'administrasi', 'sekretaris', 1.00, 'orang', 330000.00, 330000.00, '2025-07-13', NULL, '2026-09-05 16:16:39', '2026-09-06 18:26:20'),
(19, 18, 'administrasi', 'anggota', 1.00, 'orang', 243000.00, 243000.00, '2025-07-13', NULL, '2026-09-05 16:17:17', '2026-09-06 18:20:55');

-- --------------------------------------------------------

--
-- Table structure for table `proyek`
--

CREATE TABLE `proyek` (
  `id_pembangunan` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama_pembangunan` varchar(150) NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `tahun_anggaran` year(4) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `sumber_dana` varchar(100) NOT NULL,
  `anggaran` decimal(15,2) NOT NULL,
  `panjang` decimal(15,2) DEFAULT NULL,
  `lebar` decimal(15,2) DEFAULT NULL,
  `ketebalan_tinggi` decimal(15,2) DEFAULT NULL,
  `volume_proyek` decimal(15,2) DEFAULT NULL,
  `satuan_volume` varchar(50) DEFAULT NULL,
  `harga_penawaran` decimal(15,2) DEFAULT NULL,
  `batas_penawaran` date DEFAULT NULL,
  `status` enum('direncanakan','berjalan','selesai') NOT NULL DEFAULT 'direncanakan',
  `is_published` tinyint(1) DEFAULT 0,
  `status_penawaran` enum('open','review','closed') DEFAULT 'open',
  `deadline_penawaran` date DEFAULT NULL,
  `rekanan_terpilih` int(11) DEFAULT NULL,
  `bidang_pekerjaan` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proyek`
--

INSERT INTO `proyek` (`id_pembangunan`, `id_user`, `nama_pembangunan`, `lokasi`, `tahun_anggaran`, `tanggal_mulai`, `tanggal_selesai`, `sumber_dana`, `anggaran`, `panjang`, `lebar`, `ketebalan_tinggi`, `volume_proyek`, `satuan_volume`, `harga_penawaran`, `batas_penawaran`, `status`, `is_published`, `status_penawaran`, `deadline_penawaran`, `rekanan_terpilih`, `bidang_pekerjaan`, `catatan`, `created_at`, `updated_at`) VALUES
(2, 9, 'Pembangunan Jalan Dusun III', 'Dusun III, Desa Kutabuluh', '2026', '2026-01-15', '2026-06-30', 'APBDes', 150000000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'berjalan', 1, 'open', NULL, 12, 'Konstruksi Jalan', NULL, '2026-07-05 13:53:18', '2026-07-05 13:56:28'),
(3, 9, 'Pembangunan Jembatan Penghubung', 'Dusun I - Dusun II, Desa Kutabuluh', '2026', '2026-03-01', '2026-08-15', 'Bantuan Provinsi', 250000000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'berjalan', 1, 'closed', NULL, 12, 'Konstruksi Jembatan', NULL, '2026-07-19 06:00:47', '2026-07-19 07:23:55'),
(4, 9, 'Pembangunan Jalan Dusun III', 'Dusun III, Desa Kutabuluh', '2026', '2026-01-15', '2026-07-30', 'APBDes', 150000000.00, NULL, NULL, NULL, NULL, NULL, 135000000.00, NULL, 'direncanakan', 1, 'closed', NULL, 12, 'Konstruksi Jalan', NULL, '2026-07-22 13:08:10', '2026-07-22 13:11:39'),
(5, 9, 'Pembangunan Tembok Penahan Tanah', 'Desa Kutabuluh, Kecamatan Kutabuluh, Kabupaten Karo', '2025', '2025-01-15', '2025-06-30', 'Dana Desa 2025', 27977000.00, NULL, NULL, NULL, 1.00, '1', 25000000.00, NULL, 'berjalan', 0, 'open', NULL, NULL, 'Konstruksi Bangunan', NULL, '2026-07-22 13:24:40', '2026-08-24 06:24:06'),
(8, 9, 'tes', 'tes', '2026', '2026-08-28', '2026-10-29', 'tes', 67000000.00, 56.00, 44.00, 6.00, 14784.00, 'm³', 65000000.00, '2026-09-23', 'berjalan', 1, 'closed', NULL, 16, 'tes', 'tes', '2026-08-24 15:23:32', '2026-08-27 10:52:27'),
(9, 9, 'tes1', 'tes1', '2026', '2026-08-31', '2026-10-02', 'tes1', 45000000.00, 30.00, 50.00, 0.09, 135.00, 'm³', 43000000.00, '2026-08-27', 'direncanakan', 1, 'closed', NULL, 16, 'tes1', 'tes1', '2026-08-25 11:57:37', '2026-09-04 07:57:27'),
(12, 9, 'jalan jambur', 'DESA KUTABULUH', '2026', '2026-09-09', '2026-09-24', 'APBDes', 50000000.00, 50.00, 4.00, 0.25, 50.00, 'm³', 49000000.00, '2026-09-07', 'direncanakan', 1, 'closed', NULL, 12, 'jalan', NULL, '2026-09-03 17:49:47', '2026-09-04 07:43:19'),
(13, 9, 'tes', 'DESA KUTABULUH', '2026', '2026-09-11', '2026-09-21', 'APBDes', 6700000.00, 100.00, 3.00, 0.04, 12.00, 'm³', 66000000.00, '2026-09-07', 'direncanakan', 1, 'closed', NULL, 12, 'jalan', NULL, '2026-09-04 08:00:39', '2026-09-04 08:03:16'),
(14, 9, 'jalan juma', 'DESA KUTABULUH', '2026', '2026-09-14', '2026-09-29', 'APBDes', 66000000.00, 75.00, 3.00, 0.30, 67.50, 'm³', 65000000.00, '2026-09-13', 'direncanakan', 1, 'closed', NULL, 12, 'jalan', 'okeya', '2026-09-04 08:33:22', '2026-09-04 08:38:13'),
(15, 9, 'jalan jambur', 'Dusun 1,desa kutabuluh', '2026', '2026-09-09', '2026-09-30', 'APBDes', 50000000.00, 75.00, 3.00, 0.30, 67.50, 'm³', 45000000.00, '2026-09-07', 'direncanakan', 1, 'closed', NULL, 12, 'jalan', NULL, '2026-09-04 08:40:42', '2026-09-04 09:06:19'),
(16, 9, 'lau kaca', 'DESA KUTABULUH', '2026', '2026-09-08', '2026-09-30', 'APBDes', 45000000.00, 50.00, 2.00, 0.03, 3.00, 'm³', 44000000.00, '2026-09-06', 'direncanakan', 1, 'open', NULL, NULL, 'jalan', NULL, '2026-09-05 04:01:38', '2026-09-05 04:01:38'),
(17, 9, 'rehab jalan lau bilik', 'DESA KUTABULUH', '2026', '2026-09-08', '2026-09-30', 'APBDes', 50000000.00, 70.00, 2.00, 0.03, 4.20, 'm³', 49000000.00, '2026-09-06', 'direncanakan', 1, 'open', NULL, NULL, 'jalan', NULL, '2026-09-05 04:03:36', '2026-09-05 04:03:36'),
(18, 9, 'RABAT BETON JUMA SUMBAH', 'DESA KUTABULUH', '2026', '2025-06-06', '2025-06-18', 'APBDes', 46661000.00, 50.00, 3.00, 0.20, 30.00, 'm³', 46661000.00, '2026-09-06', 'selesai', 1, 'closed', NULL, 16, 'jalan', NULL, '2026-09-05 15:10:57', '2026-09-05 16:20:06'),
(19, 9, 'jalan jambur', 'DESA KUTABULUH', '2026', '2026-09-09', '2026-09-30', 'APBDes', 45000000.00, 50.00, 3.00, 0.20, 30.00, 'm³', 44000000.00, '2026-09-08', 'berjalan', 1, 'closed', NULL, 12, 'jalan', NULL, '2026-09-07 04:38:38', '2026-09-07 04:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `rekanan_proyek`
--

CREATE TABLE `rekanan_proyek` (
  `id_rekanan_proyek` bigint(20) UNSIGNED NOT NULL,
  `id_proyek` bigint(20) UNSIGNED NOT NULL,
  `id_rekanan` bigint(20) UNSIGNED NOT NULL,
  `status` enum('meninjau','setuju','tolak') DEFAULT 'meninjau',
  `catatan` text DEFAULT NULL,
  `harga_penawaran_rekanan` decimal(15,2) DEFAULT NULL,
  `status_penawaran` enum('pending','dipertimbangkan','diterima','ditolak') NOT NULL DEFAULT 'pending',
  `tanggal_respon` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekanan_proyek`
--

INSERT INTO `rekanan_proyek` (`id_rekanan_proyek`, `id_proyek`, `id_rekanan`, `status`, `catatan`, `harga_penawaran_rekanan`, `status_penawaran`, `tanggal_respon`, `created_at`, `updated_at`) VALUES
(1, 2, 12, 'setuju', 'gas pak cikkk', NULL, 'pending', '2026-07-05 13:56:28', '2026-07-05 13:56:28', '2026-07-05 13:56:28'),
(2, 3, 12, 'setuju', '20 JT aja iya boy hehehe', 200000000.00, 'diterima', '2026-07-19 06:03:32', '2026-07-19 06:03:32', '2026-07-19 07:23:55'),
(3, 4, 12, 'setuju', 'Saya siap mengerjakan proyek ini dengan tim yang berpengalaman', 130000000.00, 'diterima', '2026-07-22 13:09:39', '2026-07-22 13:09:39', '2026-07-22 13:11:39'),
(4, 4, 13, 'meninjau', 'Saya berpengalaman dalam proyek sejenis', 140000000.00, 'ditolak', '2026-07-22 13:10:34', '2026-07-22 13:10:34', '2026-07-22 13:11:39'),
(8, 8, 16, 'setuju', NULL, 66000000.00, 'diterima', '2026-08-24 15:29:43', '2026-08-24 15:29:04', '2026-08-24 15:29:43'),
(11, 12, 12, 'setuju', NULL, 49500000.00, 'diterima', '2026-09-04 07:43:19', '2026-09-03 17:50:36', '2026-09-04 07:43:19'),
(12, 9, 16, 'setuju', NULL, 44000000.00, 'diterima', '2026-09-04 07:57:27', '2026-09-04 07:56:13', '2026-09-04 07:57:27'),
(13, 13, 12, 'setuju', 'tesya', 66000000.00, 'diterima', '2026-09-04 08:03:16', '2026-09-04 08:01:32', '2026-09-04 08:03:16'),
(14, 14, 12, 'setuju', 'sesuai tawaran', 65000000.00, 'diterima', '2026-09-04 08:38:13', '2026-09-04 08:34:03', '2026-09-04 08:38:13'),
(15, 15, 12, 'setuju', NULL, 45000000.00, 'diterima', '2026-09-04 09:06:19', '2026-09-04 08:41:29', '2026-09-04 09:06:19'),
(16, 17, 16, 'meninjau', NULL, 49000000.00, 'dipertimbangkan', '2026-09-05 04:07:41', '2026-09-05 04:04:56', '2026-09-05 04:07:41'),
(17, 16, 16, 'tolak', NULL, 44000000.00, 'ditolak', '2026-09-05 04:08:18', '2026-09-05 04:05:15', '2026-09-05 04:08:18'),
(18, 18, 16, 'setuju', 'sesuai dengan harga penawaran', 46661000.00, 'diterima', '2026-09-05 15:15:07', '2026-09-05 15:12:14', '2026-09-05 15:15:07'),
(19, 19, 12, 'setuju', 'sesuai tawaran', 43000000.00, 'diterima', '2026-09-07 04:41:58', '2026-09-07 04:40:28', '2026-09-07 04:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','kepala_desa','rekanan') NOT NULL DEFAULT 'rekanan',
  `bidang_keahlian` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_user`, `username`, `email`, `password`, `level`, `bidang_keahlian`, `no_telp`, `alamat`, `cv`, `created_at`, `updated_at`) VALUES
(9, 'Administrator', 'admin', NULL, '$2y$12$EF7UBGSq6I2uENz13jKCdOMH/mvNBDW0uYqnsD7CDpOCt.oKxB1Gm', 'admin', NULL, NULL, NULL, NULL, '2026-07-05 11:10:25', '2026-07-05 11:10:25'),
(10, 'Kepala Desa Kutabuluh', 'kepala_desa', NULL, '$2y$12$37JxqmOzxiO4bASBwpDhI.AKN7tz3LRZanVF3xOGe2WDqi1BPy9aO', 'kepala_desa', NULL, NULL, NULL, NULL, '2026-07-05 11:10:26', '2026-07-05 11:10:26'),
(12, 'Rekanan 1 - Konstruksi', 'rekanan1', NULL, '$2y$12$DAcL45EJUrEZcFa4Vm3nBek0Fc2E1X6XF9MRH2SQAOsC2R.WNcAF6', 'rekanan', 'Konstruksi Bangunan', '081234567890', 'Jl. Contoh No. 1, Kutabuluh', 'cv_6a9a722ed866d5.45357562_1788506670.pdf', '2026-07-05 11:12:20', '2026-09-04 07:24:30'),
(13, 'Rekanan 2 - Jalan', 'rekanan2', NULL, '$2y$12$EC53z4eQ7lZ9pUhzFJredOkANy0LosjTgjKY65cOEo8V8yIROa2Vq', 'rekanan', 'Pembangunan Jalan', '081234567891', 'Jl. Contoh No. 2, Kutabuluh', NULL, '2026-07-05 11:12:21', '2026-07-05 11:12:21'),
(14, 'Rekanan 3 - Listrik', 'rekanan3', NULL, '$2y$12$.lcZxlWwUQwmR5roPPKCoO3CZgznKXcvzWA6u1oAJCw.h.6yP4ejy', 'rekanan', 'Instalasi Listrik', '081234567892', 'Jl. Contoh No. 3, Kutabuluh', NULL, '2026-07-05 11:12:21', '2026-07-05 11:12:21'),
(15, 'Ibrahim Sembiring', 'ibrahim_sembiring', NULL, '$2y$12$sLNPXjaRk/O7xVy2Z6vbrOCz10K.1DIB42Et3p38okjZ/E0NQn6s6', 'rekanan', 'Konstruksi Jalan dan Jembatan', '081234567899', 'Jl. Pahlawan No. 45, Dusun IV, Desa Kutabuluh, Kec. Kutabuluh', NULL, '2026-07-10 22:53:30', '2026-07-10 22:53:30'),
(16, 'samsyadi', 'samsyadi', 'yoelperanginangin4@gmail.com', '$2y$12$kLVyxr9dBsVQyTfh4K/XJ.9fvxLhDMz/qCbzcEyenpbNfOmpcD0ey', 'kepala_desa', 'Bangunan', '0823-8419-6744', 'Kutabuluh', 'cv_6a9c2ebd4a2f62.47139708_1788620477.pdf', '2026-08-24 14:18:39', '2026-09-06 16:27:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD PRIMARY KEY (`id_dokumentasi`),
  ADD KEY `dokumentasi_id_proyek_foreign` (`id_pembangunan`);

--
-- Indexes for table `evaluasi`
--
ALTER TABLE `evaluasi`
  ADD PRIMARY KEY (`id_evaluasi`),
  ADD KEY `evaluasi_id_proyek_foreign` (`id_pembangunan`);

--
-- Indexes for table `evaluasi_rekanan`
--
ALTER TABLE `evaluasi_rekanan`
  ADD PRIMARY KEY (`id_evaluasi_rekanan`),
  ADD UNIQUE KEY `unique_evaluasi_rekanan` (`id_pembangunan`,`id_rekanan`),
  ADD KEY `evaluasi_rekanan_id_rekanan_foreign` (`id_rekanan`),
  ADD KEY `evaluasi_rekanan_diverifikasi_oleh_foreign` (`diverifikasi_oleh`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `komentar_masyarakat`
--
ALTER TABLE `komentar_masyarakat`
  ADD PRIMARY KEY (`id_komentar`),
  ADD KEY `komentar_masyarakat_id_pembangunan_foreign` (`id_pembangunan`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `laporan_dibuat_oleh_foreign` (`dibuat_oleh`),
  ADD KEY `laporan_id_proyek_foreign` (`id_pembangunan`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monitoring`
--
ALTER TABLE `monitoring`
  ADD PRIMARY KEY (`id_monitoring`),
  ADD KEY `monitoring_id_proyek_foreign` (`id_pembangunan`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_index` (`user_id`),
  ADD KEY `notifications_proyek_id_index` (`proyek_id`),
  ADD KEY `notifications_user_read_index` (`user_id`,`is_read`);

--
-- Indexes for table `pengeluaran_proyek`
--
ALTER TABLE `pengeluaran_proyek`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `pengeluaran_proyek_id_pembangunan_kategori_index` (`id_pembangunan`,`kategori`);

--
-- Indexes for table `proyek`
--
ALTER TABLE `proyek`
  ADD PRIMARY KEY (`id_pembangunan`),
  ADD KEY `pembangunan_id_user_foreign` (`id_user`);

--
-- Indexes for table `rekanan_proyek`
--
ALTER TABLE `rekanan_proyek`
  ADD PRIMARY KEY (`id_rekanan_proyek`),
  ADD UNIQUE KEY `unique_rekanan_proyek` (`id_proyek`,`id_rekanan`),
  ADD KEY `id_rekanan` (`id_rekanan`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  MODIFY `id_dokumentasi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `evaluasi`
--
ALTER TABLE `evaluasi`
  MODIFY `id_evaluasi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `evaluasi_rekanan`
--
ALTER TABLE `evaluasi_rekanan`
  MODIFY `id_evaluasi_rekanan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `komentar_masyarakat`
--
ALTER TABLE `komentar_masyarakat`
  MODIFY `id_komentar` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `monitoring`
--
ALTER TABLE `monitoring`
  MODIFY `id_monitoring` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengeluaran_proyek`
--
ALTER TABLE `pengeluaran_proyek`
  MODIFY `id_pengeluaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `proyek`
--
ALTER TABLE `proyek`
  MODIFY `id_pembangunan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rekanan_proyek`
--
ALTER TABLE `rekanan_proyek`
  MODIFY `id_rekanan_proyek` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumentasi`
--
ALTER TABLE `dokumentasi`
  ADD CONSTRAINT `dokumentasi_id_proyek_foreign` FOREIGN KEY (`id_pembangunan`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE;

--
-- Constraints for table `evaluasi`
--
ALTER TABLE `evaluasi`
  ADD CONSTRAINT `evaluasi_id_proyek_foreign` FOREIGN KEY (`id_pembangunan`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE;

--
-- Constraints for table `evaluasi_rekanan`
--
ALTER TABLE `evaluasi_rekanan`
  ADD CONSTRAINT `evaluasi_rekanan_diverifikasi_oleh_foreign` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `evaluasi_rekanan_id_pembangunan_foreign` FOREIGN KEY (`id_pembangunan`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluasi_rekanan_id_rekanan_foreign` FOREIGN KEY (`id_rekanan`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `komentar_masyarakat`
--
ALTER TABLE `komentar_masyarakat`
  ADD CONSTRAINT `komentar_masyarakat_id_pembangunan_foreign` FOREIGN KEY (`id_pembangunan`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE;

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_dibuat_oleh_foreign` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_id_proyek_foreign` FOREIGN KEY (`id_pembangunan`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE;

--
-- Constraints for table `monitoring`
--
ALTER TABLE `monitoring`
  ADD CONSTRAINT `monitoring_id_proyek_foreign` FOREIGN KEY (`id_pembangunan`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE;

--
-- Constraints for table `pengeluaran_proyek`
--
ALTER TABLE `pengeluaran_proyek`
  ADD CONSTRAINT `pengeluaran_proyek_id_pembangunan_foreign` FOREIGN KEY (`id_pembangunan`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE;

--
-- Constraints for table `proyek`
--
ALTER TABLE `proyek`
  ADD CONSTRAINT `pembangunan_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `rekanan_proyek`
--
ALTER TABLE `rekanan_proyek`
  ADD CONSTRAINT `rekanan_proyek_ibfk_1` FOREIGN KEY (`id_proyek`) REFERENCES `proyek` (`id_pembangunan`) ON DELETE CASCADE,
  ADD CONSTRAINT `rekanan_proyek_ibfk_2` FOREIGN KEY (`id_rekanan`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
