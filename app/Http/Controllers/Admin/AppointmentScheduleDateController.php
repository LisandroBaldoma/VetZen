<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentScheduleDate\StoreAppointmentScheduleDateRequest;
use App\Http\Requests\AppointmentScheduleDate\UpdateAppointmentScheduleDateRequest;
use App\Models\AppointmentScheduleDate;
use App\Models\AppointmentScheduleTemplate;
use App\Models\Service;
use App\Services\AppointmentScheduleStartGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentScheduleDateController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', AppointmentScheduleDate::class);

        return Inertia::render('admin/schedule-dates/index', [
            'scheduleDates' => AppointmentScheduleDate::query()
                ->with([
                    'blocks' => fn ($query) => $query->orderBy('starts_at'),
                    'starts' => fn ($query) => $query->orderBy('starts_at'),
                    'services:id,name',
                ])
                ->orderBy('date')
                ->orderBy('id')
                ->get(),
        ]);
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
