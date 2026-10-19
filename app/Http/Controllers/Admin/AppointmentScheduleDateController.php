<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentScheduleDate\StoreAppointmentScheduleDateRequest;
use App\Http\Requests\AppointmentScheduleDate\UpdateAppointmentScheduleDateRequest;
use App\Models\AppointmentScheduleDate;
use App\Models\AppointmentScheduleTemplate;
use App\Models\Service;
use App\Services\AppointmentScheduleStartGenerator;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentScheduleDateController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', AppointmentScheduleDate::class);

        $view = in_array($request->query('view'), ['month', 'week', 'day'], true)
            ? $request->query('view')
            : 'month';
        $date = $this->calendarDate($request->query('date'));
        [$periodStart, $periodEnd] = match ($view) {
            'week' => [$date->startOfWeek(), $date->endOfWeek()],
            'day' => [$date, $date],
            default => [$date->startOfMonth(), $date->endOfMonth()],
        };

        $scheduleDates = AppointmentScheduleDate::query()
            ->whereBetween('date', [$periodStart->toDateString(), $periodEnd->toDateString()])
            ->with([
                'blocks' => fn ($query) => $query->orderBy('starts_at'),
                'starts' => fn ($query) => $query->orderBy('starts_at'),
                'services:id,name',
                'appointments' => fn ($query) => $query
                    ->select(['id', 'pet_id', 'service_id', 'appointment_schedule_date_id', 'service_name', 'duration_minutes', 'starts_at', 'ends_at', 'status'])
                    ->with('pet:id,name')
                    ->orderBy('starts_at')
                    ->orderBy('id'),
            ])
            ->orderBy('date')
            ->get()
            ->keyBy(fn (AppointmentScheduleDate $scheduleDate) => $scheduleDate->date->toDateString());

        $days = collect(range(0, $periodStart->diffInDays($periodEnd)))->map(function (int $offset) use ($periodStart, $scheduleDates): array {
            $date = $periodStart->addDays($offset);
            /** @var AppointmentScheduleDate|null $scheduleDate */
            $scheduleDate = $scheduleDates->get($date->toDateString());

            return [
                'date' => $date->toDateString(),
                'published' => $scheduleDate !== null,
                'schedule_date_id' => $scheduleDate?->id,
                'capacity' => $scheduleDate?->capacity,
                'blocks' => $scheduleDate?->blocks->map(fn ($block): array => [
                    'starts_at' => substr($block->starts_at, 0, 5),
                    'ends_at' => substr($block->ends_at, 0, 5),
                ])->values() ?? [],
                'starts' => $scheduleDate?->starts->map(fn ($start): array => [
                    'starts_at' => substr($start->starts_at, 0, 5),
                ])->values() ?? [],
                'appointments' => $scheduleDate?->appointments->map(fn ($appointment): array => [
                    'id' => $appointment->id,
                    'pet' => ['id' => $appointment->pet->id, 'name' => $appointment->pet->name],
                    'service' => ['id' => $appointment->service_id, 'name' => $appointment->service_name],
                    'status' => $appointment->status,
                    'starts_at' => $appointment->starts_at->setTimezone(config('appointments.timezone'))->toIso8601String(),
                    'ends_at' => $appointment->ends_at->setTimezone(config('appointments.timezone'))->toIso8601String(),
                    'duration_minutes' => $appointment->duration_minutes,
                ])->values() ?? [],
            ];
        });

        $appointments = $scheduleDates->flatMap->appointments;
        $activeAppointments = $appointments->whereIn('status', ['pending', 'approved']);
        $peak = $scheduleDates->map(function (AppointmentScheduleDate $scheduleDate): array {
            $occupancy = 0;
            $maximum = 0;
            $events = $scheduleDate->appointments
                ->whereIn('status', ['pending', 'approved'])
                ->flatMap(fn ($appointment): array => [
                    ['at' => $appointment->starts_at->getTimestamp(), 'change' => 1],
                    ['at' => $appointment->ends_at->getTimestamp(), 'change' => -1],
                ])
                ->sortBy([['at', 'asc'], ['change', 'asc']]);

            foreach ($events as $event) {
                $occupancy += $event['change'];
                $maximum = max($maximum, $occupancy);
            }

            return ['occupancy' => $maximum, 'capacity' => $scheduleDate->capacity];
        })->sortByDesc(fn (array $peak): float => $peak['occupancy'] / $peak['capacity'])->first();

        return Inertia::render('admin/schedule-dates/index', [
            // Retained while the existing configuration-list UI is migrated to the calendar props.
            'scheduleDates' => $scheduleDates->values(),
            'period' => [
                'view' => $view,
                'date' => $date->toDateString(),
                'starts_on' => $periodStart->toDateString(),
                'ends_on' => $periodEnd->toDateString(),
                'previous_date' => match ($view) {
                    'month' => $date->subMonthNoOverflow()->toDateString(),
                    'week' => $date->subWeek()->toDateString(),
                    default => $date->subDay()->toDateString(),
                },
                'next_date' => match ($view) {
                    'month' => $date->addMonthNoOverflow()->toDateString(),
                    'week' => $date->addWeek()->toDateString(),
                    default => $date->addDay()->toDateString(),
                },
                'today' => now(config('appointments.timezone'))->toDateString(),
            ],
            'days' => $days,
            'summary' => [
                'published_dates' => $scheduleDates->count(),
                'active_appointments' => $activeAppointments->count(),
                'pending' => $appointments->where('status', 'pending')->count(),
                'approved' => $appointments->where('status', 'approved')->count(),
                'attended' => $appointments->where('status', 'attended')->count(),
                'absent' => $appointments->where('status', 'absent')->count(),
                'cancelled' => $appointments->where('status', 'cancelled')->count(),
                'rejected' => $appointments->where('status', 'rejected')->count(),
                'maximum_active_occupancy' => $peak['occupancy'] ?? 0,
                'load_percent' => $peak === null ? 0 : (int) round(($peak['occupancy'] / $peak['capacity']) * 100),
            ],
        ]);
    }

    private function calendarDate(mixed $value): CarbonImmutable
    {
        if (! is_string($value) || preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) !== 1) {
            return now(config('appointments.timezone'))->toImmutable()->startOfDay();
        }

        $date = CarbonImmutable::createFromFormat('!Y-m-d', $value, config('appointments.timezone'));
        $errors = CarbonImmutable::getLastErrors();

        return $date !== false && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))
            ? $date
            : now(config('appointments.timezone'))->toImmutable()->startOfDay();
    }

    public function create(): Response
    {
        Gate::authorize('create', AppointmentScheduleDate::class);

        return Inertia::render('admin/schedule-dates/create', [
            'services' => $this->availableServices(),
            'templates' => AppointmentScheduleTemplate::query()
                ->with([
                    'blocks' => fn ($query) => $query->orderBy('starts_at'),
                    'services:id,name,appointment_duration_minutes',
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StoreAppointmentScheduleDateRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $configuration = $this->publicationConfiguration($request);

            foreach ($request->publicationDates() as $date) {
                $scheduleDate = AppointmentScheduleDate::query()->create([
                    'date' => $date,
                    'capacity' => $configuration['capacity'],
                ]);
                $this->replaceConfiguration($scheduleDate, $configuration);
            }
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Fecha publicada en la agenda.')]);

        return to_route('admin.schedule-dates.index');
    }

    public function edit(AppointmentScheduleDate $scheduleDate): Response
    {
        Gate::authorize('update', $scheduleDate);

        return Inertia::render('admin/schedule-dates/edit', [
            'scheduleDate' => $scheduleDate->load([
                'blocks' => fn ($query) => $query->orderBy('starts_at'),
                'starts' => fn ($query) => $query->orderBy('starts_at'),
                'services:id,name,appointment_duration_minutes',
            ]),
            'services' => $this->availableServices(),
        ]);
    }

    public function update(UpdateAppointmentScheduleDateRequest $request, AppointmentScheduleDate $scheduleDate): RedirectResponse
    {
        DB::transaction(function () use ($request, $scheduleDate): void {
            $lockedScheduleDate = AppointmentScheduleDate::query()->lockForUpdate()->findOrFail($scheduleDate->id);
            $lockedScheduleDate->update($request->safe()->only(['date', 'capacity']));
            $this->replaceConfiguration($lockedScheduleDate, $request->validated());
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Agenda actualizada.')]);

        return to_route('admin.schedule-dates.edit', $scheduleDate);
    }

    /** @return Collection<int, Service> */
    private function availableServices(): Collection
    {
        return Service::query()
            ->where('is_active', true)
            ->whereNotNull('appointment_duration_minutes')
            ->orderBy('name')
            ->get(['id', 'name', 'appointment_duration_minutes']);
    }

    /** @param array{blocks: array<int, array{starts_at: string, ends_at: string}>, starts: array<int, string>, service_ids: array<int, int>} $data */
    private function replaceConfiguration(AppointmentScheduleDate $scheduleDate, array $data): void
    {
        $scheduleDate->blocks()->delete();
        $scheduleDate->starts()->delete();
        $scheduleDate->blocks()->createMany($data['blocks']);
        $scheduleDate->starts()->createMany(collect($data['starts'])->map(
            fn (string $start): array => ['starts_at' => $start],
        )->all());
        $scheduleDate->services()->sync($data['service_ids']);
    }

    /** @return array{capacity: int, blocks: array<int, array{starts_at: string, ends_at: string}>, starts: array<int, string>, service_ids: array<int, int>} */
    private function publicationConfiguration(StoreAppointmentScheduleDateRequest $request): array
    {
        $template = $request->validated('template_id')
            ? AppointmentScheduleTemplate::query()->with(['blocks', 'services'])->findOrFail($request->validated('template_id'))
            : null;
        $blocks = $template
            ? $template->blocks->map(fn ($block): array => [
                'starts_at' => substr($block->starts_at, 0, 5),
                'ends_at' => substr($block->ends_at, 0, 5),
            ])->all()
            : $request->validated('blocks');
        $interval = $template?->interval_minutes ?? $request->validated('interval_minutes');
        $serviceIds = $template
            ? $template->services->modelKeys()
            : $request->validated('service_ids');

        return [
            'capacity' => $template?->capacity ?? $request->validated('capacity'),
            'blocks' => $blocks,
            'starts' => $template
                ? app(AppointmentScheduleStartGenerator::class)->generate($blocks, $interval)
                : ($request->validated('starts') ?? app(AppointmentScheduleStartGenerator::class)->generate($blocks, $interval)),
            'service_ids' => $serviceIds,
        ];
    }
}
