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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pasien')
                    ->schema([
                        Forms\Components\TextInput::make('no_rkm_medis')
                            ->required()
                            ->label('No. Rekam Medis'),
                        Forms\Components\TextInput::make('nm_pasien')
                            ->required()
                            ->label('Nama Pasien'),
                        Forms\Components\TextInput::make('no_ktp')
                            ->label('No. KTP'),
                        Forms\Components\Select::make('jk')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required()
                            ->label('Jenis Kelamin'),
                        Forms\Components\DatePicker::make('tgl_lahir')
                            ->label('Tanggal Lahir'),
                        Forms\Components\TextInput::make('no_tlp')
                            ->tel()
                            ->label('No. Telp'),
                        Forms\Components\TextInput::make('agama')
                            ->label('Agama'),
                        Forms\Components\TextInput::make('gol_darah')
                            ->label('Gol. Darah'),
                        Forms\Components\Textarea::make('alamat')
                            ->columnSpanFull()
                            ->label('Alamat'),
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
