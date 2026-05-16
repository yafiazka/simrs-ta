<?php

namespace App\Filament\Resources\PoliklinikResource\Pages;

use App\Filament\Resources\PoliklinikResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePoliklinik extends CreateRecord
{
    protected static string $resource = PoliklinikResource::class;

    protected static bool $canCreateAnother = false;
}
