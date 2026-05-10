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
        // Get some registrations that are 'Sudah' or 'Belum' to simulate completion
        $regs = RegPeriksa::limit(50)->get();

        foreach ($regs as $reg) {
            if ($faker->boolean(70)) { // 70% chance to have a resume
                ResumeMedis::create([
                    'no_rawat' => $reg->no_rawat,
                    'tgl_masuk' => $reg->tgl_registrasi,
                    'tgl_keluar' => $reg->tgl_registrasi,
                    'kd_dokter' => $reg->kd_dokter,
                    'keluhan' => $faker->sentence(),
                    'pemeriksaan_fisik' => 'Tensi: 120/80, Nadi: 80, Suhu: 36.5',
                    'diagnosa_masuk' => $faker->word(),
                    'diagnosa_utama' => $faker->word(),
                    'diagnosa_sekunder' => $faker->word(),
                    'tindakan_prosedur' => 'Pemberian obat jalan',
                    'terapi_pulang' => 'Amoxicillin 3x1, Paracetamol 3x1',
                    'cara_keluar' => 'Sembuh',
                    'ringkasan_riwayat' => $faker->paragraph(),
                    'hasil_penunjang' => 'Laboratorium normal',
                    'kondisi_pulang' => 'Baik',
                ]);
            }
        }
    }
}
