<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Procedure\StoreProcedureRequest;
use App\Http\Requests\Procedure\UpdateProcedureRequest;
use App\Models\Procedure;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ProcedureController extends Controller
{
    public function index(Service $service): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('viewAny', Procedure::class);

        return Inertia::render('admin/services/procedures/index', [
            'service' => $service,
            'procedures' => $service->procedures()->orderBy('name')->orderBy('id')->get(),
        ]);
    }

    public function create(Service $service): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('create', Procedure::class);

        return Inertia::render('admin/services/procedures/create', ['service' => $service]);
    }

    public function store(StoreProcedureRequest $request, Service $service): RedirectResponse
    {
        $this->authorizeAdmin();
        $procedure = $service->procedures()->create($request->safe()->only([
            'name', 'description', 'duration_minutes', 'is_active',
        ]));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Procedure created.')]);

        return to_route('admin.services.procedures.show', [$service, $procedure]);
    }

    public function show(Service $service, Procedure $procedure): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('view', $procedure);

        return Inertia::render('admin/services/procedures/show', [
            'service' => $service,
            'procedure' => $procedure,
        ]);
    }

    public function edit(Service $service, Procedure $procedure): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('update', $procedure);

        return Inertia::render('admin/services/procedures/edit', [
            'service' => $service,
            'procedure' => $procedure,
        ]);
    }

    public function update(UpdateProcedureRequest $request, Service $service, Procedure $procedure): RedirectResponse
    {
        $this->authorizeAdmin();
        $procedure->update($request->safe()->only([
            'name', 'description', 'duration_minutes', 'is_active',
        ]));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Procedure updated.')]);

        return to_route('admin.services.procedures.show', [$service, $procedure]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
    }
}
