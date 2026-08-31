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

    public function test_client_can_only_read_own_pet_treatment(): void
    {
        $this->withoutVite();

        $clientA = Client::factory()->create();
        $clientB = Client::factory()->create();
        $petA = Pet::factory()->for($clientA)->create();
        $petB = Pet::factory()->for($clientB)->create();
        $catalogTreatment = Treatment::factory()->create();
        $treatmentB = app(TreatmentAssignmentService::class)->assign($petB, $catalogTreatment, [
            'planned_sessions' => 1, 'default_session_price' => 0,
            'currency' => 'ARS', 'starts_on' => '2026-09-01', 'status' => 'pending',
        ]);

        $this->actingAs($clientA->user)->get(route('pets.treatments.index', $petA))->assertOk();
        $this->actingAs($clientA->user)->get(route('pets.treatments.show', [$petA, $treatmentB]))->assertNotFound();
        $this->actingAs($clientA->user)->post(route('admin.pets.treatments.store', $petA), [])->assertForbidden();
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
