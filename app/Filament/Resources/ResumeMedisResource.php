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
                Forms\Components\Section::make('Biodata Pasien')
                    ->description('Informasi otomatis berdasarkan No. Rawat')
                    ->schema([
                        Forms\Components\TextInput::make('nm_pasien')
                            ->label('Nama Pasien')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Pilih No. Rawat terlebih dahulu'),
                        Forms\Components\TextInput::make('jk_pasien')
                            ->label('Jenis Kelamin')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('tgl_lahir_pasien')
                            ->label('Tanggal Lahir')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('alamat_pasien')
                            ->label('Alamat')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2)->collapsed(),

                Forms\Components\Section::make('Informasi Kunjungan')
                    ->schema([
                        Forms\Components\Select::make('no_rawat')
                            ->relationship('regPeriksa', 'no_rawat')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (!$state) return;
                                
                                $reg = \App\Models\RegPeriksa::with('pasien')->find($state);
                                if ($reg) {
                                    $set('tgl_masuk', $reg->tgl_registrasi ? $reg->tgl_registrasi->format('Y-m-d') : null);
                                    $set('kd_dokter', $reg->kd_dokter);
                                    
                                    // Set Biodata
                                    if ($reg->pasien) {
                                        $set('nm_pasien', $reg->pasien->nm_pasien);
                                        $set('jk_pasien', $reg->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan');
                                        $set('tgl_lahir_pasien', $reg->pasien->tgl_lahir);
                                        $set('alamat_pasien', $reg->pasien->alamat);
                                    }
                                }
                            })
                            ->afterStateHydrated(function ($state, Forms\Set $set) {
                                if (!$state) return;
                                
                                $reg = \App\Models\RegPeriksa::with('pasien')->find($state);
                                if ($reg) {
                                    $set('tgl_masuk', $reg->tgl_registrasi ? $reg->tgl_registrasi->format('Y-m-d') : null);
                                    $set('kd_dokter', $reg->kd_dokter);
                                    
                                    if ($reg->pasien) {
                                        $set('nm_pasien', $reg->pasien->nm_pasien);
                                        $set('jk_pasien', $reg->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan');
                                        $set('tgl_lahir_pasien', $reg->pasien->tgl_lahir);
                                        $set('alamat_pasien', $reg->pasien->alamat);
                                    }
                                }
                            })
                            ->unique(null, null, fn ($record) => $record)
                            ->required()
                            ->label('No. Rekam Medis / Rawat')
                            ->disabled(fn () => request()->has('no_rawat'))
                            ->dehydrated(),
                        Forms\Components\DatePicker::make('tgl_masuk')
                            ->label('Tanggal Masuk')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Checkbox::make('pulangkan_pasien')
                            ->label('Pulangkan Pasien')
                            ->live()
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($state, Forms\Set $set, $record) {
                                if ($record && $record->tgl_keluar) {
                                    $set('pulangkan_pasien', true);
                                }
                            }),
                        Forms\Components\DatePicker::make('tgl_keluar')
                            ->label('Tanggal Keluar')
                            ->visible(fn (Forms\Get $get) => $get('pulangkan_pasien'))
                            ->required(fn (Forms\Get $get) => $get('pulangkan_pasien')),
                        Forms\Components\Select::make('kd_dokter')
                            ->relationship('dokter', 'nm_dokter')
                            ->searchable()
                            ->preload()
                            ->label('Nama Dokter')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('cara_keluar')
                            ->label('Cara Keluar Rumah Sakit')
                            ->visible(fn (Forms\Get $get) => $get('pulangkan_pasien'))
                            ->required(fn (Forms\Get $get) => $get('pulangkan_pasien')),
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
                            ->label('Rencana Tindak Lanjut'),
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
                Tables\Columns\TextColumn::make('tgl_keluar')
                    ->label('Tgl. Keluar')
                    ->formatStateUsing(fn ($state) => $state != null ? \Carbon\Carbon::parse($state)->format('M d, Y') : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('diagnosa_utama')->limit(30)->label('Diagnosa'),
                Tables\Columns\TextColumn::make('regPeriksa.stts')
                    ->badge()
                    ->color(fn (?string $state): string => [
                        'Sudah' => 'success',
                        'Selesai' => 'success',
                        'Belum' => 'gray',
                        'Menunggu' => 'gray',
                        'Berkas Diterima' => 'gray',
                        'Batal' => 'danger',
                        'Meninggal' => 'danger',
                        'Pulang Paksa' => 'danger',
                        'Dirujuk' => 'warning',
                        'Dirawat' => 'warning',
                    ][$state] ?? 'warning')
                    ->label('Status Resume Medis'),
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
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators[] = 'Keluar Dari ' . \Illuminate\Support\Carbon::parse($data['dari_tanggal'])->format('d M Y');
                        }
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators[] = 'Keluar Sampai ' . \Illuminate\Support\Carbon::parse($data['sampai_tanggal'])->format('d M Y');
                        }
                        return $indicators;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
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
