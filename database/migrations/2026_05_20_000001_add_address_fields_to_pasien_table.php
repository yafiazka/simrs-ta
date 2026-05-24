<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('kabupaten', 60)->nullable();
            $table->string('kecamatan', 60)->nullable();
            $table->string('kelurahan', 60)->nullable();
            $table->string('desa', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn(['kabupaten', 'kecamatan', 'kelurahan', 'desa']);
        });
    }
};
