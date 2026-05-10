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
            $table->string('no_rawat')->unique();
            $table->text('keluhan')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->text('diagnosa')->nullable();
            $table->text('terapi')->nullable();
            $table->date('tgl_keluar')->nullable();
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
