<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegPeriksaResource\Pages;
use App\Models\RegPeriksa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegPeriksaResource extends Resource
{
    protected static ?string $model = RegPeriksa::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Pelayanan Klinis';
    protected static ?string $pluralLabel = 'Pendaftaran Klinis';
    protected static ?string $navigationLabel = 'Pendaftaran Klinis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Registrasi')
                    ->schema([
                        Forms\Components\TextInput::make('no_rawat')
                            ->default(fn () => RegPeriksa::generateNoRawat())
                            ->readOnly()
                            ->required()
                            ->label('No. Rawat'),
                        Forms\Components\Select::make('no_rkm_medis')
                            ->relationship('pasien', 'nm_pasien')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Pasien'),
                        Forms\Components\Select::make('kd_poli')
                            ->relationship('poliklinik', 'nm_poli')
                            ->required()
                            ->label('Poliklinik'),
                        Forms\Components\Select::make('kd_dokter')
                            ->relationship('dokter', 'nm_dokter')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Dokter'),
                        Forms\Components\DateTimePicker::make('tgl_registrasi')
                            ->default(now())
                            ->required()
                            ->label('Tgl. Registrasi'),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('kd_pj')
                            ->relationship('penjab', 'png_jawab')
                            ->required()
                            ->label('Cara Bayar'),
                        Forms\Components\Select::make('stts')
                            ->options([
                                'Belum' => 'Belum Periksa',
                                'Sudah' => 'Sudah Periksa',
                                'Batal' => 'Batal',
                                'Berkas Diterima' => 'Berkas Diterima',
                                'Dirujuk' => 'Dirujuk',
                                'Meninggal' => 'Meninggal',
                                'Dirawat' => 'Dirawat',
                                'Pulang Paksa' => 'Pulang Paksa',
                            ])
                            ->default('Belum')
                            ->required()
                            ->label('Status Periksa'),
                        Forms\Components\Select::make('stts_daftar')
                            ->options([
                                '-' => '-',
                                'Baru' => 'Baru',
                                'Lama' => 'Lama',
                            ])
                            ->default('-')
                            ->label('Status Daftar'),
                        Forms\Components\Select::make('status_lanjut')
                            ->options([
                                'Ralan' => 'Rawat Jalan',
                                'Ranap' => 'Rawat Inap',
                            ])
                            ->default('Ralan')
                            ->label('Status Lanjut'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_reg')->label('No. Reg'),
                Tables\Columns\TextColumn::make('no_rawat')->label('No. Rawat')->searchable(),
                Tables\Columns\TextColumn::make('pasien.nm_pasien')->label('Pasien')->searchable(),
                Tables\Columns\TextColumn::make('poliklinik.nm_poli')->label('Poli'),
                Tables\Columns\TextColumn::make('tgl_registrasi')->date()->label('Tgl. Daftar')->sortable(),
                Tables\Columns\TextColumn::make('stts')
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
                    ->label('Status'),
                Tables\Columns\TextColumn::make('penjab.png_jawab')->label('Bayar'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tgl_registrasi')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn($q) => $q->whereDate('tgl_registrasi', '>=', $data['dari_tanggal']))
                            ->when($data['sampai_tanggal'], fn($q) => $q->whereDate('tgl_registrasi', '<=', $data['sampai_tanggal']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators[] = 'Dari ' . \Illuminate\Support\Carbon::parse($data['dari_tanggal'])->format('d M Y');
                        }
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators[] = 'Sampai ' . \Illuminate\Support\Carbon::parse($data['sampai_tanggal'])->format('d M Y');
                        }
                        return $indicators;
                    }),
                Tables\Filters\SelectFilter::make('kd_poli')
                    ->relationship('poliklinik', 'nm_poli')
                    ->label('Filter Poli'),
                Tables\Filters\SelectFilter::make('kd_pj')
                    ->relationship('penjab', 'png_jawab')
                    ->label('Filter Bayar'),
            ])
            ->actions([
                Tables\Actions\Action::make('isi_resume')
                    ->label('Isi Resume')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (RegPeriksa $record): string => ResumeMedisResource::getUrl('create', ['no_rawat' => $record->no_rawat]))
                    ->visible(fn (RegPeriksa $record) => !$record->resumeMedis()->exists()),
                Tables\Actions\EditAction::make()
                    ->visible(fn (RegPeriksa $record) => $record->stts !== 'Selesai'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(10)
            ->defaultSort('tgl_registrasi', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegPeriksas::route('/'),
            'create' => Pages\CreateRegPeriksa::route('/create'),
            'edit' => Pages\EditRegPeriksa::route('/{record}/edit'),
        ];
    }
}
