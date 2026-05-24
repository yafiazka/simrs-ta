<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Dokter;
use App\Filament\Resources\DokterResource;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DokterResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed();
        $this->user = User::where('username', 'admin')->firstOrFail();
    }

    public function test_dokter_resource_renders_fields_and_columns(): void
    {
        $this->actingAs($this->user);

        // Create a doctor
        $dokter = Dokter::create([
            'kd_dokter' => 'D999',
            'nm_dokter' => 'Dr. Test NIK SIP',
            'spesialis' => 'Kandungan',
            'no_telp' => '0899999999',
            'nik' => '9999999999999999',
            'sip' => 'SIP/999/999',
            'status' => '1',
        ]);

        // Get index page
        $response = $this->get(DokterResource::getUrl('index'));
        $response->assertSuccessful();

        // Get edit page
        $response = $this->get(DokterResource::getUrl('edit', ['record' => $dokter]));
        $response->assertSuccessful();

        $response->assertSee('Dr. Test NIK SIP');
    }
}
