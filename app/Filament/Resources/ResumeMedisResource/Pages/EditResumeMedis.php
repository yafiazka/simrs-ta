<?php

namespace App\Filament\Resources\ResumeMedisResource\Pages;

use App\Filament\Resources\ResumeMedisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResumeMedis extends EditRecord
{
    protected static string $resource = ResumeMedisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::class::make(),
        ];
    }
}
