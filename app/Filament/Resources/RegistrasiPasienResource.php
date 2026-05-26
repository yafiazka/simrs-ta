<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrasiPasienResource\Pages;
use App\Models\Pasien;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegistrasiPasienResource extends Resource
{
    protected static ?string $model = Pasien::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Pelayanan Klinis';
    protected static ?string $pluralLabel = 'Registrasi Pasien';
    protected static ?string $navigationLabel = 'Registrasi Pasien';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('cari_pasien_lama')
                    ->label('Cari Pasien Lama (No. RM / NIK / Nama)')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return \App\Models\Pasien::query()
                            ->where('no_rkm_medis', 'like', "%{$search}%")
                            ->orWhere('no_ktp', 'like', "%{$search}%")
                            ->orWhere('nm_pasien', 'like', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($pasien) => [$pasien->no_rkm_medis => "[{$pasien->no_rkm_medis}] {$pasien->nm_pasien} - NIK: {$pasien->no_ktp}"])
                            ->toArray();
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => ($pasien = \App\Models\Pasien::find($value)) ? "[{$pasien->no_rkm_medis}] {$pasien->nm_pasien}" : null)
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (!$state) {
                            $last = \App\Models\Pasien::orderBy('no_rkm_medis', 'desc')->first();
                            $lastNumber = $last ? intval($last->no_rkm_medis) : 0;
                            $set('no_rkm_medis', str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT));
                            
                            $set('nm_pasien', null);
                            $set('no_ktp', null);
                            $set('tmp_lahir', null);
                            $set('tgl_lahir', null);
                            $set('jk', null);
                            $set('nm_ibu', null);
                            $set('agama', null);
                            $set('stts_nikah', null);
                            $set('no_tlp', null);
                            $set('pekerjaan', null);
                            $set('kd_pj', null);
                            $set('kabupaten', null);
                            $set('kecamatan', null);
                            $set('kelurahan', null);
                            $set('desa', null);
                            $set('alamat', null);
                            $set('keluarga', null);
                            $set('namakeluarga', null);
                            $set('pekerjaanpj', null);
                            $set('alamatpj', null);
                            $set('status_kunjungan', 'Baru');
                            return;
                        }

                        $pasien = \App\Models\Pasien::find($state);
                        if ($pasien) {
                            $set('no_rkm_medis', $pasien->no_rkm_medis);
                            $set('nm_pasien', $pasien->nm_pasien);
                            $set('no_ktp', $pasien->no_ktp);
                            $set('tmp_lahir', $pasien->tmp_lahir);
                            $set('tgl_lahir', $pasien->tgl_lahir?->toDateString());
                            $set('jk', $pasien->jk);
                            $set('nm_ibu', $pasien->nm_ibu);
                            $set('agama', $pasien->agama);
                            $set('stts_nikah', $pasien->stts_nikah);
                            $set('no_tlp', $pasien->no_tlp);
                            $set('pekerjaan', $pasien->pekerjaan);
                            $set('kd_pj', $pasien->kd_pj);
                            $set('kabupaten', $pasien->kabupaten);
                            $set('kecamatan', $pasien->kecamatan);
                            $set('kelurahan', $pasien->kelurahan);
                            $set('desa', $pasien->desa);
                            $set('alamat', $pasien->alamat);
                            $set('keluarga', $pasien->keluarga);
                            $set('namakeluarga', $pasien->namakeluarga);
                            $set('pekerjaanpj', $pasien->pekerjaanpj);
                            $set('alamatpj', $pasien->alamatpj);
                            $set('status_kunjungan', 'Lama');
                        }
                    })
                    ->placeholder('Ketik No RM, NIK, atau Nama Pasien...')
                    ->columnSpanFull()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateRegistrasiPasien),

                Forms\Components\Section::make('Registrasi Kunjungan Poliklinik')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status_kunjungan')
                                    ->options([
                                        'Baru' => 'Pasien Baru',
                                        'Lama' => 'Pasien Lama',
                                    ])
                                    ->default('Baru')
                                    ->required()
                                    ->label('Status Kunjungan'),
                                Forms\Components\Select::make('kd_pj_reg')
                                    ->options(\App\Models\Penjab::pluck('png_jawab', 'kd_pj'))
                                    ->required()
                                    ->label('Pilihan Pembayaran / Cara Bayar'),
                                Forms\Components\Select::make('kd_poli')
                                    ->options(\App\Models\Poliklinik::where('status', true)->pluck('nm_poli', 'kd_poli'))
                                    ->required()
                                    ->label('Poliklinik Tujuan'),
                                Forms\Components\Select::make('kd_dokter')
                                    ->options(\App\Models\Dokter::pluck('nm_dokter', 'kd_dokter'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Dokter DPJP'),
                            ]),
                        Forms\Components\TextInput::make('diagnosa_awal')
                            ->label('Keluhan / Diagnosa Awal')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Identitas Pasien')
                    ->schema([
                        Forms\Components\TextInput::make('no_rkm_medis')
                            ->readOnly()
                            ->dehydrated(true)
                            ->default(function () {
                                $last = \App\Models\Pasien::orderBy('no_rkm_medis', 'desc')->first();
                                $lastNumber = $last ? intval($last->no_rkm_medis) : 0;
                                return str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
                            })
                            ->label('No. Rekam Medis'),
                        Forms\Components\TextInput::make('nm_pasien')
                            ->required()
                            ->maxLength(100)
                            ->label('Nama Pasien'),
                        Forms\Components\TextInput::make('no_ktp')
                            ->maxLength(20)
                            ->label('No. KTP'),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tmp_lahir')
                                    ->label('Tempat Lahir'),
                                Forms\Components\DatePicker::make('tgl_lahir')
                                    ->label('Tanggal Lahir'),
                            ]),
                        Forms\Components\Select::make('jk')
                            ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                            ->label('Jenis Kelamin'),
                        Forms\Components\TextInput::make('nm_ibu')
                            ->required()
                            ->label('Nama Ibu Kandung'),
                        Forms\Components\Select::make('agama')
                            ->options([
                                'ISLAM' => 'ISLAM',
                                'KRISTEN' => 'KRISTEN',
                                'KATOLIK' => 'KATOLIK',
                                'HINDU' => 'HINDU',
                                'BUDHA' => 'BUDHA',
                                'KONGHUCU' => 'KONGHUCU',
                             ])
                            ->label('Agama'),
                        Forms\Components\Select::make('stts_nikah')
                            ->options([
                                'BELUM MENIKAH' => 'BELUM MENIKAH',
                                'MENIKAH' => 'MENIKAH',
                                'JANDA' => 'JANDA',
                                'DUDA' => 'DUDA',
                            ])
                            ->label('Status Nikah'),
                        Forms\Components\TextInput::make('no_tlp')
                            ->tel()
                            ->label('No. Telp'),
                        Forms\Components\TextInput::make('pekerjaan')
                            ->label('Pekerjaan'),
                        Forms\Components\Select::make('kd_pj')
                            ->relationship('penjab', 'png_jawab')
                            ->label('Asuransi/Penjamin'),
                        Forms\Components\Section::make('Detail Alamat Pasien')
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('kabupaten')
                                            ->maxLength(60)
                                            ->label('Kabupaten/Kota'),
                                        Forms\Components\TextInput::make('kecamatan')
                                            ->maxLength(60)
                                            ->label('Kecamatan'),
                                        Forms\Components\TextInput::make('kelurahan')
                                            ->maxLength(60)
                                            ->label('Kelurahan'),
                                        Forms\Components\TextInput::make('desa')
                                            ->maxLength(60)
                                            ->label('Desa'),
                                    ]),
                                Forms\Components\Textarea::make('alamat')
                                    ->columnSpanFull()
                                    ->label('Alamat Jalan / RT / RW'),
                            ])->compact()->collapsible(false),
                    ])->columns(2),

                Forms\Components\Section::make('Data Keluarga')
                    ->schema([
                        Forms\Components\Select::make('keluarga')
                            ->options([
                                'AYAH' => 'AYAH',
                                'IBU' => 'IBU',
                                'ISTRI' => 'ISTRI',
                                'SUAMI' => 'SUAMI',
                                'SAUDARA' => 'SAUDARA',
                                'ANAK' => 'ANAK',
                                'DIRI SENDIRI' => 'DIRI SENDIRI',
                            ])
                            ->label('Hubungan Keluarga'),
                        Forms\Components\TextInput::make('namakeluarga')
                            ->required()
                            ->label('Nama Penanggung Jawab'),
                        Forms\Components\TextInput::make('pekerjaanpj')
                            ->label('Pekerjaan PJ'),
                        Forms\Components\Textarea::make('alamatpj')
                            ->columnSpanFull()
                            ->label('Alamat PJ'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('no_rkm_medis', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('no_rkm_medis')->label('No. RM')->searchable(),
                Tables\Columns\TextColumn::make('nm_pasien')->label('Nama Pasien')->searchable(),
                Tables\Columns\TextColumn::make('jk')->label('JK'),
                Tables\Columns\TextColumn::make('no_tlp')->label('Telepon'),
                Tables\Columns\TextColumn::make('tgl_lahir')->date()->label('Tgl Lahir'),
                Tables\Columns\TextColumn::make('tgl_daftar')->date()->label('Tgl Daftar')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('tgl_daftar')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')->label('Dari Tanggal')->default(now()->toDateString()),
                        Forms\Components\DatePicker::make('sampai_tanggal')->label('Sampai Tanggal')->default(now()->toDateString()),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tgl_daftar', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tgl_daftar', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Disable bulk delete on patients
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrasiPasiens::route('/'),
            'create' => Pages\CreateRegistrasiPasien::route('/create'),
            'edit' => Pages\EditRegistrasiPasien::route('/{record}/edit'),
        ];
    }
}
