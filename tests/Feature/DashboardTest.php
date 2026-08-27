<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_sees_their_dashboard_with_their_client_data(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('client/dashboard')
                ->where('client.id', $client->id));
    }

    public function test_admin_sees_the_administration_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('admin/dashboard'));
    }
}
