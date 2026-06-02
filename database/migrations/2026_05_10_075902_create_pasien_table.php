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
        Schema::create('pasien', function (Blueprint $table) {
            $table->string('no_rkm_medis', 15)->primary();
            $table->string('nm_pasien', 100);
            $table->string('no_ktp', 20)->nullable();
            $table->enum('jk', ['L', 'P'])->nullable();
            $table->string('tmp_lahir', 35)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('nm_ibu', 40);
            $table->string('alamat', 200)->nullable();
            $table->enum('gol_darah', ['A', 'B', 'O', 'AB', '-', 'O+', 'A+', 'B+', 'AB+', 'O-', 'A-', 'B-', 'AB-'])->nullable();
            $table->string('pekerjaan', 60)->nullable();
            $table->enum('stts_nikah', ['BELUM MENIKAH', 'MENIKAH', 'JANDA', 'DUDA', 'JOMBLO'])->nullable();
            $table->string('agama', 12)->nullable();
            $table->date('tgl_daftar')->nullable();
            $table->string('no_tlp', 40)->nullable();
            $table->string('umur', 30);
            $table->enum('pnd', ['TS', 'TK', 'SD', 'SMP', 'SMA', 'SLTA/SEDERAJAT', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', '-']);
            $table->enum('keluarga', ['AYAH', 'IBU', 'ISTRI', 'SUAMI', 'SAUDARA', 'ANAK', 'DIRI SENDIRI', 'LAIN-LAIN'])->nullable();
            $table->string('namakeluarga', 50);
            $table->char('kd_pj', 3);
            $table->string('no_peserta', 25)->nullable();
            $table->string('pekerjaanpj', 35)->nullable();
            $table->string('alamatpj', 100)->nullable();
            $table->string('kelurahanpj', 60)->nullable();
            $table->string('kecamatanpj', 60)->nullable();
            $table->string('kabupatenpj', 60)->nullable();
            $table->string('email', 50)->nullable();
            $table->timestamps();

            $table->foreign('kd_pj')->references('kd_pj')->on('penjab')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reg_periksa', function (Blueprint $table) {
            $table->dropForeign(['no_rkm_medis']);
        });

        Schema::dropIfExists('pasien');
    }
};
