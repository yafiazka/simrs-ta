<?php

namespace App\Filament\Widgets;

use App\Models\Poliklinik;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PoliPasienTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Jumlah Pasien per Poliklinik (Bulan/Tahun Terpilih)';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Poliklinik::query()
                    ->withCount(['regPeriksas' => function (Builder $query) {
                        $bulan = $this->filters['bulan'] ?? now()->format('m');
                        $tahun = $this->filters['tahun'] ?? now()->format('Y');
                        $query->whereMonth('tgl_registrasi', $bulan)
                              ->whereYear('tgl_registrasi', $tahun);
                    }])
            )
            ->columns([
                Tables\Columns\TextColumn::make('nm_poli')
                    ->label('Nama Poliklinik'),
                Tables\Columns\TextColumn::make('reg_periksas_count')
                    ->label('Jumlah Pasien')
                    ->badge()
                    ->color('info'),
            ])
            ->paginated(false);
    }
}
