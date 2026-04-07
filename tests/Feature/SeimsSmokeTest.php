<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SeimsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeimsSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_is_available(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Login to SEIMS');
    }

    public function test_authenticated_user_can_open_main_modules(): void
    {
        $this->seed(SeimsSeeder::class);

        $user = User::where('email', 'admin@seims.local')->firstOrFail();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Supply and Equipment Inventory Management System');

        $this->actingAs($user)->get('/supplies')->assertOk();
        $this->actingAs($user)->get('/equipment')->assertOk();
        $this->actingAs($user)->get('/settings')->assertOk();
    }
}
