<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE reg_periksa DROP CONSTRAINT IF EXISTS reg_periksa_stts_check");
            DB::statement("ALTER TABLE reg_periksa ADD CONSTRAINT reg_periksa_stts_check CHECK (stts::text IN ('Belum', 'Sudah', 'Batal', 'Berkas Diterima', 'Dirujuk', 'Meninggal', 'Dirawat', 'Pulang Paksa', 'Menunggu', 'Diperiksa', 'Selesai'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE reg_periksa DROP CONSTRAINT IF EXISTS reg_periksa_stts_check");
            DB::statement("ALTER TABLE reg_periksa ADD CONSTRAINT reg_periksa_stts_check CHECK (stts::text IN ('Belum', 'Sudah', 'Batal', 'Berkas Diterima', 'Dirujuk', 'Meninggal', 'Dirawat', 'Pulang Paksa', 'Menunggu'))");
        }
    }
};
