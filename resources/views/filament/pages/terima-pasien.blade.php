<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Patient Summary Card -->
        <x-filament::section>
            <x-slot name="heading">
                Identitas & Informasi Pendaftaran Pasien
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="flex flex-col">
                    <span class="text-sm font-medium pasien-info-label">Nama Pasien</span>
                    <span class="mt-1 text-base font-semibold pasien-info-value">{{ $record->pasien?->nm_pasien ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium pasien-info-label">No. Rekam Medis</span>
                    <span class="mt-1 text-base font-semibold pasien-info-value">{{ $record->no_rkm_medis ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium pasien-info-label">No. Rawat</span>
                    <span class="mt-1 text-base font-semibold pasien-info-value">{{ $record->no_rawat ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium pasien-info-label">Poliklinik Tujuan</span>
                    <span class="mt-1 text-base font-semibold pasien-info-value">{{ $record->poliklinik?->nm_poli ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium pasien-info-label">Dokter DPJP</span>
                    <span class="mt-1 text-base font-semibold pasien-info-value">{{ $record->dokter?->nm_dokter ?? '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium pasien-info-label">Cara Bayar</span>
                    <span class="mt-1 text-base font-semibold pasien-info-value">{{ $record->penjab?->png_jawab ?? '-' }}</span>
                </div>
                <div class="flex flex-col lg:col-span-2">
                    <span class="text-sm font-medium pasien-info-label">Keluhan / Diagnosa Awal</span>
                    <span class="mt-1 text-sm p-3 rounded-lg border pasien-info-box">{{ $record->diagnosa_awal ?: '-' }}</span>
                </div>
            </div>
        </x-filament::section>

        <!-- Main Examination Form -->
        <form wire:submit.prevent="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex items-center gap-x-3 justify-end">
                <x-filament::button type="submit" color="success" size="lg" icon="heroicon-m-check">
                    Simpan Pemeriksaan
                </x-filament::button>
                <x-filament::button wire:click="cancel" color="gray" outlined size="lg">
                    Kembali
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
