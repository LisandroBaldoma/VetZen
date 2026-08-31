<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('viewAny', Service::class);

        return Inertia::render('admin/services/index', [
            'services' => Service::query()
                ->withCount('procedures')
                ->orderBy('name')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('create', Service::class);

        return Inertia::render('admin/services/create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $this->authorizeAdmin();
        $service = Service::query()->create($request->safe()->only([
            'name', 'description', 'is_active',
        ]));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Servicio creado.')]);

        return to_route('admin.services.index');
    }

    public function show(Service $service): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('view', $service);

        return Inertia::render('admin/services/show', ['service' => $service]);
    }

    public function edit(Service $service): Response
    {
        $this->authorizeAdmin();
        Gate::authorize('update', $service);

        return Inertia::render('admin/services/edit', ['service' => $service]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorizeAdmin();
        $service->update($request->safe()->only([
            'name', 'description', 'is_active',
        ]));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Servicio actualizado.')]);

        return to_route('admin.services.index');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
    }
}
