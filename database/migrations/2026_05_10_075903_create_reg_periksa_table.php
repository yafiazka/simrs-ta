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
        Schema::create('reg_periksa', function (Blueprint $table) {
            $table->string('no_rawat')->primary();
            $table->string('no_rkm_medis');
            $table->string('kd_poli');
            $table->dateTime('tgl_registrasi');
            $table->enum('stts', ['Menunggu', 'Diperiksa', 'Batal', 'Selesai'])->default('Menunggu');
            $table->timestamps();

            $table->foreign('no_rkm_medis')->references('no_rkm_medis')->on('pasien')->onDelete('cascade');
            $table->foreign('kd_poli')->references('kd_poli')->on('poliklinik')->onDelete('cascade');
            
            // Indexes for performance as requested in issue.md
            $table->index('tgl_registrasi');
            $table->index('stts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reg_periksa');
    }
};
