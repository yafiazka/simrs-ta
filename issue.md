# 🏥 Issue: Roadmap Detail Sistem Informasi Rumah Sakit (Tugas Akhir)
## Ringkasan
Dokumen ini menjadi acuan implementasi proyek **SIMRS TA** berbasis:
- **Framework:** Laravel 13 + Filament PHP
- **Database:** PostgreSQL (Neon.tech)
- **Hosting:** Render.com
- **Local Dev:** Laravel Herd

Fokus utama roadmap ini adalah implementasi alur:
**Master Data → Pendaftaran → Pemeriksaan → Resume Medis → Cetak PDF → Deployment**  
dengan struktur data yang disederhanakan dan siap untuk environment `pgsql`.

## Tujuan
- Membangun aplikasi administrasi klinik/poliklinik yang stabil untuk kebutuhan tugas akhir.
- Menyederhanakan desain database agar cepat diimplementasikan namun tetap realistis.
- Menjamin seluruh konfigurasi database menggunakan **PostgreSQL** (`DB_CONNECTION=pgsql`), bukan MySQL.
- Menyediakan antarmuka operasional berbasis Filament untuk Admin, Perawat, dan Dokter.

## Ruang Lingkup Fitur
### 1) Master Data
- Master pengguna (`users`) dengan role: `Admin`, `Perawat`, `Dokter`.
- Master poliklinik (`poliklinik`) aktif/nonaktif.
- Master pasien (`pasien`) dengan identitas inti.

### 2) Transaksi Klinik
- Registrasi kunjungan (`reg_periksa`) ke poli.
- Status antrean per kunjungan: `Menunggu`, `Diperiksa`, `Batal`, `Selesai`.
- Relasi pasien ke kunjungan dan poli.

### 3) Pemeriksaan & Resume
- Pengisian resume medis (`resume_medis`) oleh dokter.
- Aksi cepat dari antrean untuk mengisi resume.
- Update status otomatis ke `Selesai` saat resume tersimpan.

### 4) Dokumen & Deploy
- Export/Cetak resume medis ke PDF.
- Deploy ke Render dengan koneksi PostgreSQL Neon.

## Desain Database (Disederhanakan untuk PostgreSQL)
> Catatan: Primary key beberapa tabel memakai `string/varchar` untuk kompatibilitas format nomor rekam medis dan nomor rawat.

### Tabel `users`
- `id` (PK, bigint, auto increment)
- `username` (unique)
- `full_name`
- `password`
- `role` (enum aplikasi: Admin, Perawat, Dokter)
- timestamps

Keterangan:
- Dipakai untuk autentikasi dan pembatasan akses menu Filament.
- Tidak memakai relasi tabel wilayah/asuransi.

### Tabel `poliklinik`
- `kd_poli` (PK, string)
- `nm_poli` (string)
- `status` (boolean, default true)
- timestamps

Keterangan:
- Master data poli/ruangan layanan.

### Tabel `pasien`
- `no_rkm_medis` (PK, string)
- `nm_pasien`
- `no_ktp` (nullable)
- `jk` (enum aplikasi: L/P)
- `tgl_lahir` (date, nullable)
- `alamat` (text, nullable)
- `no_tlp` (nullable)
- `agama` (nullable)
- `gol_darah` (nullable)
- timestamps

Keterangan:
- FK wilayah/asuransi dihilangkan untuk menyederhanakan implementasi.

### Tabel `reg_periksa`
- `no_rawat` (PK, string)
- `no_rkm_medis` (FK → `pasien.no_rkm_medis`)
- `kd_poli` (FK → `poliklinik.kd_poli`)
- `tgl_registrasi` (date/time sesuai kebutuhan)
- `stts` (enum aplikasi: Menunggu, Diperiksa, Batal, Selesai)
- timestamps

Keterangan:
- Tabel transaksi utama pendaftaran dan antrean.

### Tabel `resume_medis`
- `id` (PK, bigint, auto increment)
- `no_rawat` (FK unique/one-to-one logis → `reg_periksa.no_rawat`)
- `keluhan` (text, nullable)
- `pemeriksaan_fisik` (text, nullable)
- `diagnosa` (text, nullable)
- `terapi` (text, nullable)
- `tgl_keluar` (date, nullable)
- timestamps

Keterangan:
- Menyimpan form kelengkapan resume medis.

