<?php

namespace App\Filament\Widgets;

use App\Models\Poliklinik;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PoliPasienTable extends BaseWidget
{
    protected static ?string $heading = 'Jumlah Pasien per Poliklinik (Hari Ini)';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Poliklinik::query()
                    ->withCount(['regPeriksas' => function (Builder $query) {
                        $query->whereDate('tgl_registrasi', now());
                    }])
            )
            ->columns([
                Tables\Columns\TextColumn::make('nm_poli')
                    ->label('Nama Poliklinik'),
                Tables\Columns\TextColumn::make('reg_periksas_count')
                    ->label('Jumlah Pasien Hari Ini')
                    ->badge()
                    ->color('info'),
            ])
            ->paginated(false);
    }
}
