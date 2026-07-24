<?php

namespace App\Filament\Pages;

use App\Models\Poliklinik;
use App\Models\RegPeriksa;
use App\Models\ResumeMedis;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $title = 'Dashboard';

    /**
     * Layout 2 kolom: StatsOverview (full) → Chart Poli | Chart Penyakit → Tabel Poli (full)
     */
    public function getColumns(): int | string | array
    {
        return [
            'md' => 1,
            'lg' => 2,
            'xl' => 2,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->exportToExcel()),
        ];
    }

    public function exportToExcel(): StreamedResponse
    {
        $tipePeriode = $this->filters['tipe_periode'] ?? 'bulanan';
        $bulan = $this->filters['bulan'] ?? now()->format('m');
        $semester = $this->filters['semester'] ?? '1';
        $tahun = $this->filters['tahun'] ?? now()->format('Y');

        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        // Determinkan teks label periode dan filter query
        if ($tipePeriode === 'semester') {
            $bAwal = $this->filters['bulan_awal'] ?? '01';
            $bAkhir = $this->filters['bulan_akhir'] ?? '06';

            $startMonth = (int) $bAwal;
            $endMonth = (int) $bAkhir;

            $labelPeriode = ($namaBulan[$bAwal] ?? $bAwal) . ' s.d. ' . ($namaBulan[$bAkhir] ?? $bAkhir) . ' ' . $tahun;

            if ($startMonth <= $endMonth) {
                $applyFilterPoli = fn ($q) => $q->whereYear('tgl_registrasi', $tahun)->whereBetween(\DB::raw('EXTRACT(MONTH FROM tgl_registrasi)'), [$startMonth, $endMonth]);
                $applyFilterPenyakit = fn ($q) => $q->whereYear('tgl_masuk', $tahun)->whereBetween(\DB::raw('EXTRACT(MONTH FROM tgl_masuk)'), [$startMonth, $endMonth]);
            } else {
                // Cross-year or reverse range fallback
                $applyFilterPoli = fn ($q) => $q->whereYear('tgl_registrasi', $tahun)->where(function ($query) use ($startMonth, $endMonth) {
                    $query->whereRaw('EXTRACT(MONTH FROM tgl_registrasi) >= ?', [$startMonth])
                          ->orWhereRaw('EXTRACT(MONTH FROM tgl_registrasi) <= ?', [$endMonth]);
                });
                $applyFilterPenyakit = fn ($q) => $q->whereYear('tgl_masuk', $tahun)->where(function ($query) use ($startMonth, $endMonth) {
                    $query->whereRaw('EXTRACT(MONTH FROM tgl_masuk) >= ?', [$startMonth])
                          ->orWhereRaw('EXTRACT(MONTH FROM tgl_masuk) <= ?', [$endMonth]);
                });
            }
        } elseif ($tipePeriode === 'tahunan') {
            $labelPeriode = 'Tahun ' . $tahun;

            $applyFilterPoli = fn ($q) => $q->whereYear('tgl_registrasi', $tahun);
            $applyFilterPenyakit = fn ($q) => $q->whereYear('tgl_masuk', $tahun);
        } else {
            $labelPeriode = ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;

            $applyFilterPoli = fn ($q) => $q->whereMonth('tgl_registrasi', $bulan)->whereYear('tgl_registrasi', $tahun);
            $applyFilterPenyakit = fn ($q) => $q->whereMonth('tgl_masuk', $bulan)->whereYear('tgl_masuk', $tahun);
        }

        $spreadsheet = new Spreadsheet();

        // Style Helper Arrays
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E3A8A'] // Header Biru Gelap
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ];

        // Sheet 1: Pasien per Poliklinik
        $sheetPoli = $spreadsheet->getActiveSheet();
        $sheetPoli->setTitle('Pasien per Poli');

        $sheetPoli->setCellValue('A1', 'LAPORAN JUMLAH PASIEN PER POLIKLINIK');
        $sheetPoli->setCellValue('A2', 'Periode: ' . $labelPeriode);
        $sheetPoli->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheetPoli->getStyle('A2')->getFont()->setItalic(true)->setSize(11);

        $sheetPoli->setCellValue('A4', 'No');
        $sheetPoli->setCellValue('B4', 'Nama Poliklinik');
        $sheetPoli->setCellValue('C4', 'Jumlah Pasien');
        $sheetPoli->getStyle('A4:C4')->applyFromArray($headerStyle);
        $sheetPoli->getRowDimension(4)->setRowHeight(25);

        $poliData = Poliklinik::query()
            ->withCount(['regPeriksas' => $applyFilterPoli])
            ->get();

        $row = 5;
        $no = 1;
        foreach ($poliData as $poli) {
            $sheetPoli->setCellValue('A' . $row, $no++);
            $sheetPoli->setCellValue('B' . $row, $poli->nm_poli);
            $sheetPoli->setCellValue('C' . $row, $poli->reg_periksas_count);

            // Alternate row background for aesthetics
            if ($row % 2 == 1) {
                $sheetPoli->getStyle('A' . $row . ':C' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9FAFB');
            }

            $sheetPoli->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetPoli->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $row++;
        }

        $lastRowPoli = max(5, $row - 1);
        $sheetPoli->getStyle('A4:C' . $lastRowPoli)->applyFromArray($borderStyle);

        // Lebar kolom rapi
        $sheetPoli->getColumnDimension('A')->setWidth(8);
        $sheetPoli->getColumnDimension('B')->setWidth(40);
        $sheetPoli->getColumnDimension('C')->setWidth(20);

        // Sheet 2: 10 Besar Penyakit
        $sheetPenyakit = $spreadsheet->createSheet();
        $sheetPenyakit->setTitle('10 Besar Penyakit');

        $sheetPenyakit->setCellValue('A1', '10 BESAR PENYAKIT TERTINGGI');
        $sheetPenyakit->setCellValue('A2', 'Periode: ' . $labelPeriode);
        $sheetPenyakit->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheetPenyakit->getStyle('A2')->getFont()->setItalic(true)->setSize(11);

        $sheetPenyakit->setCellValue('A4', 'No');
        $sheetPenyakit->setCellValue('B4', 'Kode ICD-10');
        $sheetPenyakit->setCellValue('C4', 'Nama Diagnosa / Penyakit');
        $sheetPenyakit->setCellValue('D4', 'Jumlah Kasus');
        $sheetPenyakit->getStyle('A4:D4')->applyFromArray($headerStyle);
        $sheetPenyakit->getRowDimension(4)->setRowHeight(25);

        $penyakitQuery = ResumeMedis::query()
            ->leftJoin('penyakit', 'resume_medis.diagnosa_utama', '=', 'penyakit.kd_penyakit')
            ->selectRaw('
                resume_medis.diagnosa_utama as kode_penyakit, 
                MAX(penyakit.nm_penyakit) as nama_penyakit, 
                count(*) as total
            ')
            ->whereNotNull('resume_medis.diagnosa_utama')
            ->where('resume_medis.diagnosa_utama', '!=', '');
        
        $applyFilterPenyakit($penyakitQuery);

        $penyakitData = $penyakitQuery->groupBy('resume_medis.diagnosa_utama')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $rowP = 5;
        $noP = 1;
        foreach ($penyakitData as $penyakit) {
            $sheetPenyakit->setCellValue('A' . $rowP, $noP++);
            $sheetPenyakit->setCellValue('B' . $rowP, $penyakit->kode_penyakit);
            $sheetPenyakit->setCellValue('C' . $rowP, $penyakit->nama_penyakit ?? '-');
            $sheetPenyakit->setCellValue('D' . $rowP, $penyakit->total);

            if ($rowP % 2 == 1) {
                $sheetPenyakit->getStyle('A' . $rowP . ':D' . $rowP)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F9FAFB');
            }

            $sheetPenyakit->getStyle('A' . $rowP)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetPenyakit->getStyle('B' . $rowP)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheetPenyakit->getStyle('D' . $rowP)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $rowP++;
        }

        $lastRowPenyakit = max(5, $rowP - 1);
        $sheetPenyakit->getStyle('A4:D' . $lastRowPenyakit)->applyFromArray($borderStyle);

        // Lebar kolom rapi
        $sheetPenyakit->getColumnDimension('A')->setWidth(8);
        $sheetPenyakit->getColumnDimension('B')->setWidth(18);
        $sheetPenyakit->getColumnDimension('C')->setWidth(45);
        $sheetPenyakit->getColumnDimension('D')->setWidth(18);

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Dashboard_Report_' . $tipePeriode . '_' . $tahun . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            \Filament\Forms\Components\Grid::make()
                ->schema([
                Select::make('tipe_periode')
                    ->label('Rentang Waktu')
                    ->options([
                        'bulanan' => 'Bulanan',
                        'semester' => 'Per 6 Bulan (Semester)',
                        'tahunan' => 'Tahunan',
                    ])
                    ->default('bulanan')
                    ->native(false)
                    ->live()
                    ->required(),

                Select::make('bulan')
                    ->label('Bulan')
                    ->options([
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->default(now()->format('m'))
                    ->native(false)
                    ->visible(fn ($get) => ($get('tipe_periode') ?? 'bulanan') === 'bulanan')
                    ->required(fn ($get) => ($get('tipe_periode') ?? 'bulanan') === 'bulanan'),

                Select::make('bulan_awal')
                    ->label('Bulan Awal')
                    ->options([
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                    ])
                    ->default('01')
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $endMonth = str_pad((((int)$state + 5 - 1) % 12) + 1, 2, '0', STR_PAD_LEFT);
                        $set('bulan_akhir', $endMonth);
                    })
                    ->visible(fn ($get) => $get('tipe_periode') === 'semester')
                    ->required(fn ($get) => $get('tipe_periode') === 'semester'),

                Select::make('bulan_akhir')
                    ->label('Bulan Akhir (6 Bulan)')
                    ->options([
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                    ])
                    ->default('06')
                    ->native(false)
                    ->visible(fn ($get) => $get('tipe_periode') === 'semester')
                    ->required(fn ($get) => $get('tipe_periode') === 'semester'),

                Select::make('tahun')
                    ->label('Tahun')
                    ->options(array_combine(
                        range(now()->year - 5, now()->year),
                        range(now()->year - 5, now()->year)
                    ))
                    ->default(now()->year)
                    ->native(false)
                    ->required(),
            ])->columns([
                'default' => 1,
                'sm' => 2,
                'md' => 4,
                'lg' => 6,
            ]),
        ]);
    }
}
