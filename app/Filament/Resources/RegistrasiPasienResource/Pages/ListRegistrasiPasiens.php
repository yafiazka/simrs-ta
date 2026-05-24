<?php

namespace App\Filament\Resources\RegistrasiPasienResource\Pages;

use App\Filament\Resources\RegistrasiPasienResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrasiPasiens extends ListRecords
{
    protected static string $resource = RegistrasiPasienResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
