# Enterprise Document Management System (E-DMS) 📄🔒

[cite_start]Enterprise Document Management System (E-DMS) adalah sistem manajemen dokumen berskala *enterprise* yang aman, *scalable*, *auditable*, dan kolaboratif[cite: 20]. [cite_start]Sistem ini dirancang sebagai *single source of truth* untuk menggantikan kebiasaan penyimpanan dokumen yang tersebar (shared drive, email) pada divisi-divisi yang menangani dokumen sensitif dan teregulasi[cite: 22, 33, 34].

[cite_start]Proyek ini dibangun menggunakan arsitektur **Modular Monolith** dengan rilis bertahap, berfokus pada fungsionalitas inti (MVP 1) sebelum berekspansi ke fitur lanjutan[cite: 23].

## 🌟 Fitur Utama (MVP 1 Scope)
* [cite_start]**Authentication & RBAC:** Kontrol akses berbutir halus (granular) menggunakan *Role* dan *Permission* (Spatie) yang membatasi akses pada level API dan UI[cite: 48, 163, 167].
* [cite_start]**Core Document Management:** Mendukung *single*, *bulk*, dan *resumable upload* (Uppy.js) dengan validasi MIME *magic bytes* [cite: 187-189, 312]. [cite_start]File disimpan terpisah di Object Storage (MinIO/AWS S3)[cite: 27, 60].
* **Versioning System:** Riwayat versi dokumen yang tidak destruktif. [cite_start]File lama tidak dihapus, dan sistem mendukung fitur *rollback*[cite: 37, 216, 220].
* [cite_start]**Approval Workflow:** Alur persetujuan terstruktur dengan transisi status (*draft* -> *submitted* -> *approved*/*rejected* -> *published*)[cite: 266].
* [cite_start]**Immutable Audit Log:** Jejak audit *append-only* (tidak bisa diubah/dihapus) yang mencatat setiap aktivitas krusial untuk pemenuhan standar kepatuhan (ISO, SOX)[cite: 28, 37, 293].

## 🛠️ Technology Stack

[cite_start]Sistem ini menyeimbangkan kecepatan pengembangan, biaya operasional, dan kemudahan evolusi arsitektur[cite: 57].

**Backend & Database:**
* [cite_start]**Framework:** Laravel 12 (PHP 8.4) [cite: 60]
* [cite_start]**Database:** Neon Serverless PostgreSQL (Managed, Auto-scaling) [cite: 60, 73]
* [cite_start]**Queue & Cache:** Redis + Laravel Queue [cite: 60]
* [cite_start]**File Storage:** MinIO / AWS S3 Compatible [cite: 60]

**Frontend:**
* [cite_start]**Framework:** Next.js (React, App Router) [cite: 62]
* [cite_start]**Styling & UI:** TailwindCSS + shadcn/ui [cite: 62]
* [cite_start]**State Management:** Zustand [cite: 62]

**Infrastructure / DevOps:**
* [cite_start]**Containerization:** Docker (App, Worker, Redis) [cite: 67]
* [cite_start]**CI/CD:** GitHub Actions [cite: 67]

## 🏗️ Arsitektur & Konsep Inti

1.  **Database Serverless & Connection Pooling:** Menggunakan Neon PostgreSQL. [cite_start]Komunikasi API standar wajib menggunakan koneksi `pooler`, sementara eksekusi migrasi menggunakan koneksi `direct`[cite: 82].
2.  **Atomic DB Transactions:** Semua operasi *multi-step* (seperti *upload* versi baru atau *approval* dokumen) dibungkus dalam blok transaksi database. [cite_start]Jika satu gagal, seluruh proses di-*rollback*[cite: 30, 223].
3.  [cite_start]**Asynchronous Processing:** Operasi berat (seperti pembuatan *thumbnail*, *scan* antivirus, pengiriman notifikasi) tidak dilakukan secara sinkron pada *request* HTTP, melainkan didelegasikan ke *Queue Worker* via Redis[cite: 29, 273, 343].

## ⚙️ Persyaratan Lingkungan (Prerequisites)
* [cite_start]Docker & Docker Compose [cite: 67, 137]
* [cite_start]Akun Neon Tech (Database) [cite: 70]
* [cite_start]Akun MinIO/AWS S3 (Object Storage) [cite: 60]
* [cite_start]PHP 8.4 & Composer (Untuk eksekusi lokal di luar container) [cite: 60]

## 🚀 Setup & Instalasi Lokal

1. **Clone Repository**
   ```bash
   git clone [https://github.com/1m4mr4f1/E-DMS.git](https://github.com/1m4mr4f1/E-DMS.git)
   cd E-DMS