<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    /**
     * Layout 2 kolom: StatsOverview (full) → Chart Poli | Chart Penyakit → Tabel Poli (full)
     */
    public function getColumns(): int | string | array
    {
        return [
            'md' => 1,
            'lg' => 2,
            'xl' => 2,
        ];
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            \Filament\Forms\Components\Grid::make()
                ->schema([
                Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->default(now()->format('m'))
                    ->native(false)
                    ->required(),
                Select::make('tahun')
                    ->label('Tahun')
                    ->options(array_combine(
                        range(now()->year - 5, now()->year),
                        range(now()->year - 5, now()->year)
                    ))
                    ->default(now()->year)
                    ->native(false)
                    ->required()
            ])->columns([
                'default' => 1,
                'sm' => 2,
                'md' => 4,
                'lg' => 6,
            ]),
        ]);
    }
}
