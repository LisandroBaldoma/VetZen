<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest\StoreServiceRequestRequest;
use App\Models\Pet;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ServiceRequestController extends Controller
{
    public function index(Pet $pet): Response
    {
        Gate::authorize('view', $pet);

        return Inertia::render('pets/service-requests/index', ['pet' => $pet, 'requests' => $pet->serviceRequests()->with(['service:id,name', 'petTreatment:id,treatment_name'])->latest()->get()]);
    }

    public function create(Pet $pet): Response
    {
        Gate::authorize('create', [ServiceRequest::class, $pet]);

        return Inertia::render('pets/service-requests/create', ['pet' => $pet, 'services' => Service::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'description'])]);
    }

    public function store(StoreServiceRequestRequest $request, Pet $pet): RedirectResponse
    {
        $service = Service::query()->findOrFail($request->validated('service_id'));
        $serviceRequest = new ServiceRequest(['notes' => $request->validated('notes')]);
        $serviceRequest->pet()->associate($pet);
        $serviceRequest->service()->associate($service);
        $serviceRequest->save();
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Solicitud enviada.')]);

        return to_route('pets.service-requests.index', $pet);
    }

    public function show(Pet $pet, ServiceRequest $serviceRequest): Response
    {
        Gate::authorize('view', $serviceRequest);

        return Inertia::render('pets/service-requests/show', ['pet' => $pet, 'serviceRequest' => $serviceRequest->load(['service:id,name', 'petTreatment.sessions'])]);
    }
}
