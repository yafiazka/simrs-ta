<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->string('kd_kamar', 15)->primary();
            $table->string('nm_kamar', 100);
            $table->string('kelas', 20);
            $table->double('trf_kamar');
            $table->enum('stts', ['ISI', 'KOSONG'])->default('KOSONG');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
