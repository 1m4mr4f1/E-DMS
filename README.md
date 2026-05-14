# Enterprise Document Management System (E-DMS) 📄🔒

E-DMS adalah aplikasi manajemen dokumen berbasis Laravel yang mendukung upload dokumen, kontrol akses berbasis peran, dan proteksi akses per divisi.

## 🌟 Fitur Utama

- Authentication dan role-based access control menggunakan **Spatie Laravel Permission**
- Dashboard dan CRUD dokumen dengan upload file dan _visibility_ per dokumen
- Akses dokumen berbasis divisi untuk user `employee`
- `super_admin` dan `admin` dapat melihat semua dokumen
- Versi dokumen, soft delete, dan history upload
- Session driver database dan cache driver database
- Seed data untuk divisi, jabatan, agama, karyawan, user, roles, dan dokumen dasar

## 🛠️ Teknologi

- PHP 8.2+ / Laravel 12
- PostgreSQL
- Blade templates + Vite + Tailwind CSS
- Spatie Laravel Permission untuk roles dan permissions
- Database session dan cache

## 🚀 Persyaratan

- PHP ^8.2
- Composer
- Node.js + npm
- PostgreSQL

## ⚙️ Setup Lokal

1. Clone repository:
   ```bash
   git clone https://github.com/1m4mr4f1/E-DMS.git
   cd E-DMS
   ```

2. Install dependency PHP dan JS:
   ```bash
   composer install
   npm install
   ```

3. Buat file environment:
   ```bash
   cp .env.example .env
   ```

4. Sesuaikan pengaturan database di `.env`:
   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=db_edms
   DB_USERNAME=postgres
   DB_PASSWORD=secret

   SESSION_DRIVER=database
   CACHE_DRIVER=database
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. Build aset Vite / jalankan mode development:
   ```bash
   npm run build
   # atau
   npm run dev
   ```

8. Jalankan server lokal:
   ```bash
   php artisan serve
   ```

## 📌 Seeded Roles & Akun Demo

Aplikasi ini sudah menyiapkan role dan user dasar melalui seeder.

- `super_admin`: `super.admin@example.com` / `password123`
- `admin`: `admin@example.com` / `password123`
- `manager HR`: `siti.aisyah@example.com` / `password123`
- `employee HR`: `ahmad.nur@example.com` / `password123`

> Catatan: akun `super_admin` dan `admin` tidak terkait dengan divisi dan dapat melihat semua dokumen.

## 🧩 Struktur Aplikasi

- `app/Http/Controllers/DocumentController.php` — logika upload, listing, dan penghapusan dokumen
- `app/Models/Document.php` — model dokumen dengan relasi ke `User` dan `Division`
- `app/Models/User.php` — user autentikasi dan relasi ke `Employee`
- `database/migrations/` — migrasi tabel `employees`, `divisions`, `documents`, `roles`, `permissions`, `sessions`, `cache`
- `database/seeders/` — seeder data awal untuk role, employee, user, division, position, religion

## 🧠 Hak Akses Dokumen

- `super_admin`, `admin`: melihat semua dokumen
- `manager`, `employee`: melihat dokumen yang divisinya sama
- Dokumen yang diupload oleh user `super_admin` / `admin` dapat dibuat tanpa divisi dan pada saat ini tidak otomatis terlihat oleh `employee` kecuali diubah logikanya

## 📍 Catatan Tambahan

- `documents` menggunakan kolom `visibility` dengan nilai `division_only` dan `company_wide`
- `user` terhubung ke `employee` dan role ditentukan lewat `spatie/laravel-permission`
- Kustomisasi lebih lanjut dapat dilakukan pada middleware `role:` di `routes/web.php`
