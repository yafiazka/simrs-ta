<?php

namespace App\Filament\Resources\PoliKiaResource\Pages;

use App\Filament\Resources\PoliKiaResource;
use Filament\Resources\Pages\ListRecords;

class ListPoliKias extends ListRecords
{
    protected static string $resource = PoliKiaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
