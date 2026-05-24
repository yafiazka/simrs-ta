<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ResumeMedis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResumeMedisPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_resume_medis_pdf_print_route_works(): void
    {
        // Seed the database first to get realistic data and users
        $this->seed();

        $user = User::where('username', 'admin')->firstOrFail();
        $this->actingAs($user);

        // Get the first seeded ResumeMedis record
        $resume = ResumeMedis::firstOrFail();

        $response = $this->get(route('resume-medis.print', ['id' => $resume->id]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
