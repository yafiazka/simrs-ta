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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kunjungan')
                    ->schema([
                        Forms\Components\Select::make('no_rawat')
                            ->relationship('regPeriksa', 'no_rawat')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('No. Rekam Medis / Rawat'),
                        Forms\Components\DatePicker::make('tgl_masuk')
                            ->label('Tanggal Masuk'),
                        Forms\Components\DatePicker::make('tgl_keluar')
                            ->label('Tanggal Keluar'),
                        Forms\Components\TextInput::make('kd_dokter')
                            ->label('Nama Dokter'),
                        Forms\Components\TextInput::make('cara_keluar')
                            ->label('Cara Keluar Rumah Sakit'),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Medis')
                    ->schema([
                        Forms\Components\Textarea::make('keluhan')
                            ->label('Ringkasan Riwayat Penyakit/Anamnesa'),
                        Forms\Components\Textarea::make('pemeriksaan_fisik')
                            ->label('Pemeriksaan Fisik'),
                        Forms\Components\Textarea::make('diagnosa_masuk')
                            ->label('Diagnosa Masuk'),
                        Forms\Components\Textarea::make('diagnosa_utama')
                            ->label('Diagnosa Utama'),
                        Forms\Components\Textarea::make('diagnosa_sekunder')
                            ->label('Diagnosa Sekunder'),
                        Forms\Components\Textarea::make('tindakan_prosedur')
                            ->label('Tindakan/Prosedur Operasi'),
                        Forms\Components\Textarea::make('terapi_pulang')
                            ->label('Terapi Pulang'),
                        Forms\Components\Textarea::make('alergi_obat')
                            ->label('Alergi Obat'),
                        Forms\Components\Textarea::make('kondisi_pulang')
                            ->label('Kondisi Pasien Saat Pulang'),
                        Forms\Components\Textarea::make('rencana_lanjut')
                            ->label('Rencana Tidak Lanjut'),
                        Forms\Components\Textarea::make('hasil_penunjang')
                            ->label('Hasil Pemeriksaan Penunjang'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_rawat')->label('No. Rawat')->searchable(),
                Tables\Columns\TextColumn::make('regPeriksa.pasien.nm_pasien')->label('Pasien')->searchable(),
                Tables\Columns\TextColumn::make('tgl_keluar')->date()->label('Tgl. Keluar')->sortable(),
                Tables\Columns\TextColumn::make('diagnosa_utama')->limit(30)->label('Diagnosa'),
                Tables\Columns\TextColumn::make('cara_keluar')->label('Status'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tgl_keluar')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn($q) => $q->whereDate('tgl_keluar', '>=', $data['dari_tanggal']))
                            ->when($data['sampai_tanggal'], fn($q) => $q->whereDate('tgl_keluar', '<=', $data['sampai_tanggal']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResumeMedis::route('/'),
            'create' => Pages\CreateResumeMedis::route('/create'),
            'edit' => Pages\EditResumeMedis::route('/{record}/edit'),
        ];
    }
}
