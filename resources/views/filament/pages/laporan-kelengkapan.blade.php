<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Filter Form Section -->
        <x-filament::section>
            <div class="flex flex-col gap-2">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filter Laporan</h3>
                <form wire:submit.prevent="submit" class="mt-2">
                    {{ $this->form }}
                </form>
            </div>
        </x-filament::section>
        <!-- Detailed Report Section -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-2">
                        <span class="text-base font-bold text-gray-900 dark:text-white">Laporan Kunjungan Rinci</span>
                    </div>
                    <x-filament::button 
                        wire:click="exportDetailedLaporanXlsx" 
                        icon="heroicon-m-arrow-down-tray" 
                        size="xs" 
                        color="primary"
                        outlined
                    >
                        Unduh Laporan Lengkap (Excel)
                    </x-filament::button>
                </div>
            </x-slot>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full table-auto divide-y divide-gray-200 dark:divide-white/10 text-left min-w-[1500px] laporan-table">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/5">
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-12 text-center">NO</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">RUANGAN</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">NAMA PASIEN</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">CM</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">NIK</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">UMUR</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">STATUS SOSIAL</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">DX</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">DESA</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">KECAMATAN</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">KABUPATEN/KOTA</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">PENDIDIKAN</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">PEKERJAAN</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">TGL MASUK</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">DPJP</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">TGL PULANG</th>
                            <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">CARA PULANG</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                        @php
                            $detailed = $this->getDetailedLaporan();
                        @endphp
                        @forelse($detailed as $index => $row)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-white/5 transition-colors duration-150">
                                <td class="px-3 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300 text-center">{{ $index + 1 }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ $row->poliklinik?->nm_poli ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ $row->pasien?->nm_pasien ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm font-mono text-gray-900 dark:text-white">{{ $row->no_rkm_medis ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->pasien?->no_ktp ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->umurdaftar }} {{ $row->sttsumur }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->penjab?->png_jawab ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white max-w-xs truncate" title="{{ $row->resumeMedis ? '[' . $row->resumeMedis->diagnosa_utama . '] ' . ($row->resumeMedis->diagnosaUtamaPenyakit?->nm_penyakit ?? '') : '-' }}">
                                    @if($row->resumeMedis)
                                        <span class="font-mono bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-1.5 py-0.5 rounded text-xs">
                                            {{ $row->resumeMedis->diagnosa_utama }}
                                        </span>
                                        {{ $row->resumeMedis->diagnosaUtamaPenyakit?->nm_penyakit ?? '' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->pasien?->kelurahan ?? $row->pasien?->desa ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->pasien?->kecamatan ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->pasien?->kabupaten ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->pasien?->pnd ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->pasien?->pekerjaan ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->tgl_registrasi ? $row->tgl_registrasi->format('d/m/Y') : '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->dokter?->nm_dokter ?? '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">{{ $row->resumeMedis && $row->resumeMedis->tgl_keluar ? $row->resumeMedis->tgl_keluar->format('d/m/Y') : '-' }}</td>
                                <td class="px-3 py-3 text-sm text-gray-900 dark:text-white">
                                    @if($row->resumeMedis)
                                        @php
                                            $caraKeluar = $row->resumeMedis->cara_keluar;
                                            $labelMap = [
                                                'dipulangkan'  => 'Dipulangkan',
                                                'dirujuk_rs'   => 'Dirujuk ke RS',
                                                'meninggal'    => 'Meninggal',
                                                'pulang_paksa' => 'Pulang Paksa / APS',
                                            ];
                                            $colorMap = [
                                                'dipulangkan'  => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-400',
                                                'dirujuk_rs'   => 'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-400',
                                                'meninggal'    => 'bg-red-50 text-red-700 dark:bg-red-400/10 dark:text-red-400',
                                                'pulang_paksa' => 'bg-gray-100 text-gray-700 dark:bg-gray-400/10 dark:text-gray-400',
                                            ];
                                            $label = $labelMap[$caraKeluar] ?? ($caraKeluar ?? '-');
                                            $color = $colorMap[$caraKeluar] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-400/10 dark:text-gray-400';
                                        @endphp
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $color }}">
                                            {{ $label }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="px-4 py-10 text-center text-sm text-gray-400 dark:text-gray-500 italic">
                                    Tidak ada data kunjungan untuk periode ini.
                                </td>
                            </tr>
                        @endforelse

                        @if ($detailed->isNotEmpty() && $detailed->count() >= $this->perPage)
                            <tr x-intersect="$wire.loadMore()" wire:key="loader-row-{{ $this->perPage }}" class="border-t border-gray-200 dark:border-white/5">
                                <td colspan="17" class="px-3 py-4 text-center">
                                    <div class="inline-flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="animate-spin h-5 w-5 text-primary-600 dark:text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Memuat data lebih banyak...</span>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
