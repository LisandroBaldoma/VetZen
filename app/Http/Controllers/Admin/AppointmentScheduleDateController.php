<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentScheduleDate\StoreAppointmentScheduleDateRequest;
use App\Http\Requests\AppointmentScheduleDate\UpdateAppointmentScheduleDateRequest;
use App\Models\AppointmentScheduleDate;
use App\Models\Service;
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
        ]);
    }

    public function store(StoreAppointmentScheduleDateRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            foreach ($request->validated('dates') as $date) {
                $scheduleDate = AppointmentScheduleDate::query()->create([
                    'date' => $date,
                    'capacity' => $request->validated('capacity'),
                ]);
                $this->replaceConfiguration($scheduleDate, $request->validated());
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
}
