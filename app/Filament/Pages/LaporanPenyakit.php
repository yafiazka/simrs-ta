<?php

namespace App\Filament\Pages;

use App\Models\ResumeMedis;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Response;

class LaporanPenyakit extends Page implements HasTable
{
    use InteractsWithTable;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Pelayanan Klinis';
    protected static ?string $navigationLabel = 'Laporan 10 Besar Penyakit';
    protected static ?string $title = 'Laporan 10 Besar Penyakit';

    protected static string $view = 'filament.pages.laporan-penyakit';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ResumeMedis::query()
                    ->leftJoin('penyakit', 'resume_medis.diagnosa_utama', '=', 'penyakit.kd_penyakit')
                    ->selectRaw('COALESCE(penyakit.nm_penyakit, resume_medis.diagnosa_utama) as nama_penyakit, resume_medis.diagnosa_utama as kode_penyakit, count(*) as total')
                    ->groupBy('nama_penyakit', 'kode_penyakit')
                    ->orderByDesc('total')
            )
            ->recordKey(fn ($record) => $record->kode_penyakit ?? $record->nama_penyakit ?? uniqid())
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('Peringkat')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('kode_penyakit')
                    ->label('Kode Penyakit (ICD-10)')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_penyakit')
                    ->label('Nama Penyakit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Jumlah Kasus')
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('percentage')
                    ->label('Persentase')
                    ->getStateUsing(function ($record) {
                        $filters = $this->getTableFiltersForm()->getRawState();
                        $dari = $filters['tgl_masuk']['dari_tanggal'] ?? null;
                        $sampai = $filters['tgl_masuk']['sampai_tanggal'] ?? null;

                        $queryAll = ResumeMedis::query();
                        if ($dari) {
                            $queryAll->whereDate('tgl_masuk', '>=', $dari);
                        }
                        if ($sampai) {
                            $queryAll->whereDate('tgl_masuk', '<=', $sampai);
                        }

                        $totalAll = $queryAll->count();
                        if ($totalAll == 0) return '0%';
                        return round(($record->total / $totalAll) * 100, 2) . '%';
                    })
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tgl_masuk')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn($q) => $q->whereDate('resume_medis.tgl_masuk', '>=', $data['dari_tanggal']))
                            ->when($data['sampai_tanggal'], fn($q) => $q->whereDate('resume_medis.tgl_masuk', '<=', $data['sampai_tanggal']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari_tanggal'] ?? null) {
                            $indicators[] = 'Mulai ' . \Illuminate\Support\Carbon::parse($data['dari_tanggal'])->format('d M Y');
                        }
                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators[] = 'Sampai ' . \Illuminate\Support\Carbon::parse($data['sampai_tanggal'])->format('d M Y');
                        }
                        return $indicators;
                    })
            ])
            ->headerActions([
                Tables\Actions\Action::make('download_csv')
                    ->label('Unduh CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => $this->exportCsv()),
            ])
            ->paginated(false);
    }

    public function exportCsv()
    {
        $filename = "laporan_10_besar_penyakit_" . now()->format('YmdHis') . ".csv";
        
        $filters = $this->getTableFiltersForm()->getRawState();
        $dari = $filters['tgl_masuk']['dari_tanggal'] ?? null;
        $sampai = $filters['tgl_masuk']['sampai_tanggal'] ?? null;

        return Response::streamDownload(function() use ($dari, $sampai) {
            $handle = fopen('php://output', 'w');
            
            // Header
            fputcsv($handle, [
                'Peringkat', 'Kode Penyakit', 'Nama Penyakit', 'Jumlah Kasus', 'Persentase'
            ]);

            $query = ResumeMedis::query()
                ->leftJoin('penyakit', 'resume_medis.diagnosa_utama', '=', 'penyakit.kd_penyakit')
                ->selectRaw('COALESCE(penyakit.nm_penyakit, resume_medis.diagnosa_utama) as nama_penyakit, resume_medis.diagnosa_utama as kode_penyakit, count(*) as total')
                ->groupBy('nama_penyakit', 'kode_penyakit')
                ->orderByDesc('total');

            if ($dari) {
                $query->whereDate('resume_medis.tgl_masuk', '>=', $dari);
            }
            if ($sampai) {
                $query->whereDate('resume_medis.tgl_masuk', '<=', $sampai);
            }

            $diseases = $query->limit(10)->get();

            $queryAll = ResumeMedis::query();
            if ($dari) {
                $queryAll->whereDate('tgl_masuk', '>=', $dari);
            }
            if ($sampai) {
                $queryAll->whereDate('tgl_masuk', '<=', $sampai);
            }
            $totalAll = $queryAll->count();

            foreach ($diseases as $index => $row) {
                $percentage = $totalAll > 0 ? round(($row->total / $totalAll) * 100, 2) . '%' : '0%';
                fputcsv($handle, [
                    $index + 1,
                    $row->kode_penyakit ?? '-',
                    $row->nama_penyakit ?? '-',
                    $row->total,
                    $percentage
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
