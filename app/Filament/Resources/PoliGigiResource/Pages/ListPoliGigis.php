<?php

namespace App\Filament\Resources\PoliGigiResource\Pages;

use App\Filament\Resources\PoliGigiResource;
use Filament\Resources\Pages\ListRecords;

class ListPoliGigis extends ListRecords
{
    protected static string $resource = PoliGigiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
