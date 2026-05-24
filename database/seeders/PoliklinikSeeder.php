<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poliklinik;

class PoliklinikSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kd_poli' => 'UMUM', 'nm_poli' => 'Poli Umum', 'status' => '1'],
            ['kd_poli' => 'GIGI', 'nm_poli' => 'Poli Gigi', 'status' => '1'],
            ['kd_poli' => 'KIA', 'nm_poli' => 'Poli KIA (Kesehatan Ibu dan Anak)', 'status' => '1'],
            ['kd_poli' => 'MTBS', 'nm_poli' => 'Poli MTBS (Manajemen Terpadu Balita Sakit)', 'status' => '1'],
        ];

        foreach ($data as $item) {
            Poliklinik::updateOrCreate(['kd_poli' => $item['kd_poli']], $item);
        }
    }
}
