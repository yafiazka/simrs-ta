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
        Schema::table('resume_medis', function (Blueprint $table) {
            $table->string('tensi', 20)->nullable();
            $table->string('tb', 20)->nullable();
            $table->string('bb', 20)->nullable();
            $table->string('respirasi', 20)->nullable();
            $table->string('gcs', 20)->nullable();
            $table->string('nadi', 20)->nullable();
            $table->string('suhu', 20)->nullable();
            $table->string('spo2', 20)->nullable();
            $table->text('instruksi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resume_medis', function (Blueprint $table) {
            $table->dropColumn([
                'tensi', 'tb', 'bb', 'respirasi', 'gcs', 'nadi', 'suhu', 'spo2', 'instruksi'
            ]);
        });
    }
};
