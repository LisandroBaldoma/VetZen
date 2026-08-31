<?php

namespace Tests\Feature\Treatment;

use App\Models\Client;
use App\Models\Pet;
use App\Models\Procedure;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRequestManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_creates_pending_request_for_own_pet_and_active_service(): void
    {
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $service = Service::factory()->create();

        $this->actingAs($client->user)->post(route('pets.service-requests.store', $pet), [
            'service_id' => $service->id, 'notes' => 'Movilidad reducida',
            'status' => 'resolved', 'treatment_id' => 999, 'pet_id' => 999,
        ])->assertRedirect(route('pets.service-requests.index', $pet));

        $request = ServiceRequest::query()->sole();
        $this->assertTrue($request->pet->is($pet));
        $this->assertTrue($request->service->is($service));
        $this->assertSame('pending', $request->status);
        $this->assertNull($request->pet_treatment_id);
    }

    public function test_client_cannot_request_for_other_pet_or_inactive_service(): void
    {
        $clientA = Client::factory()->create();
        $clientB = Client::factory()->create();
        $petB = Pet::factory()->for($clientB)->create();
        $inactive = Service::factory()->inactive()->create();

        $this->actingAs($clientA->user)->post(route('pets.service-requests.store', $petB), ['service_id' => $inactive->id])->assertForbidden();
        $ownPet = Pet::factory()->for($clientA)->create();
        $this->actingAs($clientA->user)->post(route('pets.service-requests.store', $ownPet), ['service_id' => $inactive->id])->assertSessionHasErrors('service_id');
        $this->assertSame(0, ServiceRequest::query()->count());
    }

    public function test_client_cannot_view_request_from_another_pet(): void
    {
        $clientA = Client::factory()->create();
        $clientB = Client::factory()->create();
        $petA = Pet::factory()->for($clientA)->create();
        $petB = Pet::factory()->for($clientB)->create();
        $requestB = ServiceRequest::factory()->for($petB)->create();

        $this->actingAs($clientA->user)->get(route('pets.service-requests.show', [$petA, $requestB]))->assertNotFound();
    }

    public function test_admin_resolves_request_with_compatible_treatment_atomically(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();
        $treatment = Treatment::factory()->for($service)->create(['estimated_sessions' => 3]);
        $treatment->procedures()->attach($procedure);
        $request = ServiceRequest::factory()->for($service)->create();

        $this->actingAs($admin)->post(route('admin.service-requests.resolve', $request), [
            'treatment_id' => $treatment->id,
            'planned_sessions' => 3,
            'default_session_price' => '15000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
            'notes' => null,
        ])->assertRedirect(route('admin.service-requests.show', $request));

        $request->refresh();
        $this->assertSame('resolved', $request->status);
        $this->assertNotNull($request->pet_treatment_id);
        $this->assertCount(3, $request->petTreatment->sessions);
        $this->assertTrue($request->petTreatment->pet->is($request->pet));
    }

    public function test_admin_cannot_resolve_with_treatment_from_other_service_or_resolve_twice(): void
    {
        $admin = User::factory()->admin()->create();
        $request = ServiceRequest::factory()->create();
        $other = Treatment::factory()->create();

        $payload = ['treatment_id' => $other->id, 'planned_sessions' => 1, 'default_session_price' => '0', 'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending'];
        $this->actingAs($admin)->post(route('admin.service-requests.resolve', $request), $payload)->assertSessionHasErrors('treatment_id');
        $this->assertSame('pending', $request->fresh()->status);
    }
}
