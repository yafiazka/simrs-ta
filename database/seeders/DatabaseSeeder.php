<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Initial Users
        User::create([
            'username' => 'admin',
            'full_name' => 'Administrator System',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ]);

        User::create([
            'username' => 'perawat',
            'full_name' => 'Perawat 1',
            'password' => bcrypt('password'),
            'role' => 'Perawat',
        ]);

        User::create([
            'username' => 'dokter',
            'full_name' => 'Dr. Dokter Spesialis',
            'password' => bcrypt('password'),
            'role' => 'Dokter',
        ]);

        // Initial Poliklinik
        \App\Models\Poliklinik::create([
            'kd_poli' => 'UMUM',
            'nm_poli' => 'Poli Umum',
            'status' => true,
        ]);

        \App\Models\Poliklinik::create([
            'kd_poli' => 'GIGI',
            'nm_poli' => 'Poli Gigi',
            'status' => true,
        ]);

        \App\Models\Poliklinik::create([
            'kd_poli' => 'KDG',
            'nm_poli' => 'Poli Kandungan',
            'status' => true,
        ]);

        // Sample Pasien
        \App\Models\Pasien::create([
            'no_rkm_medis' => '000001',
            'nm_pasien' => 'Pasien Contoh',
            'no_ktp' => '1234567890123456',
            'jk' => 'L',
            'tgl_lahir' => '1990-01-01',
            'alamat' => 'Alamat Pasien',
            'no_tlp' => '08123456789',
        ]);
    }
}
