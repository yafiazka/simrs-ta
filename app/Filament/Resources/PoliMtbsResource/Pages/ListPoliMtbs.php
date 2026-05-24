<?php

namespace App\Filament\Resources\PoliMtbsResource\Pages;

use App\Filament\Resources\PoliMtbsResource;
use Filament\Resources\Pages\ListRecords;

class ListPoliMtbs extends ListRecords
{
    protected static string $resource = PoliMtbsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
