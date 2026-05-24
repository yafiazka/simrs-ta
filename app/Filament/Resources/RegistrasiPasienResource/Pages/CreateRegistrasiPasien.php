<?php

namespace App\Filament\Resources\RegistrasiPasienResource\Pages;

use App\Filament\Resources\RegistrasiPasienResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistrasiPasien extends CreateRecord
{
    protected static string $resource = RegistrasiPasienResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // 1. Separate patient data and registration data
        $regData = [
            'kd_poli' => $data['kd_poli'] ?? null,
            'kd_dokter' => $data['kd_dokter'] ?? null,
            'kd_pj' => $data['kd_pj_reg'] ?? null,
            'diagnosa_awal' => $data['diagnosa_awal'] ?? null,
            'status_kunjungan' => $data['status_kunjungan'] ?? 'Baru',
        ];

        // Remove registration fields from patient data
        unset($data['kd_poli'], $data['kd_dokter'], $data['kd_pj_reg'], $data['diagnosa_awal'], $data['status_kunjungan'], $data['cari_pasien_lama']);

        // 2. Save / Update Patient
        $noRkmMedis = $data['no_rkm_medis'] ?? null;
        if ($noRkmMedis && \App\Models\Pasien::where('no_rkm_medis', $noRkmMedis)->exists()) {
            $pasien = \App\Models\Pasien::find($noRkmMedis);
            $pasien->update($data);
        } else {
            $pasien = \App\Models\Pasien::create($data);
        }

        // 3. Create Visit Registration (RegPeriksa)
        $regPeriksa = new \App\Models\RegPeriksa();
        $regPeriksa->no_rawat = \App\Models\RegPeriksa::generateNoRawat();
        $regPeriksa->no_rkm_medis = $pasien->no_rkm_medis;
        $regPeriksa->kd_poli = $regData['kd_poli'];
        $regPeriksa->kd_dokter = $regData['kd_dokter'];
        $regPeriksa->kd_pj = $regData['kd_pj'];
        $regPeriksa->diagnosa_awal = $regData['diagnosa_awal'];
        $regPeriksa->status_kunjungan = $regData['status_kunjungan'];
        $regPeriksa->tgl_registrasi = now()->toDateString();
        $regPeriksa->stts = 'Menunggu'; // Initial status
        $regPeriksa->stts_daftar = $regData['status_kunjungan'];
        $regPeriksa->status_lanjut = 'Ralan';
        $regPeriksa->biaya_reg = 0; // Default registration fee
        $regPeriksa->save();

        return $pasien;
    }
}
