<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasien;
use Faker\Factory as Faker;

class PasienSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $jk = ['L', 'P'];
        $golDarah = ['A', 'B', 'O', 'AB'];
        $sttsNikah = ['BELUM MENIKAH', 'MENIKAH', 'JANDA', 'DUDA'];
        $pnd = ['SD', 'SMP', 'SMA', 'S1', 'D3'];
        $keluarga = ['AYAH', 'IBU', 'ISTRI', 'SUAMI', 'SAUDARA', 'ANAK', 'DIRI SENDIRI'];
        $kdPj = ['BPJ', 'UMU', 'PRU', 'ALL'];

        for ($i = 1; $i <= 50; $i++) {
            $currentJk = $faker->randomElement($jk);
            Pasien::create([
                'no_rkm_medis' => str_pad($i, 6, '0', STR_PAD_LEFT),
                'nm_pasien' => $faker->name($currentJk == 'L' ? 'male' : 'female'),
                'no_ktp' => $faker->nik(),
                'jk' => $currentJk,
                'tmp_lahir' => $faker->city(),
                'tgl_lahir' => $faker->date('Y-m-d', '2010-01-01'),
                'nm_ibu' => $faker->name('female'),
                'alamat' => $faker->address(),
                'gol_darah' => $faker->randomElement($golDarah),
                'pekerjaan' => $faker->jobTitle(),
                'stts_nikah' => $faker->randomElement($sttsNikah),
                'agama' => 'Islam',
                'tgl_daftar' => now(),
                'no_tlp' => $faker->phoneNumber(),
                'umur' => $faker->numberBetween(1, 80) . ' Th',
                'pnd' => $faker->randomElement($pnd),
                'keluarga' => $faker->randomElement($keluarga),
                'namakeluarga' => $faker->name(),
                'kd_pj' => $faker->randomElement($kdPj),
                'kabupaten' => 'Aceh Tengah',
                'kecamatan' => 'Kute Panang',
                'kelurahan' => $faker->randomElement(['Kute Panang', 'Blang Gele', 'Tawardi', 'Lukup Sabun', 'Panton Nangka']),
                'desa' => $faker->randomElement(['Kute Panang', 'Blang Gele', 'Tawardi', 'Lukup Sabun', 'Panton Nangka']),
                'no_peserta' => $faker->numerify('###########'),
                'pekerjaanpj' => $faker->jobTitle(),
                'alamatpj' => $faker->address(),
                'email' => $faker->email(),
            ]);
        }
    }
}
