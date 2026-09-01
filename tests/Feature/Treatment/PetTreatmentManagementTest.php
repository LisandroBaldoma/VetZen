<?php

namespace Tests\Feature\Treatment;

use App\Models\Client;
use App\Models\Pet;
use App\Models\Procedure;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\User;
use App\Services\TreatmentAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetTreatmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_assigns_treatment_directly_to_pet(): void
    {
        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();
        $service = Service::factory()->create();
        $procedure = Procedure::factory()->for($service)->create();
        $treatment = Treatment::factory()->for($service)->create();
        $treatment->procedures()->attach($procedure);

        $response = $this->actingAs($admin)->post(route('admin.pets.treatments.store', $pet), [
            'treatment_id' => $treatment->id,
            'planned_sessions' => 3,
            'default_session_price' => '12000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
        ]);

        $assigned = $pet->treatments()->sole();
        $response->assertRedirect(route('admin.pets.treatments.show', [$pet, $assigned]));
        $this->assertCount(3, $assigned->sessions);
        $this->assertSame($procedure->name, $assigned->procedureSnapshots()->sole()->procedure_name);
    }

    public function test_admin_can_view_administrative_pet_treatment_list_and_detail(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();
        $catalogTreatment = Treatment::factory()->create();
        $petTreatment = app(TreatmentAssignmentService::class)->assign($pet, $catalogTreatment, [
            'planned_sessions' => 1, 'default_session_price' => 0,
            'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending',
        ]);

        $this->actingAs($admin)->get(route('admin.pets.treatments.index', $pet))->assertOk();
        $this->actingAs($admin)->get(route('admin.pets.treatments.show', [$pet, $petTreatment]))->assertOk();
    }

    public function test_client_cannot_view_treatments_through_administrative_routes(): void
    {
        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $petTreatment = app(TreatmentAssignmentService::class)->assign($pet, Treatment::factory()->create(), [
            'planned_sessions' => 1, 'default_session_price' => 0,
            'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending',
        ]);

        $this->actingAs($client->user)->get(route('admin.pets.treatments.index', $pet))->assertForbidden();
        $this->actingAs($client->user)->get(route('admin.pets.treatments.show', [$pet, $petTreatment]))->assertForbidden();
    }

    public function test_client_cannot_view_another_pets_treatments(): void
    {
        $client = Client::factory()->create();
        $otherPet = Pet::factory()->create();

        $this->actingAs($client->user)->get(route('pets.treatments.index', $otherPet))->assertForbidden();
        $this->actingAs($client->user)->get(route('admin.pets.treatments.index', $otherPet))->assertForbidden();
    }

    public function test_client_can_view_own_treatments_through_client_routes(): void
    {
        $this->withoutVite();

        $client = Client::factory()->create();
        $pet = Pet::factory()->for($client)->create();
        $petTreatment = app(TreatmentAssignmentService::class)->assign($pet, Treatment::factory()->create(), [
            'planned_sessions' => 1, 'default_session_price' => 0,
            'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending',
        ]);

        $this->actingAs($client->user)->get(route('pets.treatments.index', $pet))->assertOk();
        $this->actingAs($client->user)->get(route('pets.treatments.show', [$pet, $petTreatment]))->assertOk();
    }

    public function test_nested_treatment_route_rejects_treatment_from_another_pet(): void
    {
        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();
        $otherPet = Pet::factory()->create();
        $otherTreatment = app(TreatmentAssignmentService::class)->assign($otherPet, Treatment::factory()->create(), [
            'planned_sessions' => 1, 'default_session_price' => 0,
            'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pets.treatments.show', [$pet, $otherTreatment]))
            ->assertNotFound();
    }

    public function test_guest_is_redirected_from_administrative_treatment_routes(): void
    {
        $pet = Pet::factory()->create();
        $petTreatment = app(TreatmentAssignmentService::class)->assign($pet, Treatment::factory()->create(), [
            'planned_sessions' => 1, 'default_session_price' => 0,
            'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending',
        ]);

        $this->get(route('admin.pets.treatments.index', $pet))->assertRedirect(route('login'));
        $this->get(route('admin.pets.treatments.show', [$pet, $petTreatment]))->assertRedirect(route('login'));
    }

    public function test_admin_session_update_enforces_replacement_rule(): void
    {
        $admin = User::factory()->admin()->create();
        $pet = Pet::factory()->create();
        $catalogTreatment = Treatment::factory()->create();
        $assigned = app(TreatmentAssignmentService::class)->assign($pet, $catalogTreatment, [
            'planned_sessions' => 1, 'default_session_price' => '5000',
            'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending',
        ]);
        $session = $assigned->sessions()->sole();

        $this->actingAs($admin)->patch(route('admin.treatment-sessions.update', $session), [
            'scheduled_at' => null, 'price' => '5000', 'currency' => 'ARS', 'status' => 'cancelled', 'notes' => null,
        ])->assertRedirect();

        $this->assertSame([1, 2], $assigned->sessions()->orderBy('session_number')->pluck('session_number')->all());
        $this->assertSame('cancelled', $session->fresh()->status);
    }
}
