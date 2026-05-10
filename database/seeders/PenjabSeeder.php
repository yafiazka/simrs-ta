<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjab;

class PenjabSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kd_pj' => 'BPJ', 'png_jawab' => 'BPJS Kesehatan', 'nama_perusahaan' => 'BPJS Kesehatan', 'alamat_asuransi' => 'Jl. Letjen Suprapto Kav. 20 No. 14', 'no_telp' => '1500400', 'attn' => 'Humas BPJS', 'status' => '1'],
            ['kd_pj' => 'UMU', 'png_jawab' => 'Umum/Mandiri', 'nama_perusahaan' => 'Pribadi', 'alamat_asuransi' => '-', 'no_telp' => '-', 'attn' => '-', 'status' => '1'],
            ['kd_pj' => 'PRU', 'png_jawab' => 'Prudential', 'nama_perusahaan' => 'PT Prudential Life Assurance', 'alamat_asuransi' => 'Prudential Tower, Jakarta', 'no_telp' => '021-1500085', 'attn' => 'Claim Dept', 'status' => '1'],
            ['kd_pj' => 'ALL', 'png_jawab' => 'Allianz', 'nama_perusahaan' => 'PT Asuransi Allianz Utama', 'alamat_asuransi' => 'Allianz Tower, Jakarta', 'no_telp' => '021-29268888', 'attn' => 'Medical Dept', 'status' => '1'],
        ];

        foreach ($data as $item) {
            Penjab::create($item);
        }
    }
}
