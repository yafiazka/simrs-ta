<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegPeriksa;
use App\Models\Pasien;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class RegPeriksaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $pasiens = Pasien::all();
        $polis = Poliklinik::all();
        $dokters = Dokter::all();
        $stts = ['Belum', 'Sudah', 'Batal', 'Pulang Paksa'];
        $sttsDaftar = ['Baru', 'Lama'];
        
        // Generate for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            $count = $faker->numberBetween(5, 15);

            for ($i = 1; $i <= $count; $i++) {
                $pasien = $pasiens->random();
                $poli = $polis->random();
                $dokter = $dokters->random();
                $noRawat = $date->format('Y/m/d') . '/' . str_pad($i, 6, '0', STR_PAD_LEFT);

                RegPeriksa::create([
                    'no_reg' => str_pad($i, 3, '0', STR_PAD_LEFT),
                    'no_rawat' => $noRawat,
                    'tgl_registrasi' => $date->toDateString(),
                    'jam_reg' => $faker->time(),
                    'kd_dokter' => $dokter->kd_dokter,
                    'no_rkm_medis' => $pasien->no_rkm_medis,
                    'kd_poli' => $poli->kd_poli,
                    'p_jawab' => $pasien->namakeluarga,
                    'almt_pj' => $pasien->alamatpj,
                    'hubunganpj' => $pasien->keluarga,
                    'biaya_reg' => 50000,
                    'stts' => $faker->randomElement($stts),
                    'stts_daftar' => $faker->randomElement($sttsDaftar),
                    'status_lanjut' => 'Ralan',
                    'kd_pj' => $pasien->kd_pj,
                    'umurdaftar' => (int)$pasien->umur,
                    'sttsumur' => 'Th',
                    'status_bayar' => 'Belum Bayar',
                    'status_poli' => $faker->randomElement($sttsDaftar),
                    'diagnosa_awal' => $faker->randomElement(['Demam tinggi', 'Batuk pilek', 'Sakit kepala', 'Nyeri perut', 'Diare', 'Pusing', 'Luka robek', 'Sesak nafas']),
                    'status_kunjungan' => $faker->randomElement(['Baru', 'Lama']),
                ]);
            }
        }
    }
}
