<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResumeMedisResource\Pages;
use App\Models\ResumeMedis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResumeMedisResource extends Resource
{
    protected static ?string $model = ResumeMedis::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Pelayanan Klinis';
    protected static ?string $pluralLabel = 'Resume Medis';
    protected static ?string $navigationLabel = 'Resume Medis';
    protected static ?int $navigationSort = 7;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['regPeriksa.pasien', 'dokter', 'diagnosaUtamaPenyakit']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Biodata Pasien')
                    ->schema([
                        Forms\Components\TextInput::make('no_rawat')
                            ->label('No. Rawat'),
                        Forms\Components\TextInput::make('no_rkm_medis')
                            ->label('No. Rekam Medis')
                            ->afterStateHydrated(fn ($component, $record) => $component->state(
                                ($record && $record->regPeriksa && $record->regPeriksa->pasien) ? $record->regPeriksa->pasien->no_rkm_medis : null
                            )),
                        Forms\Components\TextInput::make('nm_pasien')
                            ->label('Nama Pasien')
                            ->afterStateHydrated(fn ($component, $record) => $component->state(
                                ($record && $record->regPeriksa && $record->regPeriksa->pasien) ? $record->regPeriksa->pasien->nm_pasien : null
                            )),
                        Forms\Components\TextInput::make('jk')
                            ->label('Jenis Kelamin')
                            ->afterStateHydrated(fn ($component, $record) => $component->state(
                                ($record && $record->regPeriksa && $record->regPeriksa->pasien) 
                                    ? ($record->regPeriksa->pasien->jk === 'L' ? 'Laki-laki' : ($record->regPeriksa->pasien->jk === 'P' ? 'Perempuan' : '-')) 
                                    : '-'
                            )),
                        Forms\Components\TextInput::make('alamat')
                            ->label('Alamat')
                            ->afterStateHydrated(fn ($component, $record) => $component->state(
                                ($record && $record->regPeriksa && $record->regPeriksa->pasien) ? $record->regPeriksa->pasien->alamat : null
                            )),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Pemeriksaan')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('tensi')
                                    ->label('Tensi'),
                                Forms\Components\TextInput::make('nadi')
                                    ->suffix('x/menit')
                                    ->label('Nadi'),
                                Forms\Components\TextInput::make('suhu')
                                    ->suffix('°C')
                                    ->label('Suhu'),
                                Forms\Components\TextInput::make('spo2')
                                    ->suffix('%')
                                    ->label('SpO2'),
                                Forms\Components\TextInput::make('tb')
                                    ->suffix('cm')
                                    ->label('Tinggi Badan (TB)'),
                                Forms\Components\TextInput::make('bb')
                                    ->suffix('kg')
                                    ->label('Berat Badan (BB)'),
                                Forms\Components\TextInput::make('respirasi')
                                    ->suffix('x/menit')
                                    ->label('Respirasi'),
                                Forms\Components\TextInput::make('gcs')
                                    ->label('GCS'),
                                Forms\Components\TextInput::make('tgl_masuk')
                                    ->label('Tanggal Kunjungan')
                                    ->columnSpan(2)
                                    ->afterStateHydrated(fn ($component, $state) => $component->state(
                                        $state ? \Illuminate\Support\Carbon::parse($state)->format('d-m-Y') : null
                                    )),
                                Forms\Components\TextInput::make('tgl_keluar')
                                    ->label('Tanggal Pulang')
                                    ->columnSpan(2)
                                    ->afterStateHydrated(fn ($component, $state) => $component->state(
                                        $state ? \Illuminate\Support\Carbon::parse($state)->format('d-m-Y') : null
                                    )),
                                Forms\Components\TextInput::make('nm_dokter')
                                    ->label('Dokter DPJP')
                                    ->columnSpan(2)
                                    ->afterStateHydrated(fn ($component, $record) => $component->state(
                                        ($record && $record->dokter) ? $record->dokter->nm_dokter : null
                                    )),
                            ]),
                        Forms\Components\Textarea::make('keluhan')
                            ->label('Keluhan / Anamnesa Awal')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('diagnosa_utama')
                            ->label('Diagnosa Utama (ICD-10)')
                            ->afterStateHydrated(fn ($component, $record) => $component->state(
                                $record && $record->diagnosaUtamaPenyakit 
                                    ? '[' . $record->diagnosa_utama . '] ' . $record->diagnosaUtamaPenyakit->nm_penyakit 
                                    : ($record ? $record->diagnosa_utama : null)
                            )),
                        Forms\Components\TextInput::make('cara_keluar')
                            ->label('Cara Dipulangkan'),
                        Forms\Components\Textarea::make('instruksi')
                            ->label('Instruksi Medis')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Resep Obat')
                    ->schema([
                        Forms\Components\Repeater::make('resepObats')
                            ->relationship('resepObats')
                            ->label('Resep Obat')
                            ->schema([
                                Forms\Components\TextInput::make('nama_obat')
                                    ->label('Nama Obat'),
                                Forms\Components\TextInput::make('jumlah_obat')
                                    ->label('Jumlah'),
                                Forms\Components\TextInput::make('aturan_pakai')
                                    ->label('Aturan Pakai'),
                            ])
                            ->columns(3)
                            ->dehydrated(false)
                            ->disabled()
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_rawat')
                    ->label('No. Rawat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('regPeriksa.pasien.nm_pasien')
                    ->label('Nama Pasien')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_masuk')
                    ->label('Tgl Kunjungan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('diagnosaUtamaPenyakit.nm_penyakit')
                    ->label('Diagnosa Utama (ICD-10)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cara_keluar')
                    ->label('Tindak Lanjut')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Dirujuk ke RS' ? 'warning' : 'success'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tgl_masuk')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn($q) => $q->whereDate('tgl_masuk', '>=', $data['dari_tanggal']))
                            ->when($data['sampai_tanggal'], fn($q) => $q->whereDate('tgl_masuk', '<=', $data['sampai_tanggal']));
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail Medis')
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
                Tables\Actions\Action::make('print')
                    ->label('Cetak Resume')
                    ->color('success')
                    ->icon('heroicon-o-printer')
                    ->url(fn (ResumeMedis $record): string => route('resume-medis.print', ['id' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([])
            ->defaultSort('tgl_masuk', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResumeMedis::route('/'),
        ];
    }
}
