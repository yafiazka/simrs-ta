<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kamar;

class KamarSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kd_kamar' => 'K001', 'nm_kamar' => 'Mawar 1', 'kelas' => 'Kelas 1', 'trf_kamar' => 500000, 'stts' => 'KOSONG'],
            ['kd_kamar' => 'K002', 'nm_kamar' => 'Mawar 2', 'kelas' => 'Kelas 1', 'trf_kamar' => 500000, 'stts' => 'ISI'],
            ['kd_kamar' => 'K003', 'nm_kamar' => 'Melati 1', 'kelas' => 'Kelas 2', 'trf_kamar' => 300000, 'stts' => 'KOSONG'],
            ['kd_kamar' => 'K004', 'nm_kamar' => 'Melati 2', 'kelas' => 'Kelas 2', 'trf_kamar' => 300000, 'stts' => 'KOSONG'],
            ['kd_kamar' => 'K005', 'nm_kamar' => 'Anggrek 1', 'kelas' => 'VIP', 'trf_kamar' => 1000000, 'stts' => 'KOSONG'],
            ['kd_kamar' => 'K006', 'nm_kamar' => 'Anggrek 2', 'kelas' => 'VIP', 'trf_kamar' => 1000000, 'stts' => 'ISI'],
            ['kd_kamar' => 'K007', 'nm_kamar' => 'Lily 1', 'kelas' => 'Kelas 3', 'trf_kamar' => 150000, 'stts' => 'KOSONG'],
        ];

        foreach ($data as $item) {
            Kamar::updateOrCreate(['kd_kamar' => $item['kd_kamar']], $item);
        }
    }
}
