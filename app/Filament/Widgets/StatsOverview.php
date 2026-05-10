<?php

namespace App\Filament\Widgets;

use App\Models\Pasien;
use App\Models\RegPeriksa;
use App\Models\ResumeMedis;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pasien', Pasien::count())
                ->description('Total pasien terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Registrasi Hari Ini', RegPeriksa::whereDate('tgl_registrasi', now())->count())
                ->description('Pasien mendaftar hari ini')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('info'),
            Stat::make('Resume Selesai', ResumeMedis::count())
                ->description('Total resume medis terisi')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
        ];
    }
}
