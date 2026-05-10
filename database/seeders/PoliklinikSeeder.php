<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poliklinik;

class PoliklinikSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kd_poli' => 'INT', 'nm_poli' => 'Poli Penyakit Dalam', 'status' => '1'],
            ['kd_poli' => 'OBG', 'nm_poli' => 'Poli Kandungan', 'status' => '1'],
            ['kd_poli' => 'ANA', 'nm_poli' => 'Poli Anak', 'status' => '1'],
            ['kd_poli' => 'BED', 'nm_poli' => 'Poli Bedah', 'status' => '1'],
            ['kd_poli' => 'MAT', 'nm_poli' => 'Poli Mata', 'status' => '1'],
            ['kd_poli' => 'THT', 'nm_poli' => 'Poli THT', 'status' => '1'],
            ['kd_poli' => 'SAR', 'nm_poli' => 'Poli Saraf', 'status' => '1'],
            ['kd_poli' => 'JIW', 'nm_poli' => 'Poli Jiwa', 'status' => '1'],
            ['kd_poli' => 'JAN', 'nm_poli' => 'Poli Jantung', 'status' => '1'],
            ['kd_poli' => 'PAR', 'nm_poli' => 'Poli Paru', 'status' => '1'],
        ];

        foreach ($data as $item) {
            Poliklinik::updateOrCreate(['kd_poli' => $item['kd_poli']], $item);
        }
    }
}
