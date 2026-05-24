<?php

namespace App\Filament\Widgets;

use App\Models\Pasien;
use App\Models\RegPeriksa;
use App\Models\ResumeMedis;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $bulan = $this->filters['bulan'] ?? now()->format('m');
        $tahun = $this->filters['tahun'] ?? now()->format('Y');

        $registrasiBulanIni = RegPeriksa::whereMonth('tgl_registrasi', $bulan)
            ->whereYear('tgl_registrasi', $tahun)
            ->count();

        $resumeBulanIni = ResumeMedis::whereMonth('tgl_masuk', $bulan)
            ->whereYear('tgl_masuk', $tahun)
            ->count();

        return [
            Stat::make('Total Pasien Terdaftar', Pasien::count())
                ->description('Kumulatif seluruh pasien di master data')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('success'),
            Stat::make('Kunjungan Poli Baru', $registrasiBulanIni)
                ->description('Registrasi kunjungan baru bulan terpilih')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info'),
            Stat::make('Pemeriksaan Selesai', $resumeBulanIni)
                ->description('Resume medis terselesaikan bulan terpilih')
                ->descriptionIcon('heroicon-m-check-circle')
                ->icon('heroicon-o-check-badge')
                ->color('primary'),
        ];
    }
}
