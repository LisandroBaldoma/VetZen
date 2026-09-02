<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest\StoreServiceRequestRequest;
use App\Models\Pet;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ServiceRequestController extends Controller
{
    public function index(Pet $pet): Response
    {
        Gate::authorize('view', $pet);

        return Inertia::render('pets/service-requests/index', [
            'pet' => $this->petData($pet),
            'requests' => $pet->serviceRequests()
                ->with(['service:id,name', 'petTreatment:id,treatment_name'])
                ->latest()
                ->get()
                ->map(fn (ServiceRequest $serviceRequest): array => [
                    ...$serviceRequest->only(['id', 'status']),
                    'created_at' => $serviceRequest->created_at->toISOString(),
                    'service' => $serviceRequest->service->only(['id', 'name']),
                    'pet_treatment' => $serviceRequest->petTreatment?->only(['id', 'treatment_name']),
                ]),
        ]);
    }

    public function create(Request $request, Pet $pet): Response
    {
        Gate::authorize('create', [ServiceRequest::class, $pet]);

        $services = Service::query()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'description']);
        $selectedService = $request->integer('service');

        return Inertia::render('pets/service-requests/create', [
            'pet' => $this->petData($pet),
            'services' => $services,
            'selectedServiceId' => $services->contains('id', $selectedService) ? $selectedService : null,
        ]);
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

        $serviceRequest->load(['service:id,name', 'petTreatment:id,treatment_name,planned_sessions']);

        return Inertia::render('pets/service-requests/show', [
            'pet' => $this->petData($pet),
            'serviceRequest' => [
                ...$serviceRequest->only(['id', 'status', 'notes']),
                'created_at' => $serviceRequest->created_at->toISOString(),
                'service' => $serviceRequest->service->only(['id', 'name']),
                'pet_treatment' => $serviceRequest->petTreatment?->only(['id', 'treatment_name', 'planned_sessions']),
            ],
        ]);
    }

    /** @return array<string, bool|int|string|null> */
    private function petData(Pet $pet): array
    {
        return [
            ...$pet->only(['id', 'name', 'species', 'breed', 'sex']),
            'birth_date' => $pet->birth_date?->toDateString(),
            'has_photo' => $pet->photo !== null,
        ];
    }
}
