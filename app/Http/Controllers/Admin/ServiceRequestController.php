<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest\ResolveServiceRequestRequest;
use App\Models\ServiceRequest;
use App\Models\Treatment;
use App\Services\ServiceRequestResolutionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ServiceRequestController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()->hasRole('admin'), 403);
        Gate::authorize('viewAny', ServiceRequest::class);
        $status = $request->string('status')->toString();
        abort_unless($status === '' || in_array($status, ['pending', 'resolved', 'cancelled'], true), 422);

        return Inertia::render('admin/service-requests/index', ['requests' => ServiceRequest::query()->with(['pet.client.user', 'service:id,name', 'petTreatment:id,treatment_name'])->when($status, fn ($query) => $query->where('status', $status))->latest()->paginate(15)->withQueryString(), 'status' => $status]);
    }

    public function show(ServiceRequest $serviceRequest): Response
    {
        Gate::authorize('view', $serviceRequest);

        return Inertia::render('admin/service-requests/show', ['serviceRequest' => $serviceRequest->load(['pet.client.user', 'service', 'petTreatment.sessions']), 'treatments' => Treatment::query()->where('service_id', $serviceRequest->service_id)->where('is_active', true)->orderBy('name')->get()]);
    }

    public function resolve(ResolveServiceRequestRequest $request, ServiceRequest $serviceRequest, ServiceRequestResolutionService $resolver): RedirectResponse
    {
        $resolver->resolve($serviceRequest, Treatment::query()->findOrFail($request->validated('treatment_id')), $request->safe()->except('treatment_id'));
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Solicitud resuelta y tratamiento asignado.')]);

        return to_route('admin.service-requests.show', $serviceRequest);
    }

    public function cancel(ServiceRequest $serviceRequest): RedirectResponse
    {
        Gate::authorize('cancel', $serviceRequest);
        abort_unless($serviceRequest->status === 'pending', 422);
        $serviceRequest->update(['status' => 'cancelled']);
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Solicitud cancelada.')]);

        return back();
    }
}
