<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoliklinikResource\Pages;
use App\Models\Poliklinik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PoliklinikResource extends Resource
{
    protected static ?string $model = Poliklinik::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $pluralLabel = 'Poliklinik';
    protected static ?string $navigationLabel = 'Poliklinik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kd_poli')
                    ->required()
                    ->maxLength(255)
                    ->label('Kode Poli'),
                Forms\Components\TextInput::make('nm_poli')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Poli'),
                Forms\Components\Toggle::make('status')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kd_poli')->label('Kode'),
                Tables\Columns\TextColumn::make('nm_poli')->label('Nama Poliklinik')->searchable(),
                Tables\Columns\IconColumn::make('status')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPolikliniks::route('/'),
            'create' => Pages\CreatePoliklinik::route('/create'),
            'edit' => Pages\EditPoliklinik::route('/{record}/edit'),
        ];
    }
}
