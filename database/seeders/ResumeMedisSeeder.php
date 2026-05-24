<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResumeMedis;
use App\Models\RegPeriksa;
use Faker\Factory as Faker;

class ResumeMedisSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        // Get all registrations to simulate completion
        $regs = RegPeriksa::all();

        foreach ($regs as $reg) {
            if ($faker->boolean(85)) { // 85% chance to have a resume
                // Set status to 'Sudah' when seeding a resume
                $reg->update(['stts' => 'Sudah']);

                ResumeMedis::create([
                    'no_rawat' => $reg->no_rawat,
                    'tgl_masuk' => $reg->tgl_registrasi,
                    'tgl_keluar' => $reg->tgl_registrasi,
                    'kd_dokter' => $reg->kd_dokter,
                    'keluhan' => $reg->diagnosa_awal ?? $faker->sentence(),
                    'pemeriksaan_fisik' => 'Tensi: 120/80, Nadi: 80, Suhu: 36.5',
                    'diagnosa_masuk' => $faker->word(),
                    'diagnosa_utama' => \App\Models\Penyakit::all()->random()->kd_penyakit ?? 'J06.9',
                    'diagnosa_sekunder' => $faker->word(),
                    'tindakan_prosedur' => 'Pemberian obat jalan',
                    'terapi_pulang' => 'Amoxicillin 3x1, Paracetamol 3x1',
                    'cara_keluar' => $faker->randomElement(['dipulangkan', 'dirujuk_rs']),
                    'ringkasan_riwayat' => $faker->paragraph(),
                    'hasil_penunjang' => 'Laboratorium normal',
                    'kondisi_pulang' => 'Baik',
                    'tensi' => $faker->randomElement(['120/80', '110/70', '130/80']),
                    'tb' => (string)$faker->numberBetween(150, 180),
                    'bb' => (string)$faker->numberBetween(50, 90),
                    'respirasi' => (string)$faker->numberBetween(16, 22),
                    'gcs' => 'E4V5M6',
                    'nadi' => (string)$faker->numberBetween(60, 100),
                    'suhu' => (string)$faker->randomElement(['36.2', '36.5', '36.8', '37.0']),
                    'spo2' => (string)$faker->numberBetween(95, 100),
                    'instruksi' => 'Minum obat secara teratur dan istirahat yang cukup.',
                ]);
            }
        }
    }
}
