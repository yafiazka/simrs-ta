<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dokter;

class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kd_dokter' => 'D001', 'nm_dokter' => 'dr. Ahmad Spesialis Dalam', 'spesialis' => 'Spesialis Penyakit Dalam', 'no_telp' => '0811111111', 'sip' => 'SIP/D001/2026/01', 'nik' => '3201011212880001', 'status' => '1'],
            ['kd_dokter' => 'D002', 'nm_dokter' => 'dr. Siti Spesialis Anak', 'spesialis' => 'Spesialis Anak', 'no_telp' => '0822222222', 'sip' => 'SIP/D002/2026/02', 'nik' => '3201014505900002', 'status' => '1'],
            ['kd_dokter' => 'D003', 'nm_dokter' => 'dr. Budi Spesialis Bedah', 'spesialis' => 'Spesialis Bedah', 'no_telp' => '0833333333', 'sip' => 'SIP/D003/2026/03', 'nik' => '3201011003850003', 'status' => '1'],
            ['kd_dokter' => 'D004', 'nm_dokter' => 'dr. Ani Spesialis Kandungan', 'spesialis' => 'Spesialis Kandungan', 'no_telp' => '0844444444', 'sip' => 'SIP/D004/2026/04', 'nik' => '3201015607910004', 'status' => '1'],
            ['kd_dokter' => 'D005', 'nm_dokter' => 'dr. Iwan Spesialis Jantung', 'spesialis' => 'Spesialis Jantung', 'no_telp' => '0855555555', 'sip' => 'SIP/D005/2026/05', 'nik' => '3201012010870005', 'status' => '1'],
        ];

        foreach ($data as $item) {
            Dokter::updateOrCreate(['kd_dokter' => $item['kd_dokter']], $item);
        }
    }
}
