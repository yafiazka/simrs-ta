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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $pluralLabel = 'Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form
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
                Forms\Components\DateTimePicker::make('tgl_registrasi')
                    ->default(now())
                    ->required()
                    ->label('Tgl. Registrasi'),
                Forms\Components\Select::make('stts')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Diperiksa' => 'Diperiksa',
                        'Batal' => 'Batal',
                        'Selesai' => 'Selesai',
                    ])
                    ->default('Menunggu')
                    ->required()
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_rawat')->label('No. Rawat')->searchable(),
                Tables\Columns\TextColumn::make('pasien.nm_pasien')->label('Pasien')->searchable(),
                Tables\Columns\TextColumn::make('poliklinik.nm_poli')->label('Poli'),
                Tables\Columns\TextColumn::make('tgl_registrasi')->dateTime()->label('Tgl. Daftar')->sortable(),
                Tables\Columns\TextColumn::make('stts')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu' => 'gray',
                        'Diperiksa' => 'warning',
                        'Selesai' => 'success',
                        'Batal' => 'danger',
                    })
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kd_poli')
                    ->relationship('poliklinik', 'nm_poli')
                    ->label('Filter Poli'),
                Tables\Filters\SelectFilter::make('stts')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Diperiksa' => 'Diperiksa',
                        'Selesai' => 'Selesai',
                        'Batal' => 'Batal',
                    ])
                    ->label('Filter Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('isi_resume')
                    ->label('Isi Resume')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (RegPeriksa $record): string => ResumeMedisResource::getUrl('create', ['no_rawat' => $record->no_rawat]))
                    ->visible(fn (RegPeriksa $record) => $record->stts !== 'Selesai'),
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
            'index' => Pages\ListRegPeriksas::route('/'),
            'create' => Pages\CreateRegPeriksa::route('/create'),
            'edit' => Pages\EditRegPeriksa::route('/{record}/edit'),
        ];
    }
}
