<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentScheduleDate;
use App\Models\Client;
use App\Models\ClinicalRecord;
use App\Models\Pet;
use App\Models\Procedure;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Treatment;
use App\Models\TreatmentSession;
use App\Models\User;
use App\Services\TreatmentAssignmentService;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CarbonImmutable::setTestNow('2026-10-09 12:00:00 UTC');
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();

        parent::tearDown();
    }

    public function test_client_can_request_a_published_appointment(): void
    {
        [$user, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 45]);
        $scheduleDate = $this->scheduleDate($service, 2);

        $this->actingAs($user)->post(route('appointments.store'), $this->payload($pet, $service, $scheduleDate, '10:00'))
            ->assertRedirect(route('appointments.create'));

        $this->assertDatabaseHas('appointments', [
            'pet_id' => $pet->id,
            'service_id' => $service->id,
            'appointment_schedule_date_id' => $scheduleDate->id,
            'requested_by' => $user->id,
            'service_name' => $service->name,
            'duration_minutes' => 45,
            'status' => 'pending',
            'starts_at' => '2026-10-13 13:00:00',
            'ends_at' => '2026-10-13 13:45:00',
        ]);
    }

    public function test_client_cannot_request_an_appointment_for_another_clients_pet(): void
    {
        [$user] = $this->clientWithPet();
        [, $otherPet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 1);

        $this->actingAs($user)->post($this->route(), $this->payload($otherPet, $service, $scheduleDate, '10:00'))
            ->assertNotFound();

        $this->assertDatabaseCount('appointments', 0);
    }

    public function test_capacity_applies_to_overlapping_intervals_but_allows_contiguous_appointments(): void
    {
        [$user, $pet] = $this->clientWithPet();
        [, $otherPet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 1, ['10:00', '10:30']);

        Appointment::query()->forceCreate([
            'pet_id' => $otherPet->id,
            'service_id' => $service->id,
            'appointment_schedule_date_id' => $scheduleDate->id,
            'requested_by' => $otherPet->client->user_id,
            'service_name' => $service->name,
            'duration_minutes' => 30,
            'starts_at' => '2026-10-13 13:00:00',
            'ends_at' => '2026-10-13 13:30:00',
            'status' => 'approved',
        ]);

        $this->actingAs($user)->post($this->route(), $this->payload($pet, $service, $scheduleDate, '10:00'))
            ->assertSessionHasErrors('starts_at');
        $this->actingAs($user)->post($this->route(), $this->payload($pet, $service, $scheduleDate, '10:30'))
            ->assertRedirect(route('appointments.create'));
    }

    public function test_client_cannot_overlap_their_pets_own_appointment(): void
    {
        [$user, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 60]);
        $scheduleDate = $this->scheduleDate($service, 2, ['10:00', '10:30']);

        $this->actingAs($user)->post($this->route(), $this->payload($pet, $service, $scheduleDate, '10:00'))
            ->assertRedirect();
        $this->actingAs($user)->post($this->route(), $this->payload($pet, $service, $scheduleDate, '10:30'))
            ->assertSessionHasErrors('starts_at');
    }

    public function test_client_sees_only_their_appointments_in_upcoming_and_history(): void
    {
        $this->withoutVite();
        [$user, $pet] = $this->clientWithPet();
        [, $otherPet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);
        $ownAppointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');
        $this->appointment($otherPet, $service, $scheduleDate, '2026-10-13 13:30:00', 'approved');

        $this->actingAs($user)->get(route('appointments.index'))
            ->assertInertia(fn ($page) => $page
                ->component('appointments/index')
                ->has('upcoming', 1)
                ->where('upcoming.0.id', $ownAppointment->id)
                ->has('history', 0));
    }

    public function test_client_cannot_view_another_clients_appointment(): void
    {
        [$user] = $this->clientWithPet();
        [, $otherPet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);
        $appointment = $this->appointment($otherPet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');

        $this->actingAs($user)->get(route('appointments.show', $appointment))->assertForbidden();
    }

    public function test_admin_can_approve_a_pending_appointment_and_history_records_the_actor(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);
        $appointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');

        $this->actingAs($admin)->patch(route('admin.appointments.status.update', $appointment), ['status' => 'approved'])
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'status' => 'approved']);
        $this->assertDatabaseHas('appointment_status_histories', [
            'appointment_id' => $appointment->id,
            'changed_by' => $admin->id,
            'from_status' => 'pending',
            'to_status' => 'approved',
            'reason' => null,
        ]);
    }

    public function test_transition_matrix_and_admin_authorization_are_enforced(): void
    {
        [$client, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);
        $appointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');
        $admin = User::factory()->admin()->create();

        $this->actingAs($client)->patch(route('admin.appointments.status.update', $appointment), ['status' => 'cancelled', 'reason' => 'No puedo asistir'])
            ->assertForbidden();
        $this->actingAs($admin)->patch(route('admin.appointments.status.update', $appointment), ['status' => 'attended'])
            ->assertSessionHasErrors('status');
        $this->actingAs($admin)->patch(route('admin.appointments.status.update', $appointment), ['status' => 'cancelled'])
            ->assertSessionHasErrors('reason');

        $this->assertDatabaseCount('appointment_status_histories', 0);
        $this->assertSame('pending', $appointment->refresh()->status);
    }

    public function test_attending_an_approved_appointment_creates_a_persistent_clinical_task(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 1);
        $appointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'approved');

        $this->actingAs($admin)->patch(route('admin.appointments.status.update', $appointment), ['status' => 'attended'])
            ->assertRedirect();

        $this->assertDatabaseHas('appointment_clinical_tasks', ['appointment_id' => $appointment->id, 'clinical_record_id' => null, 'resolved_at' => null]);
    }

    public function test_admin_cannot_link_an_incompatible_service_request(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 1);
        $appointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');
        $request = ServiceRequest::factory()->for($pet)->for(Service::factory())->create();

        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $appointment), [
            'service_request_id' => $request->id,
        ])->assertSessionHasErrors('service_request_id');

        $this->assertNull($appointment->refresh()->service_request_id);
        $this->assertDatabaseCount('appointment_link_histories', 0);
    }

    public function test_admin_cannot_link_an_incompatible_treatment_session(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 1);
        $appointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');
        $session = $this->sessionFor(Pet::factory()->create(), Service::factory()->create());

        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $appointment), [
            'treatment_session_id' => $session->id,
        ])->assertSessionHasErrors('treatment_session_id');

        $this->assertNull($appointment->refresh()->treatment_session_id);
        $this->assertDatabaseCount('appointment_link_histories', 0);
    }

    public function test_only_one_active_appointment_can_link_a_service_request_or_treatment_session(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2, ['10:00', '11:00']);
        $first = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');
        $second = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 14:00:00', 'approved');
        $request = ServiceRequest::factory()->for($pet)->for($service)->create();
        $session = $this->sessionFor($pet, $service);

        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $first), [
            'service_request_id' => $request->id,
            'treatment_session_id' => $session->id,
        ])->assertRedirect();
        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $second), [
            'service_request_id' => $request->id,
        ])->assertSessionHasErrors('service_request_id');
        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $second), [
            'treatment_session_id' => $session->id,
        ])->assertSessionHasErrors('treatment_session_id');

        $this->assertNull($second->refresh()->service_request_id);
        $this->assertNull($second->treatment_session_id);
    }

    public function test_unresolved_attended_task_blocks_linking_its_session_to_a_new_appointment(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2, ['10:00', '11:00']);
        $attended = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'approved');
        $newAppointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 14:00:00', 'approved');
        $session = $this->sessionFor($pet, $service);

        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $attended), ['treatment_session_id' => $session->id])->assertRedirect();
        $this->actingAs($admin)->patch(route('admin.appointments.status.update', $attended), ['status' => 'attended'])->assertRedirect();
        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $newAppointment), ['treatment_session_id' => $session->id])
            ->assertSessionHasErrors('treatment_session_id');

        $this->assertNull($newAppointment->refresh()->treatment_session_id);
    }

    public function test_explicit_record_resolves_task_only_after_linked_session_is_completed_and_locks_links(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 1);
        $appointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'approved');
        $session = $this->sessionFor($pet, $service);

        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $appointment), ['treatment_session_id' => $session->id])->assertRedirect();
        $this->actingAs($admin)->patch(route('admin.appointments.status.update', $appointment), ['status' => 'attended'])->assertRedirect();
        $this->actingAs($admin)->post(route('admin.pets.medical-records.store', $pet), $this->clinicalRecordPayload($session->id))->assertRedirect();
        $this->assertDatabaseHas('appointment_clinical_tasks', ['appointment_id' => $appointment->id, 'clinical_record_id' => null, 'resolved_at' => null]);

        $this->actingAs($admin)->patch(route('admin.treatment-sessions.update', $session), [
            'scheduled_at' => null,
            'price' => $session->price,
            'currency' => $session->currency,
            'status' => 'completed',
            'notes' => null,
        ])->assertRedirect();

        $record = ClinicalRecord::query()->where('treatment_session_id', $session->id)->sole();
        $this->assertDatabaseHas('appointment_clinical_tasks', ['appointment_id' => $appointment->id, 'clinical_record_id' => $record->id]);
        $this->actingAs($admin)->patch(route('admin.appointments.links.update', $appointment), ['treatment_session_id' => null])
            ->assertSessionHasErrors('links');
    }

    public function test_admin_pending_queue_excludes_already_processed_appointments(): void
    {
        $this->withoutVite();
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);
        $pending = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'pending');
        $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:30:00', 'approved');

        $this->actingAs($admin)->get(route('admin.appointments.index'))
            ->assertInertia(fn ($page) => $page
                ->component('admin/appointments/index')
                ->has('appointments.data', 1)
                ->where('appointments.data.0.id', $pending->id));
    }

    public function test_admin_can_view_appointments_in_their_published_weekly_schedule(): void
    {
        $this->withoutVite();
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);
        $appointment = $this->appointment($pet, $service, $scheduleDate, '2026-10-13 13:00:00', 'approved');

        $this->actingAs($admin)->get(route('admin.appointments.schedule', ['date' => '2026-10-13']))
            ->assertInertia(fn ($page) => $page
                ->component('admin/appointments/schedule')
                ->where('weekStart', '2026-10-12')
                ->where('week.1.appointments.0.id', $appointment->id));
    }

    public function test_admin_can_create_an_approved_appointment_without_client_lead_time(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);

        $this->actingAs($admin)->post(route('admin.appointments.store'), $this->payload($pet, $service, $scheduleDate, '10:00'))
            ->assertRedirect();

        $this->assertDatabaseHas('appointments', [
            'pet_id' => $pet->id,
            'requested_by' => $admin->id,
            'status' => 'approved',
        ]);
    }

    public function test_client_can_cancel_at_the_exact_twenty_four_hour_limit_but_not_after_it(): void
    {
        [$user, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = $this->scheduleDate($service, 2);
        $cancellable = $this->appointment($pet, $service, $scheduleDate, '2026-10-10 12:00:00', 'pending');
        $late = $this->appointment($pet, $service, $scheduleDate, '2026-10-10 11:59:00', 'approved');

        $this->actingAs($user)->patch(route('appointments.cancellation', $cancellable))
            ->assertRedirect(route('appointments.show', $cancellable));
        $this->actingAs($user)->patch(route('appointments.cancellation', $late))
            ->assertSessionHasErrors('status');

        $this->assertSame('cancelled', $cancellable->refresh()->status);
        $this->assertSame('approved', $late->refresh()->status);
        $this->assertDatabaseHas('appointment_status_histories', [
            'appointment_id' => $cancellable->id,
            'changed_by' => $user->id,
            'to_status' => 'cancelled',
        ]);
    }

    public function test_admin_can_reschedule_an_approved_appointment_and_preserves_interval_history(): void
    {
        $admin = User::factory()->admin()->create();
        [, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $originalDate = $this->scheduleDate($service, 2);
        $newDate = AppointmentScheduleDate::query()->create(['date' => '2026-10-14', 'capacity' => 2]);
        $newDate->blocks()->create(['starts_at' => '10:00', 'ends_at' => '12:00']);
        $newDate->starts()->create(['starts_at' => '10:00']);
        $newDate->services()->attach($service);
        $appointment = $this->appointment($pet, $service, $originalDate, '2026-10-13 13:00:00', 'approved');

        $this->actingAs($admin)->patch(route('admin.appointments.reschedule', $appointment), [
            'schedule_date_id' => $newDate->id,
            'starts_at' => '10:00',
        ])->assertRedirect();

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'appointment_schedule_date_id' => $newDate->id,
            'starts_at' => '2026-10-14 13:00:00',
            'ends_at' => '2026-10-14 13:30:00',
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('appointment_reschedule_histories', [
            'appointment_id' => $appointment->id,
            'changed_by' => $admin->id,
            'old_starts_at' => '2026-10-13 13:00:00',
            'new_starts_at' => '2026-10-14 13:00:00',
        ]);
    }

    public function test_client_can_reschedule_an_approved_appointment_to_pending_at_the_exact_lead_time(): void
    {
        [$user, $pet] = $this->clientWithPet();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $originalDate = $this->scheduleDate($service, 2);
        $newDate = AppointmentScheduleDate::query()->create(['date' => '2026-10-14', 'capacity' => 2]);
        $newDate->blocks()->create(['starts_at' => '10:00', 'ends_at' => '12:00']);
        $newDate->starts()->create(['starts_at' => '10:00']);
        $newDate->services()->attach($service);
        $appointment = $this->appointment($pet, $service, $originalDate, '2026-10-10 12:00:00', 'approved');

        $this->actingAs($user)->patch(route('appointments.reschedule', $appointment), [
            'schedule_date_id' => $newDate->id,
            'starts_at' => '10:00',
        ])->assertRedirect(route('appointments.show', $appointment));

        $this->assertDatabaseHas('appointments', ['id' => $appointment->id, 'status' => 'pending', 'appointment_schedule_date_id' => $newDate->id]);
        $this->assertDatabaseHas('appointment_reschedule_histories', ['appointment_id' => $appointment->id, 'changed_by' => $user->id]);
    }

    /** @return array{User, Pet} */
    private function clientWithPet(): array
    {
        $user = User::factory()->create();
        $client = Client::factory()->for($user)->create();

        return [$user, Pet::factory()->for($client)->create()];
    }

    private function scheduleDate(Service $service, int $capacity, array $starts = ['10:00']): AppointmentScheduleDate
    {
        $scheduleDate = AppointmentScheduleDate::query()->create(['date' => '2026-10-13', 'capacity' => $capacity]);
        $scheduleDate->blocks()->create(['starts_at' => '10:00', 'ends_at' => '12:00']);
        foreach ($starts as $start) {
            $scheduleDate->starts()->create(['starts_at' => $start]);
        }
        $scheduleDate->services()->attach($service);

        return $scheduleDate;
    }

    /** @return array<string, int|string> */
    private function payload(Pet $pet, Service $service, AppointmentScheduleDate $scheduleDate, string $start): array
    {
        return [
            'pet_id' => $pet->id,
            'service_id' => $service->id,
            'schedule_date_id' => $scheduleDate->id,
            'starts_at' => $start,
        ];
    }

    private function route(): string
    {
        return route('appointments.store');
    }

    private function appointment(Pet $pet, Service $service, AppointmentScheduleDate $scheduleDate, string $startsAt, string $status): Appointment
    {
        return Appointment::query()->forceCreate([
            'pet_id' => $pet->id,
            'service_id' => $service->id,
            'appointment_schedule_date_id' => $scheduleDate->id,
            'requested_by' => $pet->client->user_id,
            'service_name' => $service->name,
            'duration_minutes' => 30,
            'starts_at' => $startsAt,
            'ends_at' => CarbonImmutable::parse($startsAt)->addMinutes(30),
            'status' => $status,
        ]);
    }

    private function sessionFor(Pet $pet, Service $service): TreatmentSession
    {
        $procedure = Procedure::factory()->for($service)->create();
        $treatment = Treatment::factory()->for($service)->create();
        $treatment->procedures()->attach($procedure);

        return app(TreatmentAssignmentService::class)->assign($pet, $treatment, [
            'planned_sessions' => 1,
            'default_session_price' => '5000.00',
            'currency' => 'ARS',
            'starts_on' => '2026-10-01',
            'status' => 'pending',
        ])->sessions->sole();
    }

    /** @return array<string, int|string|bool> */
    private function clinicalRecordPayload(int $sessionId): array
    {
        return [
            'type' => 'session',
            'title' => 'Registro de sesión',
            'content' => 'Evolución registrada.',
            'occurred_at' => '2026-10-09 12:00:00',
            'is_visible_to_client' => true,
            'treatment_session_id' => $sessionId,
        ];
    }
}
