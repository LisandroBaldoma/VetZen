<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest\ResolveServiceRequestRequest;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Treatment;
use App\Services\ServiceRequestResolutionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ServiceRequestController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()->hasRole('admin'), 403);
        Gate::authorize('viewAny', ServiceRequest::class);

        $search = trim($request->string('search')->toString());
        abort_if(mb_strlen($search) > 100, 422);
        $status = $request->string('status')->toString();
        abort_unless($status === '' || in_array($status, ['pending', 'resolved', 'cancelled'], true), 422);
        $service = $request->string('service')->toString();
        abort_unless($service === '' || ctype_digit($service), 422);

        $requests = ServiceRequest::query()
            ->with(['pet.client.user:id,name', 'service:id,name'])
            ->when($search, fn ($query, $search) => $query->where(function ($query) use ($search): void {
                $query->whereHas('pet', fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('pet.client.user', fn ($query) => $query->where('name', 'like', '%'.$search.'%'));
            }))
            ->when($service, fn ($query, $service) => $query->where('service_id', $service))
            ->when($status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn (ServiceRequest $serviceRequest): array => [
                'id' => $serviceRequest->id,
                'status' => $serviceRequest->status,
                'created_at' => $serviceRequest->created_at->toISOString(),
                'pet' => [
                    'id' => $serviceRequest->pet->id,
                    'name' => $serviceRequest->pet->name,
                ],
                'responsible' => [
                    'id' => $serviceRequest->pet->client->id,
                    'name' => $serviceRequest->pet->client->user->name,
                ],
                'service' => $serviceRequest->service->only(['id', 'name']),
            ]);

        return Inertia::render('admin/service-requests/index', [
            'requests' => $requests,
            'services' => Service::query()->orderBy('name')->orderBy('id')->get(['id', 'name']),
            'filters' => ['search' => $search, 'service' => $service, 'status' => $status],
        ]);
    }

    public function show(ServiceRequest $serviceRequest): Response
    {
        Gate::authorize('view', $serviceRequest);

        $serviceRequest->load(['pet.client.user:id,name', 'service:id,name,is_active', 'petTreatment:id,treatment_name,planned_sessions']);

        return Inertia::render('admin/service-requests/show', [
            'serviceRequest' => [
                ...$serviceRequest->only(['id', 'status', 'notes']),
                'created_at' => $serviceRequest->created_at->toISOString(),
                'pet' => $this->petData($serviceRequest),
                'service' => $serviceRequest->service->only(['id', 'name', 'is_active']),
                'pet_treatment' => $serviceRequest->petTreatment?->only(['id', 'treatment_name', 'planned_sessions']),
            ],
            'treatments' => $serviceRequest->service->is_active
                ? Treatment::query()->where('service_id', $serviceRequest->service_id)->where('is_active', true)->orderBy('name')->get(['id', 'name', 'estimated_sessions'])
                : [],
        ]);
    }

    public function resolve(ResolveServiceRequestRequest $request, ServiceRequest $serviceRequest, ServiceRequestResolutionService $resolver): RedirectResponse
    {
        $resolved = $resolver->resolve($serviceRequest, Treatment::query()->findOrFail($request->validated('treatment_id')), $request->safe()->except('treatment_id'));
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Solicitud resuelta y tratamiento asignado.')]);

        return to_route('admin.pets.treatments.show', [$resolved->pet_id, $resolved->pet_treatment_id]);
    }

    public function cancel(ServiceRequest $serviceRequest): RedirectResponse
    {
        Gate::authorize('cancel', $serviceRequest);
        DB::transaction(function () use ($serviceRequest): void {
            $locked = ServiceRequest::query()->lockForUpdate()->findOrFail($serviceRequest->id);
            if ($locked->status !== 'pending') {
                throw ValidationException::withMessages(['status' => __('Solo se pueden cancelar solicitudes pendientes.')]);
            }

            $locked->update(['status' => 'cancelled']);
        });
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Solicitud cancelada.')]);

        return back();
    }

    /** @return array<string, mixed> */
    private function petData(ServiceRequest $serviceRequest): array
    {
        $pet = $serviceRequest->pet;

        return [
            ...$pet->only(['id', 'name', 'species', 'breed', 'sex']),
            'birth_date' => $pet->birth_date?->toDateString(),
            'has_photo' => $pet->photo !== null,
            'client' => [
                'id' => $pet->client->id,
                'name' => $pet->client->user->name,
            ],
        ];
    }
}
