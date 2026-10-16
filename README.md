# Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh

Aplikasi berbasis web untuk membantu Pemerintah Desa Kutabuluh mengelola informasi pembangunan, memantau progres pelaksanaan proyek, melakukan evaluasi, serta menyediakan informasi pembangunan yang dapat diakses masyarakat.

Proyek ini dikembangkan sebagai bagian dari penelitian **“Sistem Informasi Monitoring dan Evaluasi Pembangunan Desa Kutabuluh Berbasis Web”**.

## Daftar Isi

- [Latar Belakang](#latar-belakang)
- [Fitur Utama](#fitur-utama)
- [Peran Pengguna](#peran-pengguna)
- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Perintah Penting](#perintah-penting)
- [Struktur Proyek](#struktur-proyek)
- [Konfigurasi Tambahan](#konfigurasi-tambahan)
- [Keamanan](#keamanan)

## Latar Belakang

Pengelolaan monitoring dan evaluasi pembangunan yang dilakukan secara manual dapat menyulitkan pencarian data, pemantauan perkembangan pekerjaan, dan penyusunan laporan. Aplikasi ini dirancang untuk mengintegrasikan data pembangunan dalam satu sistem, membantu pencatatan progres dan evaluasi, serta meningkatkan keterbukaan informasi pembangunan desa.

## Fitur Utama

- **Informasi publik pembangunan** — menampilkan daftar dan detail proyek pembangunan.
- **Autentikasi pengguna** — login, logout, serta pendaftaran akun rekanan.
- **Dashboard berdasarkan peran** — menyajikan menu dan informasi sesuai hak akses pengguna.
- **Manajemen proyek** — pengelolaan data proyek pembangunan.
- **Penawaran rekanan** — rekanan dapat melihat proyek yang tersedia dan mengajukan penawaran; admin dapat mengelola status penawaran.
- **Monitoring progres** — pencatatan riwayat perkembangan pembangunan serta proses verifikasi progres.
- **Dokumentasi pembangunan** — pengelolaan dokumentasi foto atau berkas terkait proyek.
- **Evaluasi pembangunan dan rekanan** — pencatatan evaluasi proyek serta evaluasi rekanan.
- **Laporan pembangunan** — penyajian laporan per proyek, progres, dan laporan tahunan, termasuk pratinjau atau pencetakan PDF.
- **Pengeluaran proyek** — pencatatan dan pengelolaan pengeluaran yang berkaitan dengan proyek.
- **Komentar masyarakat** — masyarakat dapat memberikan komentar pada informasi proyek; komentar dapat dikelola oleh admin.
- **Manajemen pengguna** — pengelolaan akun pengguna oleh admin.
- **Profil dan notifikasi** — pembaruan profil, perubahan kata sandi, dan pengelolaan notifikasi.

Ketersediaan fitur bergantung pada peran dan hak akses akun yang digunakan.

## Peran Pengguna

Berdasarkan alur dan pengaturan akses aplikasi, peran yang digunakan mencakup:

- **Admin** — mengelola pengguna, proyek, penawaran, monitoring, evaluasi, dokumentasi, laporan, pengeluaran proyek, serta komentar masyarakat.
- **Kepala Desa** — mengakses dashboard dan informasi/laporan pembangunan sesuai hak aksesnya.
- **Rekanan/Mandor** — melihat proyek yang tersedia, mengajukan penawaran, mengelola proyek yang menjadi tanggung jawabnya, dan mengirim progres monitoring.
- **Masyarakat/Pengunjung** — melihat informasi pembangunan yang dipublikasikan dan memberikan komentar melalui halaman publik.

## Teknologi

| Komponen | Teknologi |
|---|---|
| Backend | PHP `^8.3`, Laravel `^13.8` |
| Frontend/build | Vite, Tailwind CSS `^4` |
| JavaScript | Chart.js, Summernote |
| Database bawaan konfigurasi contoh | SQLite |
| Alternatif database | MySQL dapat digunakan setelah konfigurasi `.env` disesuaikan |
| Ekspor laporan | mPDF, Spatie Laravel PDF |
| Ekspor data spreadsheet | Maatwebsite Excel |

## Persyaratan Sistem

Pastikan perangkat pengembangan sudah memiliki:

- PHP 8.3 atau versi yang memenuhi `composer.json`.
- Composer.
- Node.js dan npm.
- Ekstensi PHP yang diperlukan Laravel dan paket yang digunakan.
- Browser modern seperti Chrome, Firefox, atau Edge.

## Instalasi

### 1. Masuk ke direktori proyek

```bash
cd sistem-monitoring-desa
```

### 2. Instal dependensi backend

```bash
composer install
```

### 3. Siapkan konfigurasi environment

Salin `.env.example` menjadi `.env`.

Windows Command Prompt:

```bat
copy .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

Sesuaikan nilai `APP_NAME` dan konfigurasi lainnya sesuai lingkungan lokal. Jangan membagikan file `.env` karena dapat berisi konfigurasi sensitif.

### 4. Buat application key

```bash
php artisan key:generate
```

### 5. Siapkan database

Konfigurasi contoh proyek menggunakan SQLite:

```env
DB_CONNECTION=sqlite
```

Pastikan file database tersedia di `database/database.sqlite`. Jika belum tersedia, buat file kosong bernama `database.sqlite` di dalam folder `database`, lalu jalankan migrasi:

```bash
php artisan migrate
```

Jika ingin menggunakan MySQL, buat database terlebih dahulu kemudian sesuaikan konfigurasi berikut pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_pengguna
DB_PASSWORD=password_database
```

Setelah konfigurasi database benar, jalankan:

```bash
php artisan migrate
```

Jalankan seeder hanya jika tersedia dan diperlukan. Pastikan isi seeder dipahami terlebih dahulu, terutama jika database berisi data penting.

### 6. Instal dependensi frontend

```bash
npm install
```

### 7. Jalankan aplikasi

Cara sederhana: jalankan server Laravel dan Vite di dua terminal terpisah.

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

Buka alamat lokal yang ditampilkan oleh Laravel, biasanya `http://127.0.0.1:8000`.

Alternatif, proyek juga menyediakan skrip Composer untuk menjalankan beberapa proses pengembangan sekaligus:

```bash
composer run dev
```

### 8. Build aset frontend

Untuk membuat aset frontend siap digunakan tanpa Vite development server:

```bash
npm run build
```

## Perintah Penting

| Perintah | Fungsi |
|---|---|
| `php artisan serve` | Menjalankan server pengembangan Laravel |
| `php artisan migrate` | Menjalankan migrasi database |
| `php artisan route:list` | Melihat daftar route aplikasi |
| `php artisan test` | Menjalankan pengujian otomatis |
| `php artisan optimize:clear` | Membersihkan cache konfigurasi, route, dan view |
| `npm run dev` | Menjalankan Vite dalam mode pengembangan |
| `npm run build` | Membuat build aset frontend |
| `composer run dev` | Menjalankan proses pengembangan yang dikonfigurasi pada Composer |

## Struktur Proyek

```text
sistem-monitoring-desa/
├── app/
│   ├── Helpers/           # Helper aplikasi
│   ├── Http/
│   │   ├── Controllers/   # Controller fitur aplikasi
│   │   └── Middleware/    # Middleware, termasuk pemeriksaan peran
│   └── Models/            # Model data
├── database/
│   ├── migrations/        # Struktur dan perubahan database
│   ├── seeders/           # Data awal jika tersedia
│   └── database.sqlite    # File database jika menggunakan SQLite
├── public/                # Aset publik
├── resources/
│   ├── views/             # Template antarmuka Blade
│   ├── css/               # Sumber CSS
│   └── js/                # Sumber JavaScript
├── routes/
│   └── web.php            # Definisi route web
├── storage/               # Log dan berkas yang dikelola aplikasi
├── tests/                 # Pengujian aplikasi
├── composer.json          # Dependensi dan skrip PHP
├── package.json           # Dependensi dan skrip frontend
└── vite.config.js         # Konfigurasi Vite
```

## Konfigurasi Tambahan

### Email

Konfigurasi contoh menggunakan `MAIL_MAILER=log`. Dengan konfigurasi ini, pesan email tidak dikirim melalui layanan email sungguhan, melainkan dicatat pada log. Untuk menggunakan SMTP, sesuaikan variabel `MAIL_*` di `.env` dengan penyedia email yang dipakai.

### Penyimpanan file

Pastikan direktori penyimpanan yang digunakan aplikasi memiliki izin tulis yang sesuai. Jika aplikasi menggunakan file storage Laravel untuk file publik, jalankan perintah berikut bila diperlukan:

```bash
php artisan storage:link
```

### Bahasa dan nama aplikasi

Atur `APP_NAME`, `APP_LOCALE`, dan `APP_FALLBACK_LOCALE` pada `.env` sesuai kebutuhan proyek.

## Pengujian

Jalankan pengujian otomatis dengan:

```bash
php artisan test
```

Pengujian manual juga disarankan untuk alur login, hak akses tiap peran, pengelolaan proyek, pengajuan penawaran, pengiriman dan verifikasi monitoring, evaluasi, komentar masyarakat, serta pembuatan laporan.

## Keamanan

- Jangan mengunggah file `.env`, kredensial database, token, atau `APP_KEY` ke repositori publik.
- Pastikan `.env` tidak ikut dibagikan saat mengirim source code.
- Untuk deployment, gunakan `APP_ENV=production` dan `APP_DEBUG=false`.
- Pastikan hak akses setiap peran diuji sebelum aplikasi digunakan.
- Lakukan backup database dan dokumentasi proyek secara berkala.
- Jangan menjalankan migrasi yang berpotensi mengubah atau menghapus data produksi tanpa backup terlebih dahulu.

## Metode Pengembangan

Dokumen penelitian proyek menjelaskan penggunaan **metode Waterfall**, yang mencakup analisis kebutuhan, perancangan sistem, implementasi, pengujian, dan pemeliharaan. Dokumen tersebut juga menyebut **Black Box Testing** sebagai pendekatan pengujian fungsional.

## Lisensi

Informasi lisensi khusus aplikasi belum dijelaskan secara terpisah di dokumentasi proyek ini. Pastikan lisensi ditetapkan oleh pemilik proyek sebelum aplikasi didistribusikan.
