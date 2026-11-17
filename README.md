# WORKSHOPTIX – Digital Workshop Registration & Ticketing System

**WORKSHOPTIX** adalah platform pendaftaran dan tiket digital workshop IT yang memungkinkan peserta memilih workshop, mengisi formulir pendaftaran terverifikasi (anti-duplicate), memproses pembayaran, serta mendapatkan e-ticket digital resmi ber-QR Code setelah pembayaran berhasil diverifikasi oleh admin / panitia.

Aplikasi ini dikembangkan berdasarkan spesifikasi **LKPD XI RPL – Drill Pra Sertifikasi Kejuruan SMK Pesat IT XPRO (Rekayasa Perangkat Lunak)**.

---

## Fitur Utama

### 1. Workshop Management (CRUD)
- Menampilkan katalog workshop IT interaktif (Web Development, Cloud & DevOps, Cyber Security, AI & Data Science, Mobile Dev, UI/UX).
- Informasi detail: Judul, deskripsi, silabus poin pembelajaran, pemateri & profil, tanggal, waktu, format (offline/online/hybrid), lokasi/venue, biaya registrasi, dan indikator kuota real-time.
- Status workshop dinamis: **Available** (Tersedia), **Full** (Penuh otomatis saat kuota habis), dan **Closed** (Ditutup).
- Panitia dapat menambah, mengubah, menghapus, serta membuka/menutup status workshop.

### 2. User Authentication & Role-Based Access Control
- Autentikasi lengkap: Register, Login, Logout, Remember Me, serta proteksi Middleware.
- Dua peran pengguna:
  - **Panitia (Admin)**: Hak akses penuh ke dashboard statistik, CRUD workshop, verifikasi pembayaran, manajemen pendaftar, dan gate scanner check-in.
  - **Peserta (Participant)**: Akses ke katalog, registrasi workshop, konfirmasi pembayaran, serta halaman *Tiket Saya*.
- **Anti-Duplicate Registration**: Sistem secara ketat menolak pendaftaran ganda dari peserta yang sama pada workshop yang sama (Constraint Unique Database & Validasi Backend).
- **Proteksi Akses Data**: Peserta tidak dapat membuka atau mengakses tiket dan transaksi milik peserta lain (HTTP 403 Forbidden).

### 3. Payment Processing & Verification
- Beragam metode pembayaran: Bank Transfer BCA, Bank Transfer Mandiri, QRIS Dinamis, dan Midtrans Sandbox Simulator.
- Formulir upload bukti struk / screenshot pembayaran dengan pratinjau instan.
- **Midtrans Sandbox Simulator**: Tombol simulasi bayar instan untuk keperluan pengujian dan drill sertifikasi tanpa transfer uang nyata.
- Portal panitia untuk memverifikasi atau menolak bukti pembayaran disertai catatan alasan penolakan.

### 4. Digital E-Ticket & Gate QR Code Scanner
- Penerbitan otomatis e-ticket digital boarding pass ber-QR Code setelah pembayaran diverifikasi.
- QR Code SVG beresolusi tinggi yang dihasilkan menggunakan library `chillerlan/php-qrcode` (tanpa ketergantungan ekstensi imagick).
- Endpoint verifikasi publik `/tickets/verify/{code}` saat QR code di-scan.
- Halaman cetak ramah printer (`@media print` clean printable pass).
- **Gate Scanner Meja Registrasi**: Tool presensi gate bagi panitia untuk memvalidasi tiket masuk dan mencatat waktu check-in (mencegah tiket digunakan dua kali).

### 5. Optimasi Database & Pencegahan Masalah N+1 Query
- Implementasi Eager Loading pada seluruh relasi model (`with(['user', 'workshop', 'payment', 'ticket'])`).
- Mengurangi query database dari **1 + (N × 4) queries** menjadi **hanya 5 queries**, menghemat beban query database hingga 98%.
- `Model::preventLazyLoading(! app()->isProduction())` aktif untuk mencegah lazy loading yang tidak disengaja selama masa pengembangan.

---

## Teknologi & Arsitektur

- **Framework**: Laravel 12 / 13 (PHP 8.5)
- **Database**: MySQL Relational Database
- **Frontend**: Blade Templating + Tailwind CSS v4 (`@tailwindcss/vite`)
- **QR Code Engine**: `chillerlan/php-qrcode` (SVG Vector Output)
- **Icons**: Lucide Icons Style SVG
- **Testing**: Pest PHP & PHPUnit (13 Feature & Unit Tests – 100% Passed)
- **Code Style**: Laravel Pint

---

## Akun Demo Pengujian

Aplikasi telah dilengkapi tombol **Uji Akses Cepat** di navigasi atas untuk login instan 1-klik:

| Peran (Role) | Email | Password | Hak Akses |
|---|---|---|---|
| **Panitia (Admin)** | `admin@workshoptix.test` | `password` | Dashboard Panitia, CRUD Workshop, Verifikasi Pembayaran, Scanner Gate |
| **Peserta Demo** | `peserta@workshoptix.test` | `password` | Katalog Workshop, Formulir Registrasi, Pembayaran, Tiket Saya |

---

## Cara Menjalankan Aplikasi

### 1. Kloning Repository & Masuk Direktori
```bash
cd workshopTix
```

### 2. Konfigurasi Environment
Salin file `.env.example` ke `.env`:
```bash
copy .env.example .env
```
Pastikan pengaturan database MySQL di `.env` sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshopTix
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependensi & Generate Key
```bash
composer install
php artisan key:generate
php artisan storage:link
```

### 4. Migrasi & Seed Database
Jalankan migrasi database beserta data dummy realistis:
```bash
php artisan migrate --seed
```

### 5. Kompilasi Frontend
```bash
npm install
npm run build
```

### 6. Menjalankan Server Lokal
```bash
php artisan serve
```
Buka browser pada alamat: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## Menjalankan Unit & Feature Testing

Jalankan test suite Pest untuk memverifikasi fungsionalitas:
```bash
php artisan test --compact
```
Atau:
```bash
vendor/bin/pest
```

Semua 13 pengujian mencakup:
- Pemuatan halaman katalog, detail, dan landing page
- Registrasi dan login pengguna
- Penolakan pendaftaran ganda (*anti-duplicate*)
- Pencegahan akses tiket milik akun lain (403)
- Proteksi route admin bagi peserta (403)
- Simulasi sandbox bayar & penerbitan tiket otomatis
- Verifikasi pembayaran manual oleh admin
- Endpoint verifikasi QR Code tiket & Gate Scanner presensi
