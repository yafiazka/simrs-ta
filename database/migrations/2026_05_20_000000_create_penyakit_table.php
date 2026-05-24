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
        Schema::create('penyakit', function (Blueprint $table) {
            $table->string('kd_penyakit', 15)->primary();
            $table->string('nm_penyakit', 200)->nullable();
            $table->string('nama_penyakit_en', 255)->nullable();
            $table->text('ciri_ciri')->nullable();
            $table->string('keterangan', 60)->nullable();
            $table->string('kd_ktg', 8)->nullable();
            $table->enum('status', ['Menular', 'Tidak Menular'])->default('Tidak Menular');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyakit');
    }
};
