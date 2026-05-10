<?php

namespace App\Filament\Resources\ResumeMedisResource\Pages;

use App\Filament\Resources\ResumeMedisResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResumeMedis extends CreateRecord
{
    protected static string $resource = ResumeMedisResource::class;

    public function mount(): void
    {
        parent::mount();

        if ($no_rawat = request()->query('no_rawat')) {
            $this->form->fill([
                'no_rawat' => $no_rawat,
            ]);
        }
    }
}
