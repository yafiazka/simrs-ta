<?php

namespace App\Filament\Resources\ResumeMedisResource\Pages;

use App\Filament\Resources\ResumeMedisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResumeMedis extends EditRecord
{
    protected static string $resource = ResumeMedisResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return \Filament\Notifications\Notification::make()
            ->success()
            ->title('Resume Medis Diperbarui')
            ->body('Perubahan data resume medis telah berhasil disimpan.');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
