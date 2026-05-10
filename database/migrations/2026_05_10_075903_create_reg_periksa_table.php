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
            $table->string('no_reg', 8)->nullable();
            $table->string('no_rawat', 17)->primary();
            $table->date('tgl_registrasi')->nullable();
            $table->time('jam_reg')->nullable();
            $table->string('kd_dokter', 20)->nullable();
            $table->string('no_rkm_medis', 15)->nullable();
            $table->char('kd_poli', 5)->nullable();
            $table->string('p_jawab', 100)->nullable();
            $table->string('almt_pj', 200)->nullable();
            $table->string('hubunganpj', 20)->nullable();
            $table->double('biaya_reg')->nullable();
            $table->enum('stts', ['Belum', 'Sudah', 'Batal', 'Berkas Diterima', 'Dirujuk', 'Meninggal', 'Dirawat', 'Pulang Paksa'])->nullable();
            $table->enum('stts_daftar', ['-', 'Lama', 'Baru']);
            $table->enum('status_lanjut', ['Ralan', 'Ranap']);
            $table->char('kd_pj', 3);
            $table->integer('umurdaftar')->nullable();
            $table->enum('sttsumur', ['Th', 'Bl', 'Hr'])->nullable();
            $table->enum('status_bayar', ['Sudah Bayar', 'Belum Bayar']);
            $table->enum('status_poli', ['Lama', 'Baru']);
            $table->dateTime('jam_panggil')->nullable();
            $table->timestamps();

            $table->foreign('no_rkm_medis')->references('no_rkm_medis')->on('pasien')->onUpdate('cascade');
            $table->foreign('kd_poli')->references('kd_poli')->on('poliklinik')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kd_pj')->references('kd_pj')->on('penjab')->onUpdate('cascade');
            
            $table->index('tgl_registrasi');
            $table->index('stts');
            $table->index('status_lanjut');
            $table->index('status_bayar');
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
