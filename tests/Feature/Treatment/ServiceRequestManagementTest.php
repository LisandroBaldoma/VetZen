<?php

namespace Tests\Feature\Treatment;

use App\Models\Client;
use App\Models\Pet;
use App\Models\PetTreatment;
use App\Models\Procedure;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Treatment;
use App\Models\TreatmentSession;
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

    public function test_admin_can_view_administrative_service_request_list_and_detail(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $serviceRequest = ServiceRequest::factory()->create();

        $this->actingAs($admin)->get(route('admin.service-requests.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.service-requests.show', $serviceRequest))->assertOk();
    }

    public function test_client_owner_cannot_view_administrative_service_request_detail(): void
    {
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $serviceRequest = ServiceRequest::factory()->for($pet)->create();

        $this->actingAs($client->user)
            ->get(route('admin.service-requests.show', $serviceRequest))
            ->assertForbidden();
    }

    public function test_client_owner_can_still_view_service_request_through_client_route(): void
    {
        $this->withoutVite();

        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $serviceRequest = ServiceRequest::factory()->for($pet)->create();

        $this->actingAs($client->user)
            ->get(route('pets.service-requests.show', [$pet, $serviceRequest]))
            ->assertOk();
    }

    public function test_guest_is_redirected_from_administrative_service_request_routes(): void
    {
        $serviceRequest = ServiceRequest::factory()->create();

        $this->get(route('admin.service-requests.index'))->assertRedirect(route('login'));
        $this->get(route('admin.service-requests.show', $serviceRequest))->assertRedirect(route('login'));
    }

    public function test_admin_resolves_request_with_compatible_treatment_atomically(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();
        $treatment = Treatment::factory()->for($service)->create(['estimated_sessions' => 3]);
        $treatment->procedures()->attach($procedure);
        $request = ServiceRequest::factory()->for($service)->create();

        $response = $this->actingAs($admin)->post(route('admin.service-requests.resolve', $request), [
            'treatment_id' => $treatment->id,
            'planned_sessions' => 3,
            'default_session_price' => '15000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
            'notes' => null,
        ]);

        $request->refresh();
        $response->assertRedirect(route('admin.pets.treatments.show', [$request->pet, $request->petTreatment]));
        $this->assertSame('resolved', $request->status);
        $this->assertNotNull($request->pet_treatment_id);
        $this->assertCount(3, $request->petTreatment->sessions);
        $this->assertTrue($request->petTreatment->pet->is($request->pet));
    }

    public function test_admin_cannot_resolve_with_treatment_from_other_service(): void
    {
        $admin = User::factory()->admin()->create();
        $request = ServiceRequest::factory()->create();
        $other = Treatment::factory()->create();

        $payload = ['treatment_id' => $other->id, 'planned_sessions' => 1, 'default_session_price' => '0', 'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending'];
        $this->actingAs($admin)->post(route('admin.service-requests.resolve', $request), $payload)->assertSessionHasErrors('treatment_id');
        $this->assertSame('pending', $request->fresh()->status);
    }

    public function test_admin_list_supports_search_service_status_and_rendered_pagination_with_minimal_props(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $targetClient = Client::factory()->create();
        $targetPet = Pet::factory()->for($targetClient)->create(['name' => 'Mora']);
        $targetService = Service::factory()->create(['name' => 'Fisioterapia']);
        ServiceRequest::factory()->count(16)->create();
        ServiceRequest::factory()->for($targetPet)->for($targetService)->create(['status' => 'pending']);

        $this->actingAs($admin)->get(route('admin.service-requests.index', [
            'search' => 'Mora',
            'service' => $targetService->id,
            'status' => 'pending',
        ]))->assertOk()->assertInertia(fn ($page) => $page
            ->component('admin/service-requests/index')
            ->where('filters.search', 'Mora')
            ->where('filters.service', (string) $targetService->id)
            ->where('filters.status', 'pending')
            ->has('requests.data', 1)
            ->where('requests.data.0.pet.name', 'Mora')
            ->where('requests.data.0.service.name', 'Fisioterapia')
            ->has('requests.data.0.responsible', 2)
            ->missing('requests.data.0.notes')
            ->missing('requests.data.0.pet.client_id'));

        $this->actingAs($admin)->get(route('admin.service-requests.index'))
            ->assertInertia(fn ($page) => $page
                ->where('requests.last_page', 2)
                ->has('requests.links'));
    }

    public function test_admin_can_cancel_only_a_pending_request_without_a_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $pending = ServiceRequest::factory()->create();

        $this->actingAs($admin)
            ->from(route('admin.service-requests.show', $pending))
            ->patch(route('admin.service-requests.cancel', $pending), ['reason' => 'ignored'])
            ->assertRedirect(route('admin.service-requests.show', $pending))
            ->assertSessionHasNoErrors();

        $this->assertSame('cancelled', $pending->fresh()->status);
        $this->assertNull($pending->fresh()->getAttribute('reason'));

        $this->actingAs($admin)
            ->patch(route('admin.service-requests.cancel', $pending))
            ->assertSessionHasErrors('status');
    }

    public function test_cancelled_request_cannot_be_resolved(): void
    {
        $admin = User::factory()->admin()->create();
        [$request, $treatment] = $this->requestWithCompatibleTreatment(['status' => 'cancelled']);

        $this->actingAs($admin)
            ->post(route('admin.service-requests.resolve', $request), $this->resolutionPayload($treatment))
            ->assertSessionHasErrors('status');

        $this->assertSame(0, PetTreatment::query()->count());
        $this->assertSame(0, TreatmentSession::query()->count());
    }

    public function test_resolving_the_same_request_twice_does_not_duplicate_treatment_or_sessions(): void
    {
        $admin = User::factory()->admin()->create();
        [$request, $treatment] = $this->requestWithCompatibleTreatment();
        $payload = $this->resolutionPayload($treatment);

        $this->actingAs($admin)->post(route('admin.service-requests.resolve', $request), $payload)->assertSessionHasNoErrors();
        $this->actingAs($admin)->post(route('admin.service-requests.resolve', $request), $payload)->assertSessionHasErrors('status');

        $this->assertSame(1, PetTreatment::query()->count());
        $this->assertSame(2, TreatmentSession::query()->count());
    }

    public function test_pending_request_cannot_be_resolved_while_its_service_is_inactive(): void
    {
        $admin = User::factory()->admin()->create();
        [$request, $treatment] = $this->requestWithCompatibleTreatment();
        $request->service->update(['is_active' => false]);

        $this->actingAs($admin)
            ->post(route('admin.service-requests.resolve', $request), $this->resolutionPayload($treatment))
            ->assertSessionHasErrors('service');

        $this->assertSame('pending', $request->fresh()->status);
        $this->assertNull($request->fresh()->pet_treatment_id);
        $this->assertSame(0, PetTreatment::query()->count());
    }

    public function test_client_cannot_resolve_or_cancel_even_an_owned_request(): void
    {
        $client = Client::factory()->create();
        $request = ServiceRequest::factory()->for(Pet::factory()->for($client))->create();

        $this->actingAs($client->user)->post(route('admin.service-requests.resolve', $request), [])->assertForbidden();
        $this->actingAs($client->user)->patch(route('admin.service-requests.cancel', $request))->assertForbidden();
    }

    public function test_client_pages_receive_localized_context_with_minimal_request_props_and_service_preselection(): void
    {
        $this->withoutVite();

        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $service = Service::factory()->create();
        $serviceRequest = ServiceRequest::factory()->for($pet)->for($service)->create();

        $this->actingAs($client->user)->get(route('pets.service-requests.index', $pet))
            ->assertInertia(fn ($page) => $page
                ->has('pet', 7)
                ->missing('pet.client_id')
                ->missing('pet.created_at')
                ->has('requests', 1)
                ->missing('requests.0.notes')
                ->missing('requests.0.pet_id'));

        $this->actingAs($client->user)->get(route('pets.service-requests.create', [$pet, 'service' => $service->id]))
            ->assertInertia(fn ($page) => $page
                ->where('selectedServiceId', $service->id)
                ->where('services.0.id', $service->id)
                ->missing('services.0.is_active'));

        $this->actingAs($client->user)->get(route('pets.service-requests.show', [$pet, $serviceRequest]))
            ->assertInertia(fn ($page) => $page
                ->where('serviceRequest.status', 'pending')
                ->missing('serviceRequest.pet_id')
                ->missing('serviceRequest.service_id'));
    }

    /** @return array{ServiceRequest, Treatment} */
    private function requestWithCompatibleTreatment(array $requestAttributes = []): array
    {
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();
        $treatment = Treatment::factory()->for($service)->create(['estimated_sessions' => 2]);
        $treatment->procedures()->attach($procedure);

        return [
            ServiceRequest::factory()->for($service)->create($requestAttributes),
            $treatment,
        ];
    }

    /** @return array<string, int|string> */
    private function resolutionPayload(Treatment $treatment): array
    {
        return [
            'treatment_id' => $treatment->id,
            'planned_sessions' => 2,
            'default_session_price' => '15000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
        ];
    }
}
