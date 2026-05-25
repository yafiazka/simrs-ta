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
  podman compose up -d
  ```

* **Menggunakan Docker:**
  ```bash
  docker compose up -d
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

## Teknologi
- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: Filament v3 (TALL Stack)
- **Database**: PostgreSQL 16
- **Container**: Podman / Docker