## Standar Konfigurasi Environment (Wajib PostgreSQL)
Gunakan `.env` dengan parameter inti berikut:
- `DB_CONNECTION=pgsql`
- `DB_HOST`, `DB_PORT=5432`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`  
  **atau** cukup `DATABASE_URL` dari Neon jika pola deploy mendukung.

Rekomendasi:
- Lokal: tetap set explicit `DB_*` untuk memudahkan debug.
- Production (Render): set `DATABASE_URL` dan sinkronkan variabel turunan jika dibutuhkan.

## Roadmap Pengerjaan (Target 5 Minggu)
## Minggu 1 — Setup Environment & Fondasi Proyek
### Target Output
- Proyek Laravel berjalan lokal.
- Filament terpasang.
- PostgreSQL Neon terkoneksi.
- Repository Git siap kolaborasi.

### Checklist Detail
- Install Laravel Herd dan pastikan PHP/Composer aktif.
- Inisialisasi project Laravel.
- Buat database PostgreSQL di Neon.
- Atur `.env` ke `DB_CONNECTION=pgsql`.
- Tes koneksi DB (`php artisan migrate:status`).
- Install Filament dan panel.
- Inisialisasi Git dan push awal ke GitHub.

### Definisi Selesai Minggu 1
- Halaman welcome/Filament login dapat diakses.
- Tidak ada error koneksi database.

## Minggu 2 — Modeling Data, Migration, Model, Seeder Dasar
### Target Output
- Seluruh tabel inti terbentuk di PostgreSQL.
- Model + relasi dasar selesai.
- Data awal (role/poli) siap uji.

### Checklist Detail
- Modifikasi migration `users` dengan kolom `role`.
- Buat migration `poliklinik`, `pasien`, `reg_periksa`, `resume_medis`.
- Terapkan FK sesuai skema sederhana.
- Jalankan `php artisan migrate`.
- Buat model + `$fillable` + relasi:
  - `Pasien hasMany RegPeriksa`
  - `Poliklinik hasMany RegPeriksa`
  - `RegPeriksa belongsTo Pasien`
  - `RegPeriksa belongsTo Poliklinik`
  - `RegPeriksa hasOne ResumeMedis`
  - `ResumeMedis belongsTo RegPeriksa`
- Tambah seeder awal untuk role user dan data poliklinik contoh.

### Definisi Selesai Minggu 2
- Struktur database final tervalidasi.
- CRUD via Tinker untuk tiap model berhasil.

## Minggu 3 — Filament Resources (Master & Pendaftaran)
### Target Output
- Halaman admin untuk kelola poli, pasien, dan pendaftaran aktif.
- Form validasi berjalan baik.
- Nomor rawat otomatis terbentuk.

### Checklist Detail
- Generate Filament Resource: `Poliklinik`, `Pasien`, `RegPeriksa`.
- Form `Pasien` dibuat rapi dengan Grid 2 kolom.
- Dropdown relasi pasien-poli pada form pendaftaran.
- Implementasi generator `no_rawat` otomatis (format tanggal + sequence harian).
- Tabel `RegPeriksa` menampilkan badge status berwarna.
- Tambahkan pencarian dan sorting kolom penting.

### Definisi Selesai Minggu 3
- Simulasi alur: tambah pasien → daftar poli → status awal `Menunggu` berhasil.

## Minggu 4 — Workflow Poli, Role Access, Resume Medis
### Target Output
- Akses menu berbasis role berjalan.
- Dokter dapat filter antrean dan isi resume.
- Status kunjungan update otomatis.

### Checklist Detail
- Policy/Gate akses:
  - Admin: semua menu
  - Perawat: master terbatas + pendaftaran
  - Dokter: antrean & resume
- Tambahkan filter antrean harian dan per poli.
- Tambahkan action custom `Isi Resume` di tabel antrean.
- Implementasi modal/form resume medis.
- Saat resume tersimpan:
  - upsert ke `resume_medis`
  - update `reg_periksa.stts` → `Selesai`.
- Cegah duplikasi resume pada `no_rawat` yang sama.

### Definisi Selesai Minggu 4
- Simulasi role Perawat dan Dokter berjalan sesuai batas akses.
- Alur pemeriksaan end-to-end valid.

## Minggu 5 — Laporan PDF, Hardening, Deployment Render
### Target Output
- Resume medis dapat dicetak PDF.
- Aplikasi live di Render dengan PostgreSQL Neon.
- UAT dasar selesai.

### Checklist Detail
- Install `barryvdh/laravel-dompdf`.
- Buat template PDF resume medis.
- Tambahkan tombol `Cetak` pada detail kunjungan/resume.
- Verifikasi font/layout dan data pasien tampil lengkap.
- Deploy ke Render (Web Service + env + build/start command).
- Set environment produksi:
  - `APP_KEY`, `APP_ENV=production`
  - `DB_CONNECTION=pgsql`
  - `DATABASE_URL` Neon
- Jalankan migrate produksi.
- Uji skenario publik:
  - login admin
  - input pasien
  - daftar berobat
  - isi resume
  - cetak PDF.

### Definisi Selesai Minggu 5
- URL live dapat diakses tanpa error kritikal.
- Seluruh alur utama lolos uji manual.

## Risiko Teknis & Mitigasi
- **Mismatch tipe data string PK/FK**  
  Mitigasi: samakan panjang/kolasi dan definisi kolom FK sejak migration awal.

- **Query lambat di antrean**  
  Mitigasi: indeks kolom `tgl_registrasi`, `stts`, `kd_poli`.

- **Perbedaan perilaku lokal vs production**  
  Mitigasi: samakan versi PHP, pakai PostgreSQL juga di lokal, uji migrasi berulang.

- **Role leakage (akses menu bocor)**  
  Mitigasi: audit policy + navigation visibility + proteksi di action handler.

## Acceptance Criteria (Issue Selesai)
- Konfigurasi DB proyek sudah **100% PostgreSQL (`pgsql`)**.
- Seluruh tabel inti dan relasi terbentuk tanpa error migration.
- Resource Filament untuk `poliklinik`, `pasien`, `reg_periksa`, `resume_medis` berfungsi.
- Alur pendaftaran sampai resume berjalan untuk role terkait.
- Fitur cetak PDF resume medis aktif.
- Aplikasi ter-deploy di Render dan terhubung ke Neon PostgreSQL.

## Catatan Implementasi
- Gunakan enum di level aplikasi (PHP/Filament) agar fleksibel, atau PostgreSQL native enum jika tim siap maintenance migration enum.
- Prioritaskan validasi form untuk field nomor identitas dan status kunjungan.
- Pertahankan penamaan konsisten antara migration, model, dan resource untuk menghindari bug relasi.
