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

        $this->call([
            PenyakitSeeder::class,
            PenjabSeeder::class,
            DokterSeeder::class,
            PoliklinikSeeder::class,
            PasienSeeder::class,
            RegPeriksaSeeder::class,
            ResumeMedisSeeder::class,
        ]);
    }
}
