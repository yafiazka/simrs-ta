<?php

namespace App\Filament\Resources\PoliUmumResource\Pages;

use App\Filament\Resources\PoliUmumResource;
use Filament\Resources\Pages\ListRecords;

class ListPoliUmums extends ListRecords
{
    protected static string $resource = PoliUmumResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
