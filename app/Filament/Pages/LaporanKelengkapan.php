<?php

namespace App\Filament\Pages;

use App\Models\ResumeMedis;
use App\Models\RegPeriksa;
use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class LaporanKelengkapan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Pelayanan Klinis';
    protected static ?string $navigationLabel = 'Laporan Puskesmas';
    protected static ?string $title = 'Laporan Puskesmas';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.laporan-kelengkapan';

    public ?array $data = [];
    public int $perPage = 20;

    public function mount(): void
    {
        $this->form->fill([
            'dari_tanggal' => now()->startOfMonth()->toDateString(),
            'sampai_tanggal' => now()->toDateString(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->live(),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->live(),
                    ]),
            ])
            ->statePath('data');
    }

    public function updated($name): void
    {
        if (str_starts_with($name, 'data.')) {
            $this->perPage = 20;
        }
    }

    public function loadMore(): void
    {
        $this->perPage += 20;
    }

    public function getDetailedLaporan(bool $all = false)
    {
        $dari = $this->data['dari_tanggal'] ?? null;
        $sampai = $this->data['sampai_tanggal'] ?? null;

        $query = RegPeriksa::query()
            ->with(['pasien', 'poliklinik', 'dokter', 'penjab', 'resumeMedis.diagnosaUtamaPenyakit']);

        if ($dari) {
            $query->whereDate('tgl_registrasi', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tgl_registrasi', '<=', $sampai);
        }

        $query->orderBy('tgl_registrasi', 'desc');

        if (!$all) {
            $query->limit($this->perPage);
        }

        return $query->get();
    }

    public function exportDetailedLaporanXlsx()
    {
        $filename = "laporan_puskesmas_lengkap_" . now()->format('YmdHis') . ".xlsx";
        $data = $this->getDetailedLaporan(true);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kunjungan');

        // Show gridlines
        $sheet->setShowGridlines(true);

        // Header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '1F2937'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3F4F6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ];

        // Headers
        $headers = [
            'NO', 'RUANGAN', 'NAMA PASIEN', 'CM', 'NIK', 'UMUR', 'STATUS SOSIAL', 
            'DX', 'DESA', 'KECAMATAN', 'KABUPATEN/KOTA', 'PENDIDIKAN', 'PEKERJAAN', 
            'TGL MASUK', 'DPJP', 'TGL PULANG', 'CARA PULANG'
        ];

        foreach ($headers as $colIndex => $header) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue($colLetter . '1', $header);
        }

        $sheet->getStyle('A1:Q1')->applyFromArray($headerStyle);
        $sheet->getRowDimension('1')->setRowHeight(28);

        // Data Row Style
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB'],
                ],
            ],
        ];

        // DPJP Column Style (Light Blue fill)
        $dpjpStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0F2FE'], // light blue / sky 100
            ],
            'font' => [
                'color' => ['rgb' => '0369A1'], // dark blue text
            ],
        ];

        // Write data
        $rowNum = 2;
        foreach ($data as $index => $row) {
            $umur = "{$row->umurdaftar} {$row->sttsumur}";
            $status_sosial = $row->penjab->png_jawab ?? '-';
            $dx = $row->resumeMedis ? '[' . $row->resumeMedis->diagnosa_utama . '] ' . ($row->resumeMedis->diagnosaUtamaPenyakit->nm_penyakit ?? '') : '-';
            $desa = $row->pasien?->kelurahan ?? $row->pasien?->desa ?? '-';
            $tgl_masuk = $row->tgl_registrasi ? $row->tgl_registrasi->format('d/m/Y') : '-';
            $tgl_pulang = $row->resumeMedis && $row->resumeMedis->tgl_keluar ? $row->resumeMedis->tgl_keluar->format('d/m/Y') : '-';
            $cara_pulang_map = [
                'dipulangkan'  => 'Dipulangkan',
                'dirujuk_rs'   => 'Dirujuk ke RS',
                'meninggal'    => 'Meninggal',
                'pulang_paksa' => 'Pulang Paksa / APS',
            ];
            $cara_pulang = $row->resumeMedis
                ? ($cara_pulang_map[$row->resumeMedis->cara_keluar] ?? $row->resumeMedis->cara_keluar ?? '-')
                : '-';

            $sheet->setCellValue('A' . $rowNum, $index + 1);
            $sheet->setCellValue('B' . $rowNum, $row->poliklinik?->nm_poli ?? '-');
            $sheet->setCellValue('C' . $rowNum, $row->pasien?->nm_pasien ?? '-');
            $sheet->setCellValue('D' . $rowNum, $row->no_rkm_medis ?? '-');
            $sheet->setCellValue('E' . $rowNum, $row->pasien?->no_ktp ?? '-');
            $sheet->setCellValue('F' . $rowNum, $umur);
            $sheet->setCellValue('G' . $rowNum, $status_sosial);
            $sheet->setCellValue('H' . $rowNum, $dx);
            $sheet->setCellValue('I' . $rowNum, $desa);
            $sheet->setCellValue('J' . $rowNum, $row->pasien?->kecamatan ?? '-');
            $sheet->setCellValue('K' . $rowNum, $row->pasien?->kabupaten ?? '-');
            $sheet->setCellValue('L' . $rowNum, $row->pasien?->pnd ?? '-');
            $sheet->setCellValue('M' . $rowNum, $row->pasien?->pekerjaan ?? '-');
            $sheet->setCellValue('N' . $rowNum, $tgl_masuk);
            $sheet->setCellValue('O' . $rowNum, $row->dokter->nm_dokter ?? '-');
            $sheet->setCellValue('P' . $rowNum, $tgl_pulang);
            $sheet->setCellValue('Q' . $rowNum, $cara_pulang);

            // Apply light blue style to DPJP column (Column O is the 15th column)
            $sheet->getStyle('O' . $rowNum)->applyFromArray($dpjpStyle);

            $sheet->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        // Apply borders to all data cells
        if ($rowNum > 2) {
            $sheet->getStyle('A2:Q' . ($rowNum - 1))->applyFromArray($borderStyle);
            // Center align NO, CM, NIK, TGL, CARA PULANG
            $sheet->getStyle('A2:A' . ($rowNum - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D2:E' . ($rowNum - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N2:N' . ($rowNum - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('P2:Q' . ($rowNum - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Set column widths explicitly
        $widths = [
            'A' => 6,
            'B' => 18,
            'C' => 25,
            'D' => 12,
            'E' => 20,
            'F' => 10,
            'G' => 18,
            'H' => 35,
            'I' => 18,
            'J' => 18,
            'K' => 20,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 25, // DPJP
            'P' => 15,
            'Q' => 15,
        ];

        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        return Response::streamDownload(function() use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
