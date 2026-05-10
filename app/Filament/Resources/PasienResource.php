<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasienResource\Pages;
use App\Models\Pasien;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PasienResource extends Resource
{
    protected static ?string $model = Pasien::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $pluralLabel = 'Pasien';
    protected static ?string $navigationLabel = 'Pasien';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pasien')
                    ->schema([
                        Forms\Components\TextInput::make('no_rkm_medis')
                            ->required()
                            ->maxLength(15)
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
                        Forms\Components\Textarea::make('alamat')
                            ->columnSpanFull()
                            ->label('Alamat Lengkap'),
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
            ->columns([
                Tables\Columns\TextColumn::make('no_rkm_medis')->label('No. RM')->searchable(),
                Tables\Columns\TextColumn::make('nm_pasien')->label('Nama Pasien')->searchable(),
                Tables\Columns\TextColumn::make('jk')->label('JK'),
                Tables\Columns\TextColumn::make('no_tlp')->label('Telepon'),
                Tables\Columns\TextColumn::make('tgl_lahir')->date()->label('Tgl Lahir'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListPasiens::route('/'),
            'create' => Pages\CreatePasien::route('/create'),
            'edit' => Pages\EditPasien::route('/{record}/edit'),
        ];
    }
}
