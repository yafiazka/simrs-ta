<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenyakitResource\Pages;
use App\Models\Penyakit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PenyakitResource extends Resource
{
    protected static ?string $model = Penyakit::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $pluralLabel = 'Penyakit';
    protected static ?string $navigationLabel = 'Penyakit';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kd_penyakit')
                    ->required()
                    ->maxLength(15)
                    ->unique(ignoreRecord: true)
                    ->label('Kode Penyakit'),
                Forms\Components\TextInput::make('nm_penyakit')
                    ->maxLength(200)
                    ->label('Nama Penyakit (ID)'),
                Forms\Components\TextInput::make('nama_penyakit_en')
                    ->maxLength(255)
                    ->label('Nama Penyakit (EN)'),
                Forms\Components\TextInput::make('kd_ktg')
                    ->maxLength(8)
                    ->label('Kode Kategori'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Menular' => 'Menular',
                        'Tidak Menular' => 'Tidak Menular',
                    ])
                    ->required()
                    ->default('Tidak Menular')
                    ->label('Status Penyakit'),
                Forms\Components\TextInput::make('keterangan')
                    ->maxLength(60)
                    ->label('Keterangan'),
                Forms\Components\Textarea::make('ciri_ciri')
                    ->label('Ciri-ciri Gejala')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kd_penyakit')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nm_penyakit')
                    ->label('Nama Penyakit')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_penyakit_en')
                    ->label('Nama (EN)')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menular' => 'danger',
                        'Tidak Menular' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30),
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
            'index' => Pages\ListPenyakits::route('/'),
            'create' => Pages\CreatePenyakit::route('/create'),
            'edit' => Pages\EditPenyakit::route('/{record}/edit'),
        ];
    }
}
