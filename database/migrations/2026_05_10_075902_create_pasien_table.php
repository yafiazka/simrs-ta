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
            $table->string('no_rkm_medis')->primary();
            $table->string('nm_pasien');
            $table->string('no_ktp')->nullable();
            $table->enum('jk', ['L', 'P']);
            $table->date('tgl_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_tlp')->nullable();
            $table->string('agama')->nullable();
            $table->string('gol_darah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
