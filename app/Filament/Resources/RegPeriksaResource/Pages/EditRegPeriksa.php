<?php

namespace App\Filament\Resources\RegPeriksaResource\Pages;

use App\Filament\Resources\RegPeriksaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegPeriksa extends EditRecord
{
    protected static string $resource = RegPeriksaResource::class;

    protected function resolveRecord($key): \Illuminate\Database\Eloquent\Model
    {
        $key = str_replace('-', '/', $key);
        return parent::resolveRecord($key);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Pendaftaran Diperbarui')
            ->body('Perubahan data pendaftaran klinis telah berhasil disimpan.');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
