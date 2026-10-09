<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AppointmentScheduleDate;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
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
}
