<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Treatment\StoreTreatmentRequest;
use App\Http\Requests\Treatment\UpdateTreatmentRequest;
use App\Models\Service;
use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TreatmentController extends Controller
{
    public function index(Service $service): Response
    {
        Gate::authorize('viewAny', Treatment::class);

        return Inertia::render('admin/services/treatments/index', ['service' => $service, 'treatments' => $service->treatments()->withCount('procedures')->orderBy('name')->get()]);
    }

    public function create(Service $service): Response
    {
        Gate::authorize('create', Treatment::class);

        return Inertia::render('admin/services/treatments/create', ['service' => $service, 'procedures' => $service->procedures()->where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(StoreTreatmentRequest $request, Service $service): RedirectResponse
    {
        $treatment = $service->treatments()->create($request->safe()->only(['name', 'description', 'estimated_sessions', 'is_active']));
        $treatment->procedures()->sync($request->validated('procedure_ids'));
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tratamiento creado.')]);

        return to_route('admin.services.treatments.index', $service);
    }

    public function edit(Service $service, Treatment $treatment): Response
    {
        Gate::authorize('update', $treatment);

        return Inertia::render('admin/services/treatments/edit', [
            'service' => $service,
            'treatment' => $treatment->load('procedures:id'),
            'procedures' => $service->procedures()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTreatmentRequest $request, Service $service, Treatment $treatment): RedirectResponse
    {
        $treatment->update($request->safe()->only(['name', 'description', 'estimated_sessions', 'is_active']));
        $treatment->procedures()->sync($request->validated('procedure_ids'));
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tratamiento actualizado.')]);

        return to_route('admin.services.treatments.index', $service);
    }
}
