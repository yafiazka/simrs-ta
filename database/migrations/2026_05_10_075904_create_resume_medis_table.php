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
        Schema::create('resume_medis', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 17)->unique();
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_keluar')->nullable();
            $table->string('kd_dokter', 20)->nullable();
            $table->text('keluhan')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->text('diagnosa_masuk')->nullable();
            $table->text('indikasi_rawat_inap')->nullable();
            $table->text('diagnosa_utama')->nullable();
            $table->text('diagnosa_sekunder')->nullable();
            $table->text('tindakan_prosedur')->nullable();
            $table->text('terapi_pulang')->nullable();
            $table->text('alergi_obat')->nullable();
            $table->text('kondisi_pulang')->nullable();
            $table->text('rencana_lanjut')->nullable();
            $table->text('ringkasan_riwayat')->nullable();
            $table->text('hasil_penunjang')->nullable();
            $table->string('cara_keluar', 50)->nullable();
            $table->timestamps();

            $table->foreign('no_rawat')->references('no_rawat')->on('reg_periksa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_medis');
    }
};
