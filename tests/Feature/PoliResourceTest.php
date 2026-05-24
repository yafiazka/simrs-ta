<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RegPeriksa;
use App\Models\Poliklinik;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Penjab;
use App\Filament\Pages\TerimaPasien;
use App\Filament\Resources\PoliUmumResource;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PoliResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
        
        $this->user = User::where('username', 'admin')->firstOrFail();
    }

    public function test_poli_umum_list_shows_selesai_patients(): void
    {
        $this->actingAs($this->user);

        // Get existing related records from seeders
        $dokter = Dokter::firstOrFail();
        $pasien = Pasien::firstOrFail();
        $penjab = Penjab::firstOrFail();

        $reg = RegPeriksa::create([
            'no_reg' => '999',
            'no_rawat' => '2026/05/23/999999',
            'tgl_registrasi' => '2026-05-23',
            'kd_dokter' => $dokter->kd_dokter,
            'no_rkm_medis' => $pasien->no_rkm_medis,
            'kd_poli' => 'UMUM',
            'kd_pj' => $penjab->kd_pj,
            'stts' => 'Selesai',
        ]);

        // Get query of PoliUmumResource and make sure the Selesai patient is included
        $query = PoliUmumResource::getEloquentQuery();
        $this->assertTrue($query->where('no_rawat', $reg->no_rawat)->exists());
    }

    public function test_terima_pasien_page_defaults_tgl_keluar_to_tgl_registrasi(): void
    {
        $this->actingAs($this->user);

        $dokter = Dokter::firstOrFail();
        $pasien = Pasien::firstOrFail();
        $penjab = Penjab::firstOrFail();

        $reg = RegPeriksa::create([
            'no_reg' => '999',
            'no_rawat' => '2026/05/23/999999',
            'tgl_registrasi' => '2026-05-23',
            'kd_dokter' => $dokter->kd_dokter,
            'no_rkm_medis' => $pasien->no_rkm_medis,
            'kd_poli' => 'UMUM',
            'kd_pj' => $penjab->kd_pj,
            'stts' => 'Belum',
        ]);

        $routeParam = str_replace('/', '-', $reg->no_rawat);

        $test = Livewire::test(TerimaPasien::class, ['no_rawat' => $routeParam])
            ->assertSuccessful();

        $this->assertStringStartsWith('2026-05-23', $test->get('data.tgl_keluar'));
    }
}
