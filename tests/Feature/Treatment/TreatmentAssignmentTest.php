<?php

namespace Tests\Feature\Treatment;

use App\Models\Pet;
use App\Models\PetTreatment;
use App\Models\Procedure;
use App\Models\Service;
use App\Models\Treatment;
use App\Models\TreatmentSession;
use App\Services\TreatmentAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Tests\TestCase;

class TreatmentAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_assignment_freezes_catalog_and_generates_priced_sessions(): void
    {
        $service = Service::factory()->create();
        $procedure = $service->procedures()->create([
            'name' => 'Masotherapy',
            'description' => 'Original description',
            'duration_minutes' => 25,
        ]);
        $treatment = $service->treatments()->create([
            'name' => 'Lumbar therapy',
            'description' => 'Original treatment',
            'estimated_sessions' => 6,
        ]);
        $treatment->procedures()->attach($procedure);
        $pet = Pet::factory()->create();

        $assigned = app(TreatmentAssignmentService::class)->assign($pet, $treatment, [
            'planned_sessions' => 6,
            'default_session_price' => '18000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
            'notes' => null,
        ]);

        $this->assertSame('Lumbar therapy', $assigned->treatment_name);
        $this->assertSame('Masotherapy', $assigned->procedureSnapshots->sole()->procedure_name);
        $this->assertCount(6, $assigned->sessions);
        $this->assertSame([1, 2, 3, 4, 5, 6], $assigned->sessions->pluck('session_number')->all());
        $this->assertSame(['18000.00'], $assigned->sessions->pluck('price')->unique()->values()->all());

        $treatment->update(['name' => 'Changed catalog name']);
        $procedure->update(['name' => 'Changed procedure']);

        $this->assertSame('Lumbar therapy', $assigned->fresh()->treatment_name);
        $this->assertSame('Masotherapy', $assigned->procedureSnapshots()->sole()->procedure_name);
    }

    public function test_cancelling_session_generates_historical_replacement_without_changing_plan(): void
    {
        $assigned = $this->assignedTreatment(6);
        $third = $assigned->sessions()->where('session_number', 3)->firstOrFail();

        app(TreatmentAssignmentService::class)->updateSession($third, [
            'scheduled_at' => null,
            'price' => '18000.00',
            'currency' => 'ARS',
            'status' => 'cancelled',
            'notes' => 'Client unavailable',
        ]);

        $assigned->refresh();
        $this->assertSame(6, $assigned->planned_sessions);
        $this->assertSame('cancelled', $third->fresh()->status);
        $this->assertSame('18000.00', $third->fresh()->price);
        $this->assertSame([1, 2, 3, 4, 5, 6, 7], $assigned->sessions()->orderBy('session_number')->pluck('session_number')->all());
        $this->assertSame('pending', $assigned->sessions()->where('session_number', 7)->value('status'));
    }

    public function test_completed_sessions_drive_status_and_cancelled_sessions_do_not_count(): void
    {
        $assigned = $this->assignedTreatment(2);
        $manager = app(TreatmentAssignmentService::class);

        foreach ($assigned->sessions()->orderBy('session_number')->get() as $session) {
            $manager->updateSession($session, [
                'scheduled_at' => null,
                'price' => $session->price,
                'currency' => 'ARS',
                'status' => 'completed',
                'notes' => null,
            ]);
        }

        $this->assertSame('completed', $assigned->fresh()->status);
        $this->assertSame(2, $assigned->sessions()->where('status', 'completed')->count());
    }

    public function test_resize_adds_and_removes_only_pending_sessions(): void
    {
        $assigned = $this->assignedTreatment(2);
        $manager = app(TreatmentAssignmentService::class);

        $manager->updateConditions($assigned, [
            'planned_sessions' => 4,
            'default_session_price' => '22000.00',
            'currency' => 'ARS',
            'notes' => 'Updated agreement',
        ]);
        $this->assertSame([1, 2, 3, 4], $assigned->sessions()->orderBy('session_number')->pluck('session_number')->all());
        $this->assertSame(['18000.00', '18000.00', '22000.00', '22000.00'], $assigned->sessions()->orderBy('session_number')->pluck('price')->all());
        $this->assertSame('Updated agreement', $assigned->fresh()->notes);

        $manager->updateConditions($assigned, [
            'planned_sessions' => 3,
            'default_session_price' => '23000.00',
            'currency' => 'ARS',
            'notes' => null,
        ]);
        $this->assertSame([1, 2, 3], $assigned->sessions()->orderBy('session_number')->pluck('session_number')->all());
        $this->assertSame(3, $assigned->fresh()->planned_sessions);
    }

    public function test_suspended_treatment_does_not_allow_session_changes_or_generate_replacements(): void
    {
        $assigned = $this->assignedTreatment(1);
        $manager = app(TreatmentAssignmentService::class);
        $manager->changeStatus($assigned, 'suspended');

        $this->expectException(ValidationException::class);
        $session = $assigned->sessions()->sole();
        $manager->updateSession($session, [
            'scheduled_at' => null, 'price' => $session->price, 'currency' => 'ARS',
            'status' => 'cancelled', 'notes' => null,
        ]);
    }

    public function test_assignment_rejects_unavailable_or_invalid_catalog_configuration(): void
    {
        $pet = Pet::factory()->create();
        $manager = app(TreatmentAssignmentService::class);
        $crossServiceTreatment = $this->catalogTreatment(withProcedure: false);
        $crossServiceTreatment->procedures()->attach(Procedure::factory()->create());

        foreach ([
            $this->catalogTreatment(serviceActive: false),
            $this->catalogTreatment(treatmentActive: false),
            $this->catalogTreatment(procedureActive: false),
            $this->catalogTreatment(withProcedure: false),
            $crossServiceTreatment,
        ] as $treatment) {
            try {
                $manager->assign($pet, $treatment, $this->assignmentAttributes());
                $this->fail('An unavailable catalog treatment was assigned.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey('treatment_id', $exception->errors());
            }
        }

        $this->assertDatabaseCount('pet_treatments', 0);
        $this->assertDatabaseCount('treatment_sessions', 0);
        $this->assertDatabaseCount('pet_treatment_procedures', 0);
    }

    public function test_condition_and_session_changes_roll_back_together_when_session_creation_fails(): void
    {
        $assigned = $this->assignedTreatment(1);
        TreatmentSession::creating(function (): void {
            throw new RuntimeException('Simulated session creation failure.');
        });

        try {
            app(TreatmentAssignmentService::class)->updateConditions($assigned, [
                'planned_sessions' => 2,
                'default_session_price' => '25000.00',
                'currency' => 'ARS',
                'notes' => 'Must roll back',
            ]);
            $this->fail('The operation did not fail.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Simulated session creation failure.', $exception->getMessage());
        }

        $assigned->refresh();
        $this->assertSame(1, $assigned->planned_sessions);
        $this->assertSame('18000.00', $assigned->default_session_price);
        $this->assertNull($assigned->notes);
        $this->assertSame(1, $assigned->sessions()->count());
    }

    public function test_final_session_status_can_keep_corrections_but_cannot_transition(): void
    {
        $assigned = $this->assignedTreatment(2);
        $manager = app(TreatmentAssignmentService::class);
        $session = $assigned->sessions()->orderBy('session_number')->firstOrFail();

        $manager->updateSession($session, [
            'scheduled_at' => '2026-09-02 10:30:00',
            'price' => '19000.00',
            'currency' => 'ARS',
            'status' => 'completed',
            'notes' => 'Completed once',
        ]);
        $manager->updateSession($session, [
            'scheduled_at' => '2026-09-02 11:00:00',
            'price' => '19500.00',
            'currency' => 'ARS',
            'status' => 'completed',
            'notes' => 'Corrected note',
        ]);

        $this->assertSame('19500.00', $session->fresh()->price);
        $this->assertSame('Corrected note', $session->fresh()->notes);

        try {
            $manager->updateSession($session, [
                'scheduled_at' => null,
                'price' => '1.00',
                'currency' => 'ARS',
                'status' => 'pending',
                'notes' => 'Forbidden transition',
            ]);
            $this->fail('A completed session changed status.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('status', $exception->errors());
        }

        $this->assertSame('completed', $session->fresh()->status);
        $this->assertSame('19500.00', $session->fresh()->price);
    }

    public function test_cancelled_session_status_is_final_but_its_details_can_be_corrected_while_parent_is_active(): void
    {
        $assigned = $this->assignedTreatment(2);
        $manager = app(TreatmentAssignmentService::class);
        $session = $assigned->sessions()->orderBy('session_number')->firstOrFail();

        $manager->updateSession($session, [
            'scheduled_at' => null,
            'price' => '18000.00',
            'currency' => 'ARS',
            'status' => 'cancelled',
            'notes' => 'Cancelled',
        ]);
        $manager->updateSession($session, [
            'scheduled_at' => '2026-09-03 09:00:00',
            'price' => '17500.00',
            'currency' => 'ARS',
            'status' => 'cancelled',
            'notes' => 'Corrected cancellation',
        ]);

        $this->assertSame('17500.00', $session->fresh()->price);
        $this->assertSame('Corrected cancellation', $session->fresh()->notes);

        $this->expectException(ValidationException::class);
        $manager->updateSession($session, [
            'scheduled_at' => null,
            'price' => '17500.00',
            'currency' => 'ARS',
            'status' => 'completed',
            'notes' => null,
        ]);
    }

    public function test_completed_and_cancelled_treatments_are_final(): void
    {
        $manager = app(TreatmentAssignmentService::class);
        $completed = $this->assignedTreatment(1);
        $session = $completed->sessions()->sole();
        $manager->updateSession($session, [
            'scheduled_at' => null,
            'price' => $session->price,
            'currency' => 'ARS',
            'status' => 'completed',
            'notes' => null,
        ]);

        foreach (['suspended', 'cancelled'] as $status) {
            try {
                $manager->changeStatus($completed, $status);
                $this->fail('A completed treatment changed status.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey('status', $exception->errors());
            }
        }

        try {
            $manager->updateConditions($completed, [
                'planned_sessions' => 2,
                'default_session_price' => '20000.00',
                'currency' => 'ARS',
                'notes' => null,
            ]);
            $this->fail('A completed treatment changed conditions.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('planned_sessions', $exception->errors());
        }

        try {
            $manager->updateSession($session, [
                'scheduled_at' => '2026-09-03 12:00:00',
                'price' => '20000.00',
                'currency' => 'ARS',
                'status' => 'completed',
                'notes' => 'Forbidden correction',
            ]);
            $this->fail('A completed treatment session was modified.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('status', $exception->errors());
        }

        $cancelled = $this->assignedTreatment(1);
        $manager->changeStatus($cancelled, 'cancelled');

        try {
            $manager->changeStatus($cancelled, 'resume');
            $this->fail('A cancelled treatment was reopened.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('status', $exception->errors());
        }

        $this->assertSame('completed', $completed->fresh()->status);
        $this->assertSame('cancelled', $cancelled->fresh()->status);
    }

    private function assignedTreatment(int $plannedSessions): PetTreatment
    {
        $treatment = $this->catalogTreatment(estimatedSessions: $plannedSessions);

        return app(TreatmentAssignmentService::class)->assign(
            Pet::factory()->create(),
            $treatment,
            $this->assignmentAttributes($plannedSessions),
        );
    }

    private function catalogTreatment(
        bool $serviceActive = true,
        bool $treatmentActive = true,
        bool $procedureActive = true,
        bool $withProcedure = true,
        int $estimatedSessions = 1,
    ): Treatment {
        $service = Service::factory()->create(['is_active' => $serviceActive]);
        $treatment = Treatment::factory()->for($service)->create([
            'is_active' => $treatmentActive,
            'estimated_sessions' => $estimatedSessions,
        ]);

        if ($withProcedure) {
            $procedure = Procedure::factory()->for($service)->create(['is_active' => $procedureActive]);
            $treatment->procedures()->attach($procedure);
        }

        return $treatment;
    }

    /** @return array<string, int|string|null> */
    private function assignmentAttributes(int $plannedSessions = 1): array
    {
        return [
            'planned_sessions' => $plannedSessions,
            'default_session_price' => '18000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-09-01',
            'status' => 'pending',
            'notes' => null,
        ];
    }
}
