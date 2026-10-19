<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentScheduleTemplate\StoreAppointmentScheduleTemplateRequest;
use App\Http\Requests\AppointmentScheduleTemplate\UpdateAppointmentScheduleTemplateRequest;
use App\Models\AppointmentScheduleTemplate;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentScheduleTemplateController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', AppointmentScheduleTemplate::class);

        return Inertia::render('admin/schedule-templates/index', [
            'templates' => AppointmentScheduleTemplate::query()
                ->with(['blocks' => fn ($query) => $query->orderBy('starts_at'), 'services:id,name'])
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', AppointmentScheduleTemplate::class);

        return Inertia::render('admin/schedule-templates/create', ['services' => $this->availableServices()]);
    }

    public function store(StoreAppointmentScheduleTemplateRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $template = AppointmentScheduleTemplate::query()->create($request->safe()->only(['name', 'capacity', 'interval_minutes']));
            $this->replaceConfiguration($template, $request->validated());
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Plantilla creada.')]);

        return to_route('admin.schedule-templates.index');
    }

    public function edit(AppointmentScheduleTemplate $scheduleTemplate): Response
    {
        Gate::authorize('update', $scheduleTemplate);

        return Inertia::render('admin/schedule-templates/edit', [
            'template' => $scheduleTemplate->load([
                'blocks' => fn ($query) => $query->orderBy('starts_at'),
                'services:id,name,appointment_duration_minutes',
            ]),
            'services' => $this->availableServices(),
        ]);
    }

    public function update(UpdateAppointmentScheduleTemplateRequest $request, AppointmentScheduleTemplate $scheduleTemplate): RedirectResponse
    {
        DB::transaction(function () use ($request, $scheduleTemplate): void {
            $template = AppointmentScheduleTemplate::query()->lockForUpdate()->findOrFail($scheduleTemplate->id);
            $template->update($request->safe()->only(['name', 'capacity', 'interval_minutes']));
            $this->replaceConfiguration($template, $request->validated());
        });

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Plantilla actualizada.')]);

        return to_route('admin.schedule-templates.edit', $scheduleTemplate);
    }

    public function destroy(AppointmentScheduleTemplate $scheduleTemplate): RedirectResponse
    {
        Gate::authorize('delete', $scheduleTemplate);
        $scheduleTemplate->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Plantilla eliminada.')]);

        return to_route('admin.schedule-templates.index');
    }

    private function availableServices()
    {
        return Service::query()
            ->where('is_active', true)
            ->whereNotNull('appointment_duration_minutes')
            ->orderBy('name')
            ->get(['id', 'name', 'appointment_duration_minutes']);
    }

    /** @param array{blocks: array<int, array{starts_at: string, ends_at: string}>, service_ids: array<int, int>} $data */
    private function replaceConfiguration(AppointmentScheduleTemplate $template, array $data): void
    {
        $template->blocks()->delete();
        $template->blocks()->createMany($data['blocks']);
        $template->services()->sync($data['service_ids']);
    }
}
