<?php

namespace Tests\Feature\Simon;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimonPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('simon.index'))
            ->assertRedirect(route('login'));
    }

    public function test_client_can_open_the_simon_page(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->get(route('simon.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('simon/index'));
    }

    public function test_admin_can_open_the_simon_page(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('simon.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('simon/index'));
    }

    public function test_user_without_a_supported_role_cannot_open_the_simon_page(): void
    {
        $user = User::factory()->create();
        $user->syncRoles([]);

        $this->actingAs($user)
            ->get(route('simon.index'))
            ->assertForbidden();
    }
}
