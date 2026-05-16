<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KamarResource\Pages;
use App\Models\Kamar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KamarResource extends Resource
{
    protected static ?string $model = Kamar::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $pluralLabel = 'Kamar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kd_kamar')
                    ->required()
                    ->maxLength(15)
                    ->label('Kode Kamar'),
                Forms\Components\TextInput::make('nm_kamar')
                    ->required()
                    ->maxLength(100)
                    ->label('Nama Kamar/Ruangan'),
                Forms\Components\Select::make('kelas')
                    ->options([
                        'Kelas 1' => 'Kelas 1',
                        'Kelas 2' => 'Kelas 2',
                        'Kelas 3' => 'Kelas 3',
                        'VIP' => 'VIP',
                        'VVIP' => 'VVIP',
                    ])
                    ->required()
                    ->label('Kelas'),
                Forms\Components\TextInput::make('trf_kamar')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->label('Tarif Kamar'),
                Forms\Components\Select::make('stts')
                    ->options([
                        'ISI' => 'ISI',
                        'KOSONG' => 'KOSONG',
                    ])
                    ->default('KOSONG')
                    ->required()
                    ->label('Status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kd_kamar')->label('Kode'),
                Tables\Columns\TextColumn::make('nm_kamar')->label('Kamar')->searchable(),
                Tables\Columns\TextColumn::make('kelas')->label('Kelas'),
                Tables\Columns\TextColumn::make('trf_kamar')->money('IDR')->label('Tarif'),
                Tables\Columns\TextColumn::make('stts')
                    ->badge()
                    ->color(fn (string $state): string => [
                        'ISI' => 'danger',
                        'KOSONG' => 'success',
                    ][$state] ?? 'gray')
                    ->label('Status'),
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
            'index' => Pages\ListKamars::route('/'),
            'create' => Pages\CreateKamar::route('/create'),
            'edit' => Pages\EditKamar::route('/{record}/edit'),
        ];
    }
}
