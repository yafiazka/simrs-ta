<?php

namespace App\Filament\Resources\ResumeMedisResource\Pages;

use App\Filament\Resources\ResumeMedisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResumeMedis extends ListRecords
{
    protected static string $resource = ResumeMedisResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
