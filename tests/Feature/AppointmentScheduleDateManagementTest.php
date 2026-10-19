<?php

namespace Tests\Feature;

use App\Models\AppointmentScheduleDate;
use App\Models\AppointmentScheduleTemplate;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentScheduleDateManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_and_update_a_schedule_date(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 45]);
        $payload = $this->storePayload($service, [now()->addDay()->toDateString()]);

        $this->actingAs($admin)
            ->post(route('admin.schedule-dates.store'), $payload)
            ->assertRedirect();

        $scheduleDate = AppointmentScheduleDate::query()->sole();
        $this->assertSame(3, $scheduleDate->capacity);
        $this->assertSame(['10:00', '16:00'], $scheduleDate->blocks()->orderBy('starts_at')->pluck('starts_at')->all());
        $this->assertSame(['10:00', '11:00', '12:00', '16:00', '17:00', '18:00'], $scheduleDate->starts()->orderBy('starts_at')->pluck('starts_at')->all());
        $this->assertTrue($scheduleDate->services()->sole()->is($service));

        $payload = $this->updatePayload($service, $scheduleDate->date->toDateString());
        $payload['capacity'] = 4;
        $payload['starts'][] = '17:00';
        $this->actingAs($admin)
            ->patch(route('admin.schedule-dates.update', $scheduleDate), $payload)
            ->assertRedirect(route('admin.schedule-dates.edit', $scheduleDate));

        $scheduleDate->refresh();
        $this->assertSame(4, $scheduleDate->capacity);
        $this->assertSame(4, $scheduleDate->starts()->count());
    }

    public function test_admin_index_and_edit_receive_schedule_configuration(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $scheduleDate = AppointmentScheduleDate::query()->create([
            'date' => now()->addDay()->toDateString(),
            'capacity' => 1,
        ]);
        $scheduleDate->blocks()->create(['starts_at' => '10:00', 'ends_at' => '13:00']);
        $scheduleDate->starts()->create(['starts_at' => '10:00']);
        $scheduleDate->services()->attach($service);

        $this->actingAs($admin)->get(route('admin.schedule-dates.index'))
            ->assertInertia(fn ($page) => $page
                ->component('admin/schedule-dates/index')
                ->has('scheduleDates', 1)
                ->where('scheduleDates.0.capacity', 1)
                ->where('scheduleDates.0.services.0.id', $service->id));

        $this->actingAs($admin)->get(route('admin.schedule-dates.edit', $scheduleDate))
            ->assertInertia(fn ($page) => $page
                ->component('admin/schedule-dates/edit')
                ->where('scheduleDate.id', $scheduleDate->id)
                ->where('services.0.appointment_duration_minutes', 30));
    }

    public function test_schedule_date_create_receives_available_templates(): void
    {
        $this->withoutVite();

        $admin = User::factory()->admin()->create();
        $template = AppointmentScheduleTemplate::query()->create([
            'name' => 'Mañana',
            'capacity' => 1,
            'interval_minutes' => 30,
        ]);
        $template->blocks()->create(['starts_at' => '10:00', 'ends_at' => '13:00']);

        $this->actingAs($admin)->get(route('admin.schedule-dates.create'))
            ->assertInertia(fn ($page) => $page
                ->component('admin/schedule-dates/create')
                ->where('templates.0.id', $template->id)
                ->where('templates.0.name', 'Mañana'));
    }

    public function test_admin_can_publish_multiple_dates_with_the_same_configuration(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 45]);

        $this->actingAs($admin)
            ->post(route('admin.schedule-dates.store'), $this->storePayload($service, [
                now()->addDay()->toDateString(),
                now()->addDays(3)->toDateString(),
            ]))
            ->assertRedirect(route('admin.schedule-dates.index'));

        $this->assertDatabaseCount('appointment_schedule_dates', 2);
        $this->assertDatabaseCount('appointment_schedule_blocks', 4);
        $this->assertDatabaseCount('appointment_schedule_starts', 12);
        $this->assertDatabaseCount('appointment_schedule_date_service', 2);
    }

    public function test_admin_can_adjust_generated_starts_before_publishing(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 45]);

        $payload = $this->storePayload($service, [now()->addDay()->toDateString()]);
        $payload['interval_minutes'] = 30;
        $payload['starts'] = ['10:00', '12:30', '16:30'];

        $this->actingAs($admin)
            ->post(route('admin.schedule-dates.store'), $payload)
            ->assertRedirect(route('admin.schedule-dates.index'));

        $scheduleDate = AppointmentScheduleDate::query()->sole();
        $this->assertSame(
            ['10:00', '12:30', '16:30'],
            $scheduleDate->starts()->orderBy('starts_at')->pluck('starts_at')->all(),
        );
    }

    public function test_schedule_date_rejects_invalid_blocks_starts_services_and_capacity(): void
    {
        $admin = User::factory()->admin()->create();
        $missingDuration = Service::factory()->create(['appointment_duration_minutes' => null]);

        $this->actingAs($admin)->post(route('admin.schedule-dates.store'), [
            'dates' => [now()->subDay()->toDateString()],
            'capacity' => 6,
            'blocks' => [
                ['starts_at' => '13:00', 'ends_at' => '10:00'],
                ['starts_at' => '12:00', 'ends_at' => '14:00'],
            ],
            'interval_minutes' => 20,
            'service_ids' => [$missingDuration->id],
        ])->assertSessionHasErrors([
            'dates.0', 'capacity', 'interval_minutes', 'blocks.0.ends_at', 'service_ids',
        ]);

        $this->assertDatabaseCount('appointment_schedule_dates', 0);
    }

    public function test_schedule_date_rejects_overlapping_blocks_and_inactive_services(): void
    {
        $admin = User::factory()->admin()->create();
        $inactive = Service::factory()->inactive()->create(['appointment_duration_minutes' => 30]);

        $this->actingAs($admin)->post(route('admin.schedule-dates.store'), [
            'dates' => [now()->addDay()->toDateString()],
            'capacity' => 1,
            'blocks' => [
                ['starts_at' => '10:00', 'ends_at' => '12:00'],
                ['starts_at' => '11:00', 'ends_at' => '13:00'],
            ],
            'interval_minutes' => 30,
            'service_ids' => [$inactive->id],
        ])->assertSessionHasErrors(['blocks', 'service_ids.0']);
    }

    public function test_client_cannot_access_administrative_schedule_routes(): void
    {
        $client = User::factory()->create();
        $scheduleDate = AppointmentScheduleDate::query()->create([
            'date' => now()->addDay()->toDateString(),
            'capacity' => 1,
        ]);

        $this->actingAs($client)->get(route('admin.schedule-dates.index'))->assertForbidden();
        $this->actingAs($client)->get(route('admin.schedule-dates.create'))->assertForbidden();
        $this->actingAs($client)->post(route('admin.schedule-dates.store'), [])->assertForbidden();
        $this->actingAs($client)->get(route('admin.schedule-dates.edit', $scheduleDate))->assertForbidden();
        $this->actingAs($client)->patch(route('admin.schedule-dates.update', $scheduleDate), [])->assertForbidden();
    }

    /** @return array<string, mixed> */
    private function storePayload(Service $service, array $dates): array
    {
        return [
            'dates' => $dates,
            'capacity' => 3,
            'blocks' => [
                ['starts_at' => '10:00', 'ends_at' => '13:00'],
                ['starts_at' => '16:00', 'ends_at' => '19:00'],
            ],
            'interval_minutes' => 60,
            'service_ids' => [$service->id],
        ];
    }

    /** @return array<string, mixed> */
    private function updatePayload(Service $service, string $date): array
    {
        return [
            ...$this->storePayload($service, [$date]),
            'date' => $date,
            'starts' => ['10:00', '11:00', '16:00'],
        ];
    }
}
