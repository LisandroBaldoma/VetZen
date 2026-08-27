<?php

namespace Tests\Feature\Client;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_view_own_profile(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->get(route('clients.edit', $client))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('settings/client-profile')
                ->where('client.id', $client->id)
                ->where('client.user_id', $client->user_id));
    }

    public function test_client_can_update_own_profile(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($client->user)
            ->patch(route('clients.update', $client), [
                'phone' => '+54 11 4444 4444',
                'address' => 'Calle 123',
                'city' => 'Buenos Aires',
                'province' => 'Buenos Aires',
                'postal_code' => '1000',
                'document' => '12345678',
                'birth_date' => '1990-01-15',
            ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('clients.edit', $client));

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'phone' => '+54 11 4444 4444',
            'city' => 'Buenos Aires',
            'document' => '12345678',
        ]);
    }

    public function test_client_cannot_view_another_clients_profile(): void
    {
        $client = Client::factory()->create();
        $otherClient = Client::factory()->create();

        $this->actingAs($client->user)
            ->get(route('clients.edit', $otherClient))
            ->assertForbidden();
    }

    public function test_client_cannot_update_another_clients_profile(): void
    {
        $client = Client::factory()->create();
        $otherClient = Client::factory()->create(['phone' => 'original-phone']);

        $this->actingAs($client->user)
            ->patch(route('clients.update', $otherClient), ['phone' => 'changed-phone'])
            ->assertForbidden();

        $this->assertSame('original-phone', $otherClient->fresh()->phone);
    }

    public function test_client_profile_requires_a_valid_phone_and_birth_date(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->from(route('clients.edit', $client))
            ->patch(route('clients.update', $client), [
                'phone' => '',
                'birth_date' => now()->addDay()->toDateString(),
            ])
            ->assertSessionHasErrors(['phone', 'birth_date'])
            ->assertRedirect(route('clients.edit', $client));
    }

    public function test_client_update_ignores_another_users_identifier(): void
    {
        $client = Client::factory()->create();
        $otherUser = User::factory()->create();

        $this->actingAs($client->user)
            ->patch(route('clients.update', $client), [
                'phone' => 'new-phone',
                'client_id' => $otherUser->id,
                'user_id' => $otherUser->id,
                'role' => 'admin',
                'permissions' => ['clients.viewAny'],
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame($client->user_id, $client->fresh()->user_id);
        $this->assertSame('new-phone', $client->fresh()->phone);
        $this->assertTrue($client->user->fresh()->hasRole('client'));
    }
}
