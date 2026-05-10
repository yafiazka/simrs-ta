<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokterResource\Pages;
use App\Models\Dokter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DokterResource extends Resource
{
    protected static ?string $model = Dokter::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $pluralLabel = 'Dokter';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kd_dokter')
                    ->required()
                    ->maxLength(20)
                    ->label('Kode Dokter'),
                Forms\Components\TextInput::make('nm_dokter')
                    ->required()
                    ->maxLength(100)
                    ->label('Nama Dokter'),
                Forms\Components\TextInput::make('spesialis')
                    ->required()
                    ->maxLength(50)
                    ->label('Spesialis'),
                Forms\Components\TextInput::make('no_telp')
                    ->tel()
                    ->maxLength(20)
                    ->label('No. Telp'),
                Forms\Components\Toggle::make('status')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kd_dokter')->label('Kode'),
                Tables\Columns\TextColumn::make('nm_dokter')->label('Nama Dokter')->searchable(),
                Tables\Columns\TextColumn::make('spesialis')->label('Spesialis'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
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
            'index' => Pages\ListDokters::route('/'),
            'create' => Pages\CreateDokter::route('/create'),
            'edit' => Pages\EditDokter::route('/{record}/edit'),
        ];
    }
}
