<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResumeMedisResource\Pages;
use App\Models\ResumeMedis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResumeMedisResource extends Resource
{
    protected static ?string $model = ResumeMedis::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $pluralLabel = 'Resume Medis';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('no_rawat')
                    ->relationship('regPeriksa', 'no_rawat')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('No. Rawat'),
                Forms\Components\DatePicker::make('tgl_keluar')
                    ->default(now())
                    ->label('Tgl. Keluar'),
                Forms\Components\Textarea::make('keluhan')
                    ->label('Keluhan'),
                Forms\Components\Textarea::make('pemeriksaan_fisik')
                    ->label('Pemeriksaan Fisik'),
                Forms\Components\Textarea::make('diagnosa')
                    ->label('Diagnosa'),
                Forms\Components\Textarea::make('terapi')
                    ->label('Terapi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_rawat')->label('No. Rawat')->searchable(),
                Tables\Columns\TextColumn::make('regPeriksa.pasien.nm_pasien')->label('Pasien'),
                Tables\Columns\TextColumn::make('tgl_keluar')->date()->label('Tgl. Keluar'),
                Tables\Columns\TextColumn::make('diagnosa')->limit(50)->label('Diagnosa'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (ResumeMedis $record): string => route('resume.pdf', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListResumeMedis::route('/'),
            'create' => Pages\CreateResumeMedis::route('/create'),
            'edit' => Pages\EditResumeMedis::route('/{record}/edit'),
        ];
    }
}
