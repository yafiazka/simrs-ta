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
        Schema::table('reg_periksa', function (Blueprint $table) {
            $table->enum('status_kunjungan', ['Baru', 'Lama'])->nullable();
            $table->text('diagnosa_awal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reg_periksa', function (Blueprint $table) {
            $table->dropColumn(['status_kunjungan', 'diagnosa_awal']);
        });
    }
};
