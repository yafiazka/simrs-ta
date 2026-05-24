<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RegPeriksa;
use App\Filament\Pages\LaporanKelengkapan;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanKelengkapanTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::forceCreate([
            'username' => 'admin',
            'full_name' => 'Administrator System',
            'password' => bcrypt('password'),
            'role' => 'Admin',
        ]);
    }

    public function test_laporan_kelengkapan_page_can_be_rendered(): void
    {
        $this->actingAs($this->user);

        $test = Livewire::test(LaporanKelengkapan::class)
            ->assertSuccessful()
            ->assertSet('perPage', 20);

        $this->assertStringStartsWith(now()->startOfMonth()->toDateString(), $test->get('data.dari_tanggal'));
        $this->assertStringStartsWith(now()->toDateString(), $test->get('data.sampai_tanggal'));
    }

    public function test_changing_date_filters_resets_per_page(): void
    {
        $this->actingAs($this->user);

        Livewire::test(LaporanKelengkapan::class)
            ->set('perPage', 40)
            ->set('data.dari_tanggal', now()->subMonth()->toDateString())
            ->assertSet('perPage', 20);
    }

    public function test_load_more_increases_per_page(): void
    {
        $this->actingAs($this->user);

        Livewire::test(LaporanKelengkapan::class)
            ->call('loadMore')
            ->assertSet('perPage', 40);
    }

    public function test_export_laporan_returns_xlsx_file(): void
    {
        $this->actingAs($this->user);

        Livewire::test(LaporanKelengkapan::class)
            ->call('exportDetailedLaporanXlsx')
            ->assertFileDownloaded();
    }
}
