<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjab', function (Blueprint $table) {
            $table->char('kd_pj', 3)->primary();
            $table->string('png_jawab', 30);
            $table->string('nama_perusahaan', 60);
            $table->string('alamat_asuransi', 130);
            $table->string('no_telp', 40);
            $table->string('attn', 60);
            $table->enum('status', ['0', '1']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjab');
    }
};
