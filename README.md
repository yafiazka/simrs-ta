# SIMRS TA - Sistem Informasi Rumah Sakit

Sistem Informasi Rumah Sakit (SIMRS) berbasis web yang dikembangkan menggunakan Laravel 12, PHP 8.4, dan Filament v3. Aplikasi ini dirancang untuk mengelola data master rumah sakit dan alur pelayanan klinis secara efisien.

## Fitur Utama
- **Dashboard Interaktif**: Statistik pasien real-time dan grafik distribusi poliklinik.
- **Master Data**: Pengelolaan Pasien, Poliklinik, Dokter, dan Kamar.
- **Pelayanan Klinis**: Pendaftaran Pasien dan Resume Medis terintegrasi.
- **Laporan Kelengkapan**: Ekspor data rekam medis ke format Excel/CSV.
- **Custom Auth**: Login menggunakan Username untuk kemudahan operasional.

## Persyaratan Sistem
- Podman / Docker
- Podman Compose / Docker Compose

## Cara Menjalankan

### 1. Clone Repositori
Clone proyek ini ke direktori lokal Anda.

### 2. Jalankan Container
Pilih salah satu instruksi berikut bergantung pada container engine yang Anda gunakan:

* **Menggunakan Podman:**
  ```bash
  podman compose up -d --build
  ```

* **Menggunakan Docker:**
  ```bash
  docker compose up -d --build
  ```

### 3. Install & Build Frontend Assets (Vite)
Jalankan perintah berikut untuk menginstall *dependencies* NPM dan mengompilasi aset *frontend*:

* **Menggunakan Podman:**
  ```bash
  podman compose run --rm node npm install
  podman compose run --rm node npm run build
  ```

* **Menggunakan Docker:**
  ```bash
  docker compose run --rm node npm install
  docker compose run --rm node npm run build
  ```

### 4. Migrasi & Seeding Database
Jalankan perintah berikut untuk membuat skema database dan mengisi data simulasi (seeder):

* **Menggunakan Podman:**
  ```bash
  podman exec -it simrs-ta-app php artisan migrate:fresh --seed
  ```

* **Menggunakan Docker:**
  ```bash
  docker exec -it simrs-ta-app php artisan migrate:fresh --seed
  ```

### 5. Akses Aplikasi
Buka browser dan akses aplikasi melalui tautan berikut:
`http://localhost:2408/admin`

---

## Akses Database

Kontainer database menggunakan PostgreSQL 16. Anda dapat terhubung ke database menggunakan CLI atau aplikasi database client GUI (DBeaver, TablePlus, pgAdmin, dll.).

### Kredensial Koneksi
* **Host**: `localhost` atau `127.0.0.1`
* **Port**: `5432`
* **Database**: `laravel`
* **Username**: `root`
* **Password**: `root123`

### Akses via CLI (psql)
Jika Anda ingin masuk ke terminal interaktif psql langsung di dalam kontainer database:

* **Menggunakan Podman:**
  ```bash
  podman exec -it simrs-ta-db psql -U root -d laravel
  ```

* **Menggunakan Docker:**
  ```bash
  docker exec -it simrs-ta-db psql -U root -d laravel
  ```

---

## Akun Akses
Aplikasi ini memiliki beberapa role akses untuk simulasi berbagai departemen:

| Role | Username | Password | Deskripsi |
|------|----------|----------|-----------|
| **Admin** | `admin` | `password` | Akses penuh ke seluruh fitur dan master data. |
| **Perawat** | `perawat` | `password` | Akses untuk pendaftaran dan manajemen pasien. |
| **Dokter** | `dokter` | `password` | Akses untuk pengisian resume medis. |

## Informasi Penting & Pemeliharaan

### 1. Kemandirian Lingkungan (Self-Contained Container)
Seluruh dependensi sistem dan library (seperti **PhpSpreadsheet** untuk export Excel dan **SimpleSoftwareIO QrCode** untuk cetak QR) beserta seluruh ekstensi PHP yang dibutuhkan (`gd`, `zip`, `xml`, `mbstring`, `bcmath`, `intl`) **berjalan sepenuhnya di dalam kontainer**. 

Anda **TIDAK PERLU** menginstall PHP, Composer, Node.js, atau ekstensi-ekstensi sistem tersebut pada OS komputer lokal (host) Anda. Semua proses eksekusi dan pustaka perangkat lunak telah diwadahi secara mandiri di dalam kontainer Podman/Docker.

### 2. Cara Update Aplikasi & Rebuild Container
Jika terdapat pembaruan kode sumber, perubahan file `Dockerfile`, dependensi di `composer.json` / `package.json`, lakukan langkah-langkah pembaruan berikut:

* **Menggunakan Podman:**
  ```bash
  # 1. Matikan kontainer yang sedang berjalan
  podman compose down

  # 2. Bangun ulang image dan jalankan kontainer baru
  podman compose up -d --build

  # 3. Update & compile ulang aset frontend (jika ada perubahan package/CSS/JS)
  podman compose run --rm node npm install
  podman compose run --rm node npm run build

  # 4. Jalankan migrasi database (jika ada perubahan skema database baru)
  podman exec -it simrs-ta-app php artisan migrate
  ```

* **Menggunakan Docker:**
  ```bash
  # 1. Matikan kontainer yang sedang berjalan
  docker compose down

  # 2. Bangun ulang image dan jalankan kontainer baru
  docker compose up -d --build

  # 3. Update & compile ulang aset frontend (jika ada perubahan package/CSS/JS)
  docker compose run --rm node npm install
  docker compose run --rm node npm run build

  # 4. Jalankan migrasi database (jika ada perubahan skema database baru)
  docker exec -it simrs-ta-app php artisan migrate
  ```

## Teknologi
- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: Filament v3 (TALL Stack)
- **Database**: PostgreSQL 16
- **Container**: Podman / Docker
