<?php

namespace App\Filament\Resources\ResumeMedisResource\Pages;

use App\Filament\Resources\ResumeMedisResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResumeMedis extends CreateRecord
{
    protected static string $resource = ResumeMedisResource::class;

    protected static bool $canCreateAnother = false;

    public function mount(): void
    {
        parent::mount();

        if ($no_rawat = request()->query('no_rawat')) {
            $data = ['no_rawat' => $no_rawat];
            
            $reg = \App\Models\RegPeriksa::with('pasien')->find($no_rawat);
            if ($reg) {
                $data['tgl_masuk'] = $reg->tgl_registrasi ? $reg->tgl_registrasi->format('Y-m-d') : null;
                $data['kd_dokter'] = $reg->kd_dokter;
                
                if ($reg->pasien) {
                    $data['nm_pasien'] = $reg->pasien->nm_pasien;
                    $data['jk_pasien'] = $reg->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan';
                    $data['tgl_lahir_pasien'] = $reg->pasien->tgl_lahir;
                    $data['alamat_pasien'] = $reg->pasien->alamat;
                }
            }

            $this->form->fill($data);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Resume Medis Berhasil')
            ->body('Data resume medis telah berhasil disimpan.');
    }
}
