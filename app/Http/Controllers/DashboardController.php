<?php

namespace App\Http\Controllers;

use App\Models\PetTreatment;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        if ($request->user()->hasRole('admin')) {
            $requests = ServiceRequest::query()
                ->select(['id', 'pet_id', 'service_id', 'status', 'created_at'])
                ->with(['pet:id,name', 'service:id,name'])
                ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', ['pending'])
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get();

            return Inertia::render('admin/dashboard', [
                'pendingRequestsCount' => ServiceRequest::query()
                    ->where('status', 'pending')
                    ->count(),
                'requests' => $requests->map(fn (ServiceRequest $serviceRequest): array => [
                    'id' => $serviceRequest->id,
                    'status' => $serviceRequest->status,
                    'createdAt' => $serviceRequest->created_at->toIso8601String(),
                    'pet' => [
                        'id' => $serviceRequest->pet->id,
                        'name' => $serviceRequest->pet->name,
                    ],
                    'service' => [
                        'id' => $serviceRequest->service->id,
                        'name' => $serviceRequest->service->name,
                    ],
                ])->all(),
            ]);
        }

        if ($request->user()->hasRole('client')) {
            $client = $request->user()->client;

            if ($client === null) {
                return Inertia::render('client/dashboard', [
                    'pets' => [],
                    'pendingRequests' => [],
                    'activeTreatments' => [],
                ]);
            }

            $pets = $client->pets()
                ->select(['id', 'client_id', 'name', 'species'])
                ->orderBy('name')
                ->orderBy('id')
                ->limit(6)
                ->get();

            $pendingRequests = ServiceRequest::query()
                ->select(['id', 'pet_id', 'service_id', 'status', 'created_at'])
                ->where('status', 'pending')
                ->whereHas('pet', fn ($query) => $query->where('client_id', $client->id))
                ->with(['pet:id,name', 'service:id,name'])
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get();

            $activeTreatments = PetTreatment::query()
                ->select(['id', 'pet_id', 'treatment_name', 'planned_sessions', 'starts_on', 'status'])
                ->whereIn('status', ['pending', 'in_progress', 'suspended'])
                ->whereHas('pet', fn ($query) => $query->where('client_id', $client->id))
                ->with('pet:id,name')
                ->withCount([
                    'sessions as completed_sessions_count' => fn ($query) => $query->where('status', 'completed'),
                ])
                ->orderByDesc('starts_on')
                ->orderByDesc('id')
                ->limit(5)
                ->get();

            return Inertia::render('client/dashboard', [
                'pets' => $pets->map(fn ($pet): array => [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'species' => $pet->species,
                ])->all(),
                'pendingRequests' => $pendingRequests->map(fn (ServiceRequest $serviceRequest): array => [
                    'id' => $serviceRequest->id,
                    'status' => $serviceRequest->status,
                    'createdAt' => $serviceRequest->created_at->toIso8601String(),
                    'pet' => [
                        'id' => $serviceRequest->pet->id,
                        'name' => $serviceRequest->pet->name,
                    ],
                    'service' => [
                        'id' => $serviceRequest->service->id,
                        'name' => $serviceRequest->service->name,
                    ],
                ])->all(),
                'activeTreatments' => $activeTreatments->map(fn (PetTreatment $petTreatment): array => [
                    'id' => $petTreatment->id,
                    'treatmentName' => $petTreatment->treatment_name,
                    'status' => $petTreatment->status,
                    'plannedSessions' => $petTreatment->planned_sessions,
                    'completedSessions' => $petTreatment->completed_sessions_count,
                    'pet' => [
                        'id' => $petTreatment->pet->id,
                        'name' => $petTreatment->pet->name,
                    ],
                ])->all(),
            ]);
        }

        abort(403);
    }
}
