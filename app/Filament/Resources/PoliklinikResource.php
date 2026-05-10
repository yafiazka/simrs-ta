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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::class::make('kd_poli')
                    ->required()
                    ->maxLength(255)
                    ->label('Kode Poli'),
                Forms\Components\TextInput::class::make('nm_poli')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Poli'),
                Forms\Components\Toggle::class::make('status')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::class::make('kd_poli')->label('Kode'),
                Tables\Columns\TextColumn::class::make('nm_poli')->label('Nama Poliklinik')->searchable(),
                Tables\Columns\IconColumn::class::make('status')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::class::make(),
                Tables\Actions\DeleteAction::class::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::class::make([
                    Tables\Actions\DeleteBulkAction::class::make(),
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
