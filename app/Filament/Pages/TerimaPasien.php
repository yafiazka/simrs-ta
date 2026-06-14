<?php

namespace App\Filament\Pages;

use App\Models\RegPeriksa;
use App\Models\ResumeMedis;
use App\Models\Penyakit;
use App\Models\ResepObat;
use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class TerimaPasien extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = null;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.terima-pasien';

    protected static ?string $slug = 'terima-pasien/{no_rawat}';

    protected static ?string $title = 'Terima Pasien & Pemeriksaan Medis';

    public ?string $no_rawat = null;
    public ?RegPeriksa $record = null;

    public ?array $data = [];

    public function mount(string $no_rawat): void
    {
        $this->no_rawat = str_replace('-', '/', $no_rawat);
        $this->record = RegPeriksa::with(['pasien', 'dokter', 'poliklinik', 'resumeMedis.resepObats'])->where('no_rawat', $this->no_rawat)->firstOrFail();

        // Update status to 'Diperiksa' if it is 'Menunggu' or 'Belum'
        if (in_array($this->record->stts, ['Menunggu', 'Belum'])) {
            $this->record->update(['stts' => 'Diperiksa']);
        }

        $resume = $this->record->resumeMedis;
        if ($resume) {
            $this->form->fill([
                'tensi' => $resume->tensi,
                'tb' => $resume->tb,
                'bb' => $resume->bb,
                'respirasi' => $resume->respirasi,
                'gcs' => $resume->gcs,
                'nadi' => $resume->nadi,
                'suhu' => $resume->suhu,
                'spo2' => $resume->spo2,
                'diagnosa_utama' => $resume->diagnosa_utama,
                'cara_keluar' => $resume->cara_keluar,
                'tgl_keluar' => $resume->tgl_keluar?->toDateString() ?? $this->record->tgl_registrasi?->toDateString() ?? now()->toDateString(),
                'instruksi' => $resume->instruksi,
                'catatan_medis' => $resume->catatan_medis,
                'resep_obat' => $resume->resepObats->map(fn($o) => [
                    'nama_obat' => $o->nama_obat,
                    'jumlah_obat' => $o->jumlah_obat,
                    'aturan_pakai' => $o->aturan_pakai,
                ])->toArray(),
            ]);
        } else {
            $this->form->fill([
                'tensi' => null,
                'tb' => null,
                'bb' => null,
                'respirasi' => null,
                'gcs' => 'E4V5M6',
                'nadi' => null,
                'suhu' => null,
                'spo2' => null,
                'diagnosa_utama' => null,
                'cara_keluar' => 'dipulangkan',
                'tgl_keluar' => $this->record->tgl_registrasi?->toDateString() ?? now()->toDateString(),
                'resep_obat' => [],
                'instruksi' => null,
                'catatan_medis' => null,
            ]);
        }
    }

    public function getTitle(): string
    {
        return 'Pemeriksaan Medis Pasien';
    }

    public function getHeading(): string
    {
        return 'Pemeriksaan Medis: ' . ($this->record?->pasien?->nm_pasien ?? '');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tanda-Tanda Vital / Pemeriksaan Fisik')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('tensi')
                                    ->label('Tekanan Darah (Tensi)')
                                    ->placeholder('Contoh: 120/80')
                                    ->required(),
                                TextInput::make('nadi')
                                    ->label('Nadi')
                                    ->suffix('x/menit')
                                    ->placeholder('Contoh: 80')
                                    ->required(),
                                TextInput::make('suhu')
                                    ->label('Suhu Tubuh')
                                    ->suffix('°C')
                                    ->placeholder('Contoh: 36.5')
                                    ->required(),
                                TextInput::make('spo2')
                                    ->label('Saturasi Oksigen (SpO2)')
                                    ->suffix('%')
                                    ->placeholder('Contoh: 98')
                                    ->required(),
                                TextInput::make('tb')
                                    ->label('Tinggi Badan (TB)')
                                    ->suffix('cm')
                                    ->placeholder('Contoh: 165')
                                    ->required(),
                                TextInput::make('bb')
                                    ->label('Berat Badan (BB)')
                                    ->suffix('kg')
                                    ->placeholder('Contoh: 60')
                                    ->required(),
                                TextInput::make('respirasi')
                                    ->label('Respirasi')
                                    ->suffix('x/menit')
                                    ->placeholder('Contoh: 18')
                                    ->required(),
                                TextInput::make('gcs')
                                    ->label('GCS (E, V, M)')
                                    ->placeholder('Contoh: E4V5M6')
                                    ->default('-'),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 2,
                                'md' => 4,
                            ]),
                        Textarea::make('catatan_medis')
                            ->label('Catatan Medis')
                            ->rows(3),
                    ]),

                Section::make('Diagnosa & Tindakan')
                    ->schema([
                        Select::make('diagnosa_utama')
                            ->label('Diagnosa Utama (ICD-10)')
                            ->required()
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                return Penyakit::query()
                                    ->where('kd_penyakit', 'like', "%{$search}%")
                                    ->orWhere('nm_penyakit', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->get()
                                    ->mapWithKeys(fn ($penyakit) => [$penyakit->kd_penyakit => "[{$penyakit->kd_penyakit}] {$penyakit->nm_penyakit}"])
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn ($value): ?string => ($penyakit = Penyakit::find($value)) ? "[{$penyakit->kd_penyakit}] {$penyakit->nm_penyakit}" : null),
                        
                        Select::make('cara_keluar')
                            ->label('Cara Dipulangkan')
                            ->options([
                                'dipulangkan'  => 'Dipulangkan',
                                'dirujuk_rs'   => 'Dirujuk ke RS',
                                'meninggal'    => 'Meninggal',
                                'pulang_paksa' => 'Pulang Paksa / APS',
                            ])
                            ->required()
                            ->default('dipulangkan'),
                        DatePicker::make('tgl_keluar')
                            ->label('Tanggal Pulang'),
                    ])->columns(3),

                Section::make('Resep Obat & Instruksi Medis')
                    ->schema([
                        Repeater::make('resep_obat')
                            ->label('Resep Obat')
                            ->schema([
                                TextInput::make('nama_obat')
                                    ->required()
                                    ->label('Nama Obat'),
                                TextInput::make('jumlah_obat')
                                    ->required()
                                    ->label('Jumlah')
                                    ->placeholder('Contoh: 10 tablet, 1 strip'),
                                TextInput::make('aturan_pakai')
                                    ->required()
                                    ->placeholder('Contoh: 3x1 tablet')
                                    ->label('Aturan Pakai'),
                            ])
                            ->columns(3)
                            ->default([]),

                        Textarea::make('instruksi')
                            ->label('Instruksi Medis')
                            ->rows(3),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            $this->record->update(['stts' => 'Selesai']);

            $resume = $this->record->resumeMedis;
            if (!$resume) {
                $resume = new ResumeMedis();
                $resume->no_rawat = $this->record->no_rawat;
                $resume->tgl_masuk = $this->record->tgl_registrasi;
            }
            $resume->tgl_keluar = $data['tgl_keluar'] ?? null;
            $resume->kd_dokter = $this->record->kd_dokter;
            $resume->keluhan = $this->record->diagnosa_awal;
            $resume->tensi = $data['tensi'];
            $resume->tb = $data['tb'];
            $resume->bb = $data['bb'];
            $resume->respirasi = $data['respirasi'];
            $resume->gcs = $data['gcs'];
            $resume->nadi = $data['nadi'];
            $resume->suhu = $data['suhu'];
            $resume->spo2 = $data['spo2'];
            $resume->diagnosa_utama = $data['diagnosa_utama'];
            $resume->cara_keluar = $data['cara_keluar'] ?? null;
            $resume->instruksi = $data['instruksi'] ?? null;
            $resume->catatan_medis = $data['catatan_medis'] ?? null;
            
            $resume->pemeriksaan_fisik = '-';
            $resume->diagnosa_masuk = '-';
            $resume->indikasi_rawat_inap = '-';
            $resume->diagnosa_sekunder = '-';
            $resume->tindakan_prosedur = '-';
            $resume->terapi_pulang = '-';
            $resume->alergi_obat = '-';
            $resume->kondisi_pulang = '-';
            $resume->rencana_lanjut = '-';
            $resume->ringkasan_riwayat = '-';
            $resume->hasil_penunjang = '-';
            $resume->save();

            $resume->resepObats()->delete();
            if (!empty($data['resep_obat'])) {
                foreach ($data['resep_obat'] as $resep) {
                    ResepObat::create([
                        'resume_medis_id' => $resume->id,
                        'nama_obat' => $resep['nama_obat'],
                        'jumlah_obat' => $resep['jumlah_obat'],
                        'aturan_pakai' => $resep['aturan_pakai'],
                    ]);
                }
            }
        });

        Notification::make()
            ->title('Pemeriksaan Medis Berhasil Disimpan')
            ->body('Data rekam medis telah disimpan dan status pasien diupdate ke Selesai.')
            ->success()
            ->send();

        $this->redirect($this->getBackUrl());
    }

    public function cancel(): void
    {
        $this->redirect($this->getBackUrl());
    }

    public function getBackUrl(): string
    {
        $kd_poli = $this->record->kd_poli ?? '';
        return match ($kd_poli) {
            'UMUM' => '/admin/poli-umums',
            'GIGI' => '/admin/poli-gigis',
            'KIA' => '/admin/poli-kias',
            'MTBS' => '/admin/poli-mtbs',
            default => '/admin',
        };
    }
}
