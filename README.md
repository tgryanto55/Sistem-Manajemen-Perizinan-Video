# 🎥 Sistem Manajemen Perizinan Video (Technical Test - Mediatama)

[![Framework](https://img.shields.io/badge/Framework-CodeIgniter%204.7-EF4444?style=for-the-badge&logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![Styling](https://img.shields.io/badge/CSS-Tailwind%204.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![JS](https://img.shields.io/badge/Frontend-Alpine.js%20%26%20HTMX-000000?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)


Repositori ini berisi solusi untuk tugas technical test "Web Developer" dari Mediatama. Proyek ini dibangun dengan standar profesional menggunakan **Clean Architecture**, keamanan berlapis, dan pengalaman pengguna (UX) berperforma tinggi.

---

## Kepatuhan Terhadap Soal Tes

Sistem ini telah memenuhi seluruh kriteria yang diminta dalam soal:
1.  **Sistem Perizinan**: Alur lengkap permintaan & persetujuan akses video.
2.  **2 Level User**: Tersedia role Admin dan Customer dengan hak akses berbeda.
3.  **Hak Akses Admin**: CRUD data Customer, CRUD data Video, dan fitur Approve perizinan.
4.  **Hak Akses Customer**: Fitur Request akses dan menonton video yang sudah disetujui.
5.  **Aturan Teknis**:
    -   Menggunakan **Framework CodeIgniter 4**.
    -   Terdiri dari **4 Tabel Utama** atau lebih (`users`, `videos`, `video_access_requests`, `migrations`).
    -   **Skala Waktu Terbatas**: Admin dapat menentukan durasi akses (Jam/Menit), dan akses otomatis hangus saat waktu habis.
    -   **Mekanisme Request Ulang**: Customer dapat meminta kembali akses setelah masa berlaku habis.

---

## Keputusan Arsitektur Utama

### Design Patterns & Arsitektur
-   **Action Pattern (`app/Actions`)**: Memisahkan logika penulisan (write operations) dari Controller agar kode lebih rapi dan mudah diuji.
-   **Service Layer (`app/Services`)**: Pusat logika domain seperti pengecekan izin akses dan kalkulasi waktu kedaluwarsa.
-   **Optimasi Database**: Menggunakan query `LEFT JOIN` (lihat `VideoModel`) untuk mengatasi masalah **N+1 problem**, memastikan sistem tetap cepat meski data bertambah banyak.
-   **Keamanan Global**: Proteksi CSRF diaktifkan secara global, termasuk integrasi dengan header AJAX **HTMX**.

### Stack Teknologi Modern
-   **HTMX**: Memberikan pengalaman Single Page Application (SPA) tanpa beban berat JavaScript Framework.
-   **Alpine.js**: Digunakan untuk interaktivitas ringan seperti Modal, Toast Notifications, dan Real-time Counter.
-   **Tailwind CSS v4**: Desain modern dan responsif dengan fitur-fitur CSS terbaru.

---

## Struktur Folder Proyek

Berikut adalah struktur direktori lengkap proyek ini, diverifikasi sesuai kondisi file aktual:

```bash
app/
├── Actions/                  # LOGIC WRITE LAYER (Command Pattern)
│   ├── User/
│   │   └── CreateCustomerAction.php       # Menangani pembuatan user tipe 'customer'
│   └── Video/
│       ├── ApproveVideoAccessAction.php   # Menyetujui akses & set waktu expired
│       ├── RejectVideoAccessAction.php    # Menolak permintaan akses
│       └── RequestVideoAccessAction.php   # Membuat request akses baru
│
├── Services/                 # BUSINESS LOGIC LAYER
│   ├── AuthService.php          # Enkapsulasi login, logout, & cek session
│   ├── VideoAccessService.php   # Cek apakah user punya izin akses aktif
│   └── VideoDurationService.php # Helper kalkulasi durasi (addHours/addMinutes)
│
├── Models/                   # DATA ACCESS LAYER
│   ├── UserModel.php            # Model tabel 'users' (+ hash password otomatis)
│   ├── VideoModel.php           # Model tabel 'videos' (+ query optimized)
│   └── VideoAccessRequestModel.php # Model tabel transaksional 'video_access_requests'
│
├── Filters/                  # SECURITY MIDDLEWARE
│   ├── AdminFilter.php          # Mencegah non-admin masuk ke halaman admin
│   ├── CustomerFilter.php       # Mencegah non-customer masuk ke halaman customer
│   └── AuthFilter.php           # Memastikan user sudah login sebelum akses
│
├── Controllers/              # HTTP ENTRY POINTS (Thin Controllers)
│   ├── Admin/
│   │   ├── AccessRequestController.php    # Kelola approval request
│   │   ├── CustomerController.php         # CRUD data customer
│   │   ├── DashboardController.php        # Halaman dashboard admin
│   │   └── VideoController.php            # CRUD data video
│   ├── Customer/
│   │   ├── DashboardController.php        # Halaman dashboard customer
│   │   └── VideoController.php            # Halaman list & nonton video
│   └── Auth/
│       ├── LoginController.php            # Proses login
│       └── LogoutController.php           # Proses logout
│
├── Views/                    # PRESENTATION LAYER
│   ├── admin/
│   │   ├── customers/        # View manajemen customer (tabel, form)
│   │   ├── videos/           # View manajemen video (tabel, form)
│   │   └── dashboard.php     # Tampilan statistik admin
│   ├── customer/
│   │   ├── videos/           # View galeri & player video
│   │   └── dashboard.php     # Tampilan beranda customer
│   ├── layouts/
│   │   ├── app.php           # Layout utama (Sidebar, Navbar, Toast)
│   │   └── guest.php         # Layout khusus halaman login
│   └── auth/
│       └── login.php         # Form login
│
├── Config/                   # CONFIGURATION
│   ├── Routes.php            # Definisi semua URL & Grouping Route
│   ├── Filters.php           # Mapping alias filter ke class
│   ├── Database.php          # Konfigurasi koneksi database
│   └── ... (File Config Lainnya)
│
└── Database/                 # MIGRATIONS & SEEDS
    ├── Migrations/
    │   ├── CreateUsersTable.php           # Schema tabel users
    │   ├── CreateVideosTable.php          # Schema tabel videos
    │   ├── CreateVideoAccessRequestsTable.php # Schema tabel request
    │   └── AddDurationToVideos.php        # Update schema (alter table)
    └── Seeds/
        └── UserSeeder.php                 # Data dummy akun Admin & Customer
```

---

## �🛠️ Instruksi Instalasi

### 1. Prasyarat
- **PHP**: `^8.2` (aktifkan ekstensi `intl`, `mbstring`, `gd`)
- **Composer**: Manajemen dependensi PHP
- **Node.js & NPM**: Untuk kompilasi aset (Tailwind & Esbuild)
- **Database**: MySQL/MariaDB

### 2. Cara Instal
```bash
# Clone repositori
git clone <link-github-anda>
cd ci4-video-permission

# Instal dependensi PHP
composer install

# Instal dependensi JS & Build Aset
npm install
npm run build
```

### 3. Setup Database
1. Buat database baru di MySQL (contoh: `ci4_video_permission`).
2. Sesuaikan konfigurasi database di file `.env`.
3. Jalankan migrasi dan seeder:
```bash
php spark migrate
php spark db:seed UserSeeder
```

### 4. Jalankan Aplikasi
```bash
php spark serve
```
Akses di: `http://localhost:8080`

---

## Kredensial Akun Default

| Level | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@example.com` | `password` |
| **Customer** | `customer@example.com` | `password` |

---

## Pengembangan Berbasis AI

Proyek ini dikembangkan menggunakan workflow **AI-assisted development** menggunakan **Google Antigravity**.

---

## Lisensi
Proyek tes ini tersedia di bawah [MIT License](LICENSE).
