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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Biodata Pasien')
                    ->schema([
                        Forms\Components\TextInput::make('no_rawat')
                            ->label('No. Rawat'),
                        Forms\Components\TextInput::make('regPeriksa.pasien.no_rkm_medis')
                            ->label('No. Rekam Medis'),
                        Forms\Components\TextInput::make('regPeriksa.pasien.nm_pasien')
                            ->label('Nama Pasien'),
                        Forms\Components\TextInput::make('regPeriksa.pasien.jk')
                            ->label('Jenis Kelamin')
                            ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                        Forms\Components\TextInput::make('regPeriksa.pasien.alamat')
                            ->label('Alamat'),
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
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('regPeriksa.dokter.nm_dokter')
                                    ->label('Dokter DPJP')
                                    ->columnSpan(2),
                            ]),
                        Forms\Components\Textarea::make('keluhan')
                            ->label('Keluhan / Anamnesa Awal')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('diagnosaUtamaPenyakit.nm_penyakit')
                            ->label('Diagnosa Utama (ICD-10)'),
                        Forms\Components\TextInput::make('cara_keluar')
                            ->formatStateUsing(fn ($state) => $state === 'dirujuk_rs' ? 'Dirujuk ke RS' : 'Dipulangkan')
                            ->label('Cara Dipulangkan'),
                        Forms\Components\Textarea::make('instruksi')
                            ->label('Instruksi Medis')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Resep Obat')
                    ->schema([
                        Forms\Components\Repeater::make('resepObats')
                            ->relationship('resepObats')
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
                    ->color(fn (string $state): string => $state === 'dirujuk_rs' ? 'warning' : 'success')
                    ->formatStateUsing(fn (string $state): string => $state === 'dirujuk_rs' ? 'Dirujuk' : 'Pulang'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tgl_masuk')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
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
