<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table) {
            $table->string('kd_dokter', 20)->primary();
            $table->string('nm_dokter', 100);
            $table->string('spesialis', 50);
            $table->string('no_telp', 20)->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
