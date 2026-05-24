<?php

namespace App\Filament\Widgets;

use App\Models\ResumeMedis;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TenPenyakitChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = '10 Besar Penyakit Tertinggi (Bulan/Tahun Terpilih)';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $bulan = $this->filters['bulan'] ?? now()->format('m');
        $tahun = $this->filters['tahun'] ?? now()->format('Y');

        $data = ResumeMedis::query()
            ->selectRaw('resume_medis.diagnosa_utama as kode_penyakit, count(*) as total')
            ->whereMonth('resume_medis.tgl_masuk', $bulan)
            ->whereYear('resume_medis.tgl_masuk', $tahun)
            ->whereNotNull('resume_medis.diagnosa_utama')
            ->where('resume_medis.diagnosa_utama', '!=', '')
            ->groupBy('resume_medis.diagnosa_utama')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kasus',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#f43f5e', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', 
                        '#ec4899', '#14b8a6', '#06b6d4', '#f97316', '#6366f1'
                    ],
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $data->pluck('kode_penyakit')->toArray(),
        ];

    }

    protected function getType(): string
    {
        return 'bar';
    }
}
