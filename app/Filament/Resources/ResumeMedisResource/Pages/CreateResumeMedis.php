<?php

namespace App\Filament\Resources\ResumeMedisResource\Pages;

use App\Filament\Resources\ResumeMedisResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResumeMedis extends CreateRecord
{
    protected static string $resource = ResumeMedisResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If no_rawat is passed in URL
        if (request()->has('no_rawat')) {
            $data['no_rawat'] = request()->query('no_rawat');
        }
        
        return $data;
    }
}
