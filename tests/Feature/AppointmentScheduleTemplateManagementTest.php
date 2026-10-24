<?php

namespace Tests\Feature;

use App\Models\AppointmentScheduleDate;
use App\Models\AppointmentScheduleTemplate;
use App\Models\Service;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentScheduleTemplateManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_templates_without_changing_published_dates(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);

        $this->actingAs($admin)->post(route('admin.schedule-templates.store'), $this->templatePayload($service))
            ->assertRedirect(route('admin.schedule-templates.index'));

        $template = AppointmentScheduleTemplate::query()->sole();
        $this->assertSame(30, $template->interval_minutes);
        $this->assertSame(['10:00', '14:00'], $template->blocks()->orderBy('starts_at')->pluck('starts_at')->all());
        $this->assertTrue($template->services()->sole()->is($service));

        $this->actingAs($admin)->post(route('admin.schedule-dates.store'), [
            'dates' => [now()->addDay()->toDateString()],
            'template_id' => $template->id,
        ])->assertRedirect();

        $scheduleDate = AppointmentScheduleDate::query()->sole();
        $this->assertSame(2, $scheduleDate->capacity);
        $this->assertSame(['10:00', '10:30', '11:00', '11:30', '14:00', '14:30'], $scheduleDate->starts()->orderBy('starts_at')->pluck('starts_at')->all());

        $payload = $this->templatePayload($service);
        $payload['name'] = 'Actualizada';
        $payload['capacity'] = 5;
        $this->actingAs($admin)->patch(route('admin.schedule-templates.update', $template), $payload)->assertRedirect();
        $this->actingAs($admin)->delete(route('admin.schedule-templates.destroy', $template))->assertRedirect();

        $this->assertDatabaseMissing('appointment_schedule_templates', ['id' => $template->id]);
        $this->assertDatabaseHas('appointment_schedule_dates', ['id' => $scheduleDate->id, 'capacity' => 2]);
        $this->assertDatabaseCount('appointment_schedule_starts', 6);
    }

    public function test_client_cannot_manage_schedule_templates(): void
    {
        $client = User::factory()->create();
        $template = AppointmentScheduleTemplate::query()->create([
            'name' => 'Mañana',
            'capacity' => 1,
            'interval_minutes' => 30,
        ]);

        $this->actingAs($client)->get(route('admin.schedule-templates.index'))->assertForbidden();
        $this->actingAs($client)->post(route('admin.schedule-templates.store'), [])->assertForbidden();
        $this->actingAs($client)->patch(route('admin.schedule-templates.update', $template), [])->assertForbidden();
        $this->actingAs($client)->delete(route('admin.schedule-templates.destroy', $template))->assertForbidden();
    }

    public function test_template_requires_a_supported_interval_and_active_services_when_published(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $invalidPayload = $this->templatePayload($service);
        $invalidPayload['interval_minutes'] = 20;

        $this->actingAs($admin)->post(route('admin.schedule-templates.store'), $invalidPayload)
            ->assertSessionHasErrors('interval_minutes');

        $template = AppointmentScheduleTemplate::query()->create([
            'name' => 'Servicio desactivado',
            'capacity' => 1,
            'interval_minutes' => 30,
        ]);
        $template->blocks()->create(['starts_at' => '10:00', 'ends_at' => '12:00']);
        $template->services()->attach($service);
        $service->update(['is_active' => false]);

        $this->actingAs($admin)->post(route('admin.schedule-dates.store'), [
            'dates' => [now()->addDay()->toDateString()],
            'template_id' => $template->id,
        ])->assertSessionHasErrors('template_id');

        $this->assertDatabaseCount('appointment_schedule_dates', 0);
    }

    public function test_schedule_date_can_be_selected_by_range_and_weekdays(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $monday = CarbonImmutable::now()->next(CarbonImmutable::MONDAY)->toDateString();
        $friday = CarbonImmutable::parse($monday)->addDays(4)->toDateString();

        $this->actingAs($admin)->post(route('admin.schedule-dates.store'), [
            'date_from' => $monday,
            'date_to' => $friday,
            'weekdays' => [1, 3, 5],
            'capacity' => 1,
            'interval_minutes' => 45,
            'blocks' => [['starts_at' => '10:00', 'ends_at' => '12:00']],
            'service_ids' => [$service->id],
        ])->assertRedirect();

        $this->assertSame([
            $monday,
            CarbonImmutable::parse($monday)->addDays(2)->toDateString(),
            $friday,
        ], AppointmentScheduleDate::query()->orderBy('date')->pluck('date')->map->toDateString()->all());
        $this->assertDatabaseCount('appointment_schedule_starts', 9);
    }

    public function test_existing_date_rejects_the_entire_publication_atomically(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['appointment_duration_minutes' => 30]);
        $existingDate = now()->addDays(2)->toDateString();
        AppointmentScheduleDate::query()->create(['date' => $existingDate, 'capacity' => 1]);

        $this->actingAs($admin)->post(route('admin.schedule-dates.store'), [
            ...$this->customPublication($service),
            'dates' => [$existingDate, now()->addDays(3)->toDateString()],
        ])->assertSessionHasErrors('dates');

        $this->assertDatabaseCount('appointment_schedule_dates', 1);
        $this->assertDatabaseCount('appointment_schedule_starts', 0);
    }

    /** @return array<string, mixed> */
    private function templatePayload(Service $service): array
    {
        return [
            'name' => 'Horario terapias',
            'capacity' => 2,
            'interval_minutes' => 30,
            'blocks' => [
                ['starts_at' => '10:00', 'ends_at' => '12:00'],
                ['starts_at' => '14:00', 'ends_at' => '15:00'],
            ],
            'service_ids' => [$service->id],
        ];
    }

    /** @return array<string, mixed> */
    private function customPublication(Service $service): array
    {
        return [
            'capacity' => 1,
            'interval_minutes' => 30,
            'blocks' => [['starts_at' => '10:00', 'ends_at' => '12:00']],
            'service_ids' => [$service->id],
        ];
    }
}
