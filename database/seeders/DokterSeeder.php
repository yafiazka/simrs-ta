<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dokter;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kd_dokter' => 'D001', 'nm_dokter' => 'dr. Ahmad Spesialis Dalam', 'spesialis' => 'Spesialis Penyakit Dalam', 'no_telp' => '0811111111', 'status' => '1'],
            ['kd_dokter' => 'D002', 'nm_dokter' => 'dr. Siti Spesialis Anak', 'spesialis' => 'Spesialis Anak', 'no_telp' => '0822222222', 'status' => '1'],
            ['kd_dokter' => 'D003', 'nm_dokter' => 'dr. Budi Spesialis Bedah', 'spesialis' => 'Spesialis Bedah', 'no_telp' => '0833333333', 'status' => '1'],
            ['kd_dokter' => 'D004', 'nm_dokter' => 'dr. Ani Spesialis Kandungan', 'spesialis' => 'Spesialis Kandungan', 'no_telp' => '0844444444', 'status' => '1'],
            ['kd_dokter' => 'D005', 'nm_dokter' => 'dr. Iwan Spesialis Jantung', 'spesialis' => 'Spesialis Jantung', 'no_telp' => '0855555555', 'status' => '1'],
        ];

        foreach ($data as $item) {
            Dokter::updateOrCreate(['kd_dokter' => $item['kd_dokter']], $item);
        }
    }
}
