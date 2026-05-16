
# 🚀 Enterprise Document Management System (E-DMS) Portal

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-fullcolor.svg" width="380" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/PostgreSQL-Neon_DB-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
</p>

---

**E-DMS Portal (Enterprise Edition)** adalah platform manajemen dan tata kelola arsip digital korporat berskala besar. Dibangun di atas **Laravel 12** dan **Neon DB (PostgreSQL)**, sistem ini menerapkan pengamanan data berlapis menggunakan metode **Strict Data Isolation** langsung pada level kueri database untuk menjamin kerahasiaan informasi antar unit divisi.

---

## 🌟 Fitur Unggulan Proyek

### 1. 🛡️ Native Architecture Access Control
Sistem tidak lagi bergantung pada *cache* tabel eksternal Spatie Permission yang rentan *bottleneck*. Keamanan dikunci langsung melalui kolom entitas `role_id` pada tabel `users` untuk proses otentikasi secepat kilat:
* `role_id = 1` : **Super Admin** (Akses bypass global mutlak).
* `role_id = 2` : **Manager** (Akses kontrol divisi & persetujuan *workflow*).
* `role_id = 3` : **Corporate User** (Akses operasional standar).

### 2. 🗂️ Strict Document Data Isolation
Penyaringan repositori pada `DocumentController` dan `DashboardController` memastikan transparansi data yang aman:
* **Super Admin** otomatis dapat memantau, mengedit, dan menghapus seluruh dokumen dari semua divisi tanpa terkecuali.
* **Manager & User** hanya diizinkan melihat dokumen yang diset untuk umum (`visibility = 'public'`) **ATAU** dokumen internal privat yang memiliki kesamaan kode `division_id` dengan akun mereka.
* Log aktivitas sistem (*Recent Transactions*) otomatis terfilter sehingga akun non-admin tidak bisa mengintip aktivitas dokumen rahasia divisi lain.

### 3. 👥 User Identity Provisioning Directory
* Halaman CRUD manajemen pengguna khusus untuk Super Admin.
* Terproteksi aman menggunakan interface terbaru **`Illuminate\Routing\Controllers\HasMiddleware`** bawaan Laravel 12.
* Fitur penghapusan akun terintegrasi aman dengan konfirmasi modal *SweetAlert2*.

### 4. ⚙️ Integrated Profile Hub & Security Credentials
* Sinkronisasi dinamis avatar pengguna pada komponen *Fluid Layout Sidebar* dan *Header Dropdown*.
* Fitur unggah foto profil (*Identity Avatar Storage*) otomatis.
* Pembaruan kata sandi aman dilengkapi dengan interseptor pengaman dari sisi *front-end*.
* Pelacakan otomatis riwayat masuk pengguna riil menggunakan kolom `last_login_at`.

---

## 🛠️ Spesifikasi Teknologi

* **Framework Utama:** Laravel 12.x
* **Bahasa Pemrograman:** PHP 8.3+
* **Mesin Database:** PostgreSQL / Neon DB Cloud
* **Kompiler Aset:** Vite + Tailwind CSS
* **Komponen Interaktif:** Vanilla JS + SweetAlert2
* **Sesi & Cache:** Database Driver

---

## 🚀 Panduan Setup Lingkungan Lokal

### 1. Klon Proyek
```bash
git clone [https://github.com/1m4mr4f1/E-DMS.git](https://github.com/1m4mr4f1/E-DMS.git)
cd E-DMS

```

### 2. Pasang Dependensi Komponen

```bash
composer install
npm install && npm run build

```

### 3. Konfigurasi Environtment `.env`

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env

```

Buka file `.env` dan sesuaikan jalur koneksi menuju kluster **Neon DB** Anda:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=your-project-id.neon.tech
DB_PORT=5432
DB_DATABASE=db_edms
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

SESSION_DRIVER=database
CACHE_DRIVER=database

```

### 4. Inisialisasi Kunci & Basis Data

Jalankan perintah generator, migrasi seluruh struktur tabel ERP, beserta pengisian data seeder awal:

```bash
php artisan key:generate
php artisan migrate:fresh --seed

```

### 5. Tautkan Penyimpanan Berkas (*Storage Link*)

Jembatani folder penyimpanan publik agar file avatar dan dokumen fisik dapat diunggah dengan aman:

```bash
php artisan storage:link

```

### 6. Jalankan Aplikasi

```bash
php artisan serve

```

Akses portal melalui peramban Anda di: `http://127.0.0.1:8000`

---

## 📌 Akun Pengujian Demo (Seeded Accounts)

* **Super Admin:** `admin@example.com` | `password123` (Divisi: IT)
* **Alice Manager:** `alice.manager@example.com` | `password123` (Divisi: Human Resources)
* **MsBrew (Manager):** `missbrewaduhai@gmail.com` | `password123` (Divisi: HR - Recruitment)

---

## 🧩 Cetak Biru Komponen Utama Proyek

* `app/Http/Controllers/DashboardController.php` — Mengelola metrik ringkasan data dan isolasi kueri riwayat transaksi log.
* `app/Http/Controllers/DocumentController.php` — Logika pemisahan hak akses repositori utama berkas korporat.
* `app/Http/Controllers/UserController.php` — Proteksi manajemen pembuatan dan pembersihan identitas menggunakan struktur `HasMiddleware` Laravel 12.
* `app/Http/Controllers/ProfileController.php` — Otomasi mutasi data pribadi, hashing password, dan pembaruan berkas avatar.
* `app/Models/User.php` — Entitas model utama pengguna yang dikonfigurasi dengan properti `$fillable` lengkap (`full_name`, `role_id`, `avatar_url`, `last_login_at`).

---

## 🧹 Perintah Pemeliharaan Cache (*Maintenance*)

Jika Anda melakukan modifikasi pada file konfigurasi rute atau komponen Blade, bersihkan cache kompilasi internal melalui terminal:

```bash
php artisan route:clear
php artisan view:clear
php artisan cache:clear

```

---