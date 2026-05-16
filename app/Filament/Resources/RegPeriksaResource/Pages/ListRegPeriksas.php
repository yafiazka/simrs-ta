<?php

namespace App\Filament\Resources\RegPeriksaResource\Pages;

use App\Filament\Resources\RegPeriksaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegPeriksas extends ListRecords
{
    protected static string $resource = RegPeriksaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
