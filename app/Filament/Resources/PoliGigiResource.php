<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoliGigiResource\Pages;
use App\Models\RegPeriksa;
use App\Models\Penyakit;
use App\Models\ResumeMedis;
use App\Models\ResepObat;
use App\Models\Poliklinik;
use App\Models\Penjab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class PoliGigiResource extends Resource
{
    protected static ?string $model = RegPeriksa::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Pelayanan Klinis';
    protected static ?string $pluralLabel = 'Poli Gigi';
    protected static ?string $navigationLabel = 'Poli Gigi';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('kd_poli', 'GIGI')
            ->whereIn('stts', ['Menunggu', 'Diperiksa', 'Belum', 'Selesai']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_reg')
                    ->label('No. Antrean')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_rawat')
                    ->label('No. Rawat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasien.nm_pasien')
                    ->label('Nama Pasien')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasien.jk')
                    ->label('JK')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'info' : 'danger'),
                Tables\Columns\TextColumn::make('umurdaftar')
                    ->label('Umur')
                    ->state(fn (RegPeriksa $record) => "{$record->umurdaftar} {$record->sttsumur}"),
                Tables\Columns\TextColumn::make('diagnosa_awal')
                    ->label('Keluhan Awal')
                    ->limit(50),
                Tables\Columns\TextColumn::make('penjab.png_jawab')
                    ->label('Cara Bayar'),
                Tables\Columns\TextColumn::make('stts')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu', 'Belum' => 'gray',
                        'Diperiksa' => 'warning',
                        'Selesai' => 'success',
                        default => 'danger',
                    })
                    ->state(fn (RegPeriksa $record) => match ($record->stts) {
                        'Belum', 'Menunggu' => 'Menunggu',
                        'Diperiksa' => 'Diperiksa',
                        'Selesai' => 'Selesai',
                        default => $record->stts,
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_awal')
                            ->label('Dari Tanggal')
                            ->default(now()->toDateString()),
                        Forms\Components\DatePicker::make('tanggal_akhir')
                            ->label('Sampai Tanggal')
                            ->default(now()->toDateString()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_awal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tgl_registrasi', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_akhir'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tgl_registrasi', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('terima_pasien')
                    ->label(fn (RegPeriksa $record) => $record->stts === 'Selesai' ? 'Edit Pemeriksaan' : 'Terima Pasien')
                    ->color(fn (RegPeriksa $record) => $record->stts === 'Selesai' ? 'warning' : 'success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (RegPeriksa $record) => in_array($record->stts, ['Menunggu', 'Belum', 'Diperiksa', 'Selesai']))
                    ->url(fn (RegPeriksa $record) => "/admin/terima-pasien/" . str_replace('/', '-', $record->no_rawat)),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([])
            ->defaultSort('tgl_registrasi', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoliGigis::route('/'),
        ];
    }
}
