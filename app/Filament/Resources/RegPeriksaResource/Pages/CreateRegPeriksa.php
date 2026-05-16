<?php

namespace App\Filament\Resources\RegPeriksaResource\Pages;

use App\Filament\Resources\RegPeriksaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegPeriksa extends CreateRecord
{
    protected static string $resource = RegPeriksaResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Pendaftaran Berhasil')
            ->body('Data pendaftaran klinis telah berhasil disimpan ke dalam sistem.');
    }
}
