<?php

namespace App\Filament\Widgets;

use App\Models\Poliklinik;
use App\Models\RegPeriksa;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PoliPasienChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = '10 Poli dengan Pasien Tertinggi';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $bulan = $this->filters['bulan'] ?? now()->format('m');
        $tahun = $this->filters['tahun'] ?? now()->format('Y');

        $data = RegPeriksa::join('poliklinik', 'reg_periksa.kd_poli', '=', 'poliklinik.kd_poli')
            ->selectRaw('poliklinik.nm_poli, count(*) as total')
            ->whereMonth('reg_periksa.tgl_registrasi', $bulan)
            ->whereYear('reg_periksa.tgl_registrasi', $tahun)
            ->groupBy('poliklinik.nm_poli')
            ->orderByDesc('total')
            ->limit(10)
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
