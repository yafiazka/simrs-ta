<?php

namespace App\Filament\Widgets;

use App\Models\Poliklinik;
use App\Models\RegPeriksa;
use Filament\Widgets\ChartWidget;

class PoliPasienChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Pasien per Poliklinik';

    protected function getData(): array
    {
        $data = RegPeriksa::join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->selectRaw('poliklinik.nm_poli, count(*) as total')
            ->groupBy('poliklinik.nm_poli')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pasien',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                ],
            ],
            'labels' => $data->pluck('nm_poli')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
