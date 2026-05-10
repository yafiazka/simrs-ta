<?php

namespace App\Filament\Resources\RegPeriksaResource\Pages;

use App\Filament\Resources\RegPeriksaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegPeriksa extends EditRecord
{
    protected static string $resource = RegPeriksaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::class::make(),
        ];
    }
}
