<?php

namespace App\Filament\Resources\RegistrasiPasienResource\Pages;

use App\Filament\Resources\RegistrasiPasienResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrasiPasien extends EditRecord
{
    protected static string $resource = RegistrasiPasienResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $latestReg = \App\Models\RegPeriksa::where('no_rkm_medis', $this->record->no_rkm_medis)
            ->orderBy('tgl_registrasi', 'desc')
            ->orderBy('no_rawat', 'desc')
            ->first();

        if ($latestReg) {
            $data['kd_poli'] = $latestReg->kd_poli;
            $data['kd_dokter'] = $latestReg->kd_dokter;
            $data['kd_pj_reg'] = $latestReg->kd_pj;
            $data['diagnosa_awal'] = $latestReg->diagnosa_awal;
            $data['status_kunjungan'] = $latestReg->status_kunjungan;
        } else {
            $data['kd_poli'] = null;
            $data['kd_dokter'] = null;
            $data['kd_pj_reg'] = null;
            $data['diagnosa_awal'] = null;
            $data['status_kunjungan'] = 'Baru';
        }

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // 1. Separate patient data and registration data
        $regData = [
            'kd_poli' => $data['kd_poli'] ?? null,
            'kd_dokter' => $data['kd_dokter'] ?? null,
            'kd_pj' => $data['kd_pj_reg'] ?? null,
            'diagnosa_awal' => $data['diagnosa_awal'] ?? null,
            'status_kunjungan' => $data['status_kunjungan'] ?? 'Baru',
        ];

        // Remove registration fields from patient data to avoid database errors on the pasien table
        unset($data['kd_poli'], $data['kd_dokter'], $data['kd_pj_reg'], $data['diagnosa_awal'], $data['status_kunjungan']);

        // 2. Update Patient
        $record->update($data);

        // 3. Update or Create latest Visit Registration (RegPeriksa)
        $latestReg = \App\Models\RegPeriksa::where('no_rkm_medis', $record->no_rkm_medis)
            ->orderBy('tgl_registrasi', 'desc')
            ->orderBy('no_rawat', 'desc')
            ->first();

        if ($latestReg) {
            $latestReg->update([
                'kd_poli' => $regData['kd_poli'],
                'kd_dokter' => $regData['kd_dokter'],
                'kd_pj' => $regData['kd_pj'],
                'diagnosa_awal' => $regData['diagnosa_awal'],
                'status_kunjungan' => $regData['status_kunjungan'],
                'stts_daftar' => $regData['status_kunjungan'],
            ]);
        } else {
            // Create a new visit if somehow none exists
            $regPeriksa = new \App\Models\RegPeriksa();
            $regPeriksa->no_rawat = \App\Models\RegPeriksa::generateNoRawat();
            $regPeriksa->no_rkm_medis = $record->no_rkm_medis;
            $regPeriksa->kd_poli = $regData['kd_poli'];
            $regPeriksa->kd_dokter = $regData['kd_dokter'];
            $regPeriksa->kd_pj = $regData['kd_pj'];
            $regPeriksa->diagnosa_awal = $regData['diagnosa_awal'];
            $regPeriksa->status_kunjungan = $regData['status_kunjungan'];
            $regPeriksa->tgl_registrasi = now()->toDateString();
            $regPeriksa->stts = 'Menunggu';
            $regPeriksa->stts_daftar = $regData['status_kunjungan'];
            $regPeriksa->status_lanjut = 'Ralan';
            $regPeriksa->biaya_reg = 0;
            $regPeriksa->save();
        }

        return $record;
    }
}
