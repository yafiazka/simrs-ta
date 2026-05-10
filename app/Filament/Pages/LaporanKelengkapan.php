<?php

namespace App\Filament\Pages;

use App\Models\RegPeriksa;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Response;

class LaporanKelengkapan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Pelayanan Klinis';
    protected static ?string $navigationLabel = 'Laporan Kelengkapan';
    protected static ?string $title = 'Laporan Kelengkapan Rekam Medis';

    protected static string $view = 'filament.pages.laporan-kelengkapan';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                RegPeriksa::query()
                    ->with(['pasien', 'resumeMedis', 'poliklinik'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('no_rawat')->label('No. Rawat')->searchable(),
                Tables\Columns\TextColumn::make('pasien.nm_pasien')->label('Nama Pasien')->searchable(),
                Tables\Columns\TextColumn::make('poliklinik.nm_poli')->label('Poliklinik'),
                Tables\Columns\TextColumn::make('tgl_registrasi')->date()->label('Tgl. Daftar'),
                Tables\Columns\IconColumn::make('resumeMedis')
                    ->label('Status Resume')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->resumeMedis()->exists()),
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_excel')
                    ->label('Unduh Excel (CSV)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => $this->exportCsv()),
            ]);
    }

    public function exportCsv()
    {
        $filename = "laporan_kelengkapan_" . now()->format('YmdHis') . ".csv";
        
        return Response::streamDownload(function() {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, [
                'No. Rawat', 'No. RM', 'Nama Pasien', 'Poliklinik', 'Tgl. Daftar', 
                'Penjamin', 'Keluhan', 'Diagnosa Utama', 'Tindakan', 'Status Resume'
            ]);

            RegPeriksa::with(['pasien', 'resumeMedis', 'poliklinik', 'penjab'])->chunk(100, function($regs) use ($handle) {
                foreach ($regs as $row) {
                    fputcsv($handle, [
                        $row->no_rawat,
                        $row->no_rkm_medis,
                        $row->pasien->nm_pasien ?? '-',
                        $row->poliklinik->nm_poli ?? '-',
                        $row->tgl_registrasi ? $row->tgl_registrasi->format('Y-m-d') : '-',
                        $row->penjab->png_jawab ?? '-',
                        $row->resumeMedis->keluhan ?? '-',
                        $row->resumeMedis->diagnosa_utama ?? '-',
                        $row->resumeMedis->tindakan_prosedur ?? '-',
                        $row->resumeMedis ? 'Lengkap' : 'Belum Lengkap'
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
