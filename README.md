# Aplikasi Monitoring Pekerjaan (Monitoring Proyek Bulog)

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-^8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)

**Sistem Monitoring Pekerjaan (Monitoring Proyek Bulog)** adalah aplikasi berbasis web yang dirancang untuk mempermudah monitoring, pencatatan, dan evaluasi seluruh siklus hidup proyek di lingkungan **Perum BULOG**. Sistem ini mencakup pengelolaan proyek dari tahap perizinan prinsip, administrasi kontrak kerja sama dengan rekanan, pengawasan realisasi fisik berkala, pembayaran termin biaya, hingga pengarsipan dokumen proyek terpusat.

---

## 📌 Fitur Utama

- **📊 Dashboard Eksekutif & Statistik Interaktif**:
  - Ringkasan cepat metrik utama (*Quick Stats*): total proyek, total kontrak, pagu anggaran, serta realisasi biaya.
  - Grafik dan diagram progres fisik vs realisasi biaya.
  - Kalender/event kegiatan proyek dan notifikasi peringatan (*alerts*).
  - Filter analitik berdasarkan kantor wilayah dan status proyek.

- **🏢 Manajemen Kantor Wilayah**:
  - Manajemen kantor wilayah / cabang Bulog sebagai unit kerja pelaksana proyek.
  - Statistik performa proyek per kantor.

- **📁 Manajemen Data Proyek**:
  - Perekaman data proyek (nama proyek, deskripsi, tanggal mulai, dll).
  - Pengelolaan berkas **Izin Prinsip** dengan fitur upload dan download aman.

- **📑 Manajemen Kontrak & SPK**:
  - Klasifikasi bagian pekerjaan: **Perencana**, **Pelaksana**, **Pengawas**, dan **Pengelola Teknis**.
  - Pencatatan rekanan/mitra, nomor SPK, pagu biaya, nilai kontrak, jangka waktu pengerjaan, dan masa pemeliharaan.
  - Penyimpanan dan pengunduhan berkas fisik Surat Perintah Kerja (SPK).

- **💰 Monitoring Biaya & Termin Pembayaran**:
  - Pengelolaan termin pembayaran per kontrak secara fleksibel.
  - Validasi batas maksimal termin agar tidak melebihi nilai kontrak.
  - Pelacakan histori pengeluaran anggaran proyek.

- **📈 Progres Realisasi Fisik Lapangan**:
  - Input dan monitoring persentase kemajuan pekerjaan fisik di lapangan secara berkala.
  - Evaluasi deviasi dan performa ketepatan waktu proyek.

- **📂 Repositori Dokumen Proyek**:
  - Pengarsipan berkas teknis, laporan mingguan/bulanan, dan dokumentasi foto progres.
  - Kemudahan akses unduh file dokumen terstruktur per proyek.

- **👥 Manajemen Pengguna & Hak Akses (Multi-Role)**:
  - Role **Administrator**: Akses menyeluruh ke manajemen user, konfigurasi sistem, dan seluruh modul data.
  - Role **Staff**: Akses operasional untuk pembaruan data proyek, kontrak, realisasi, dan upload dokumen.
  - Fitur keamanan: Reset password, penonaktifan user, serta mekanisme *Soft Deletes* dan *Restore*.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Bahasa Pemrograman**: PHP ^8.2
- **Basis Data**: MySQL / MariaDB
- **Frontend / Styling**: Blade Templating, Bootstrap 5, SCSS, JavaScript (AJAX & Fetch API)
- **Ekspor Dokumen**: [Barryvdh Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf)
- **Asset Bundling**: [Vite](https://vitejs.dev/)

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan pengembangan lokal (misalnya menggunakan Laragon, XAMPP, atau PHP CLI):

### 1. Prasyarat Sistem
- PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & NPM
- MySQL / MariaDB Server

### 2. Kloning Repository
```bash
git clone https://github.com/PahniHanawan/Aplikasi-Monitoring-Pekerjaan.git
cd Aplikasi-Monitoring-Pekerjaan
```

### 3. Install Dependensi
```bash
# Dependensi PHP
composer install

# Dependensi Frontend
npm install
```

### 4. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Buka file `.env` lalu sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monitoring_bulog
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key & Storage Link
```bash
php artisan key:generate
php artisan storage:link
```

### 6. Migrasi Database & Seeder
Buat database di MySQL (misal: `monitoring_bulog`), lalu jalankan migrasi dan data awal (seeder):
```bash
php artisan migrate --seed
```

### 7. Jalankan Server Pengembangan
Buka 2 terminal (atau gunakan script dev yang tersedia):

Terminal 1 (Laravel Server):
```bash
php artisan serve
```

Terminal 2 (Vite Assets):
```bash
npm run dev
```

Aplikasi dapat diakses melalui browser di: `http://127.0.0.1:8000`

---

## 🔑 Akun Default (Hasil Seeder)

Setelah menjalankan `php artisan db:seed`, Anda dapat masuk menggunakan akun berikut:

| Peran (Role) | Email | Password |
|---|---|---|
| **Administrator** | `admin@bulog.co.id` | `password123` |
| **Staff Operasional** | `staff@bulog.co.id` | `password123` |

> ⚠️ *Catatan: Segera ubah kata sandi default setelah pertama kali masuk untuk menjaga keamanan data.*

---

## 📁 Struktur Direktori Penting

```plaintext
├── app/
│   ├── Http/Controllers/      # Controller Modul (Proyek, Kontrak, Biaya, Realisasi, dll)
│   └── Models/               # Model Eloquent & Relasi Database
├── database/
│   ├── migrations/           # Skema tabel database
│   └── seeders/              # Data inisial default (Kantor, User, Proyek, dll)
├── public/
│   ├── assets/               # File CSS, SCSS, JS, Libs, & Image template
│   └── storage/              # Simbolik tautan ke file unggahan proyek
├── resources/
│   └── views/                # Tampilan Blade template & komponen UI
└── routes/
    └── web.php               # Rute aplikasi dan API internal
```

---

## 📄 Lisensi

Aplikasi ini dikembangkan untuk kebutuhan internal monitoring proyek. Hak cipta dilindungi oleh pengembang dan entitas terkait.
