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
1. Clone repositori ini.
2. Jalankan container:
   ```bash
   podman compose up -d
   ```
3. Jalankan migrasi dan seeder (pertama kali):
   ```bash
   podman exec simrs-ta-app php artisan migrate:fresh --seed
   docker exec simrs-ta-app php artisan migrate:fresh --seed
   ```
4. Akses aplikasi di: `http://localhost:8000/admin`

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
- **Container**: Podman
