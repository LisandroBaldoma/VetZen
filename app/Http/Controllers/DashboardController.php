<?php

namespace App\Http\Controllers;

use App\Models\PetTreatment;
use App\Models\ServiceRequest;
use App\Models\TreatmentSession;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        if ($request->user()->hasRole('admin')) {
            $requests = ServiceRequest::query()
                ->select(['id', 'pet_id', 'service_id', 'status', 'notes', 'created_at'])
                ->where('status', 'pending')
                ->with(['pet:id,name,species', 'service:id,name'])
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
                    'notes' => $serviceRequest->notes,
                    'createdAt' => $serviceRequest->created_at->toIso8601String(),
                    'pet' => [
                        'id' => $serviceRequest->pet->id,
                        'name' => $serviceRequest->pet->name,
                        'species' => $serviceRequest->pet->species,
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
                    'selectedPet' => null,
                    'nextSession' => null,
                    'pendingRequests' => [],
                    'activeTreatments' => [],
                    'timezone' => config('app.timezone'),
                ]);
            }

            $pets = $client->pets()
                ->select(['id', 'client_id', 'name', 'species', 'breed', 'sex', 'birth_date', 'weight', 'photo'])
                ->orderBy('name')
                ->orderBy('id')
                ->limit(6)
                ->get();

            $selectedPet = $request->has('pet')
                ? $client->pets()
                    ->select(['id', 'client_id', 'name', 'species', 'breed', 'sex', 'birth_date', 'weight', 'photo'])
                    ->findOrFail($request->integer('pet'))
                : $pets->first();

            if ($selectedPet !== null && ! $pets->contains('id', $selectedPet->id)) {
                $pets->prepend($selectedPet);
                $pets = $pets->take(6)->values();
            }

            $pendingRequests = collect();
            $activeTreatments = collect();
            $activeTreatmentsCount = 0;

            if ($selectedPet !== null) {
                $pendingRequests = ServiceRequest::query()
                    ->select(['id', 'pet_id', 'service_id', 'status', 'created_at'])
                    ->where('status', 'pending')
                    ->where('pet_id', $selectedPet->id)
                    ->with(['pet:id,name', 'service:id,name'])
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->limit(5)
                    ->get();

                $nextSessionAttribute = fn (string $column) => TreatmentSession::query()
                    ->select($column)
                    ->whereColumn('pet_treatment_id', 'pet_treatments.id')
                    ->where('status', 'pending')
                    ->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '>=', now())
                    ->orderBy('scheduled_at')
                    ->orderBy('session_number')
                    ->limit(1);

                $activeTreatmentsQuery = PetTreatment::query()
                    ->select(['id', 'pet_id', 'treatment_name', 'planned_sessions', 'starts_on', 'status'])
                    ->addSelect([
                        'next_session_at' => $nextSessionAttribute('scheduled_at'),
                        'next_session_number' => $nextSessionAttribute('session_number'),
                    ])
                    ->where('pet_id', $selectedPet->id)
                    ->whereIn('status', ['pending', 'in_progress', 'suspended']);

                $activeTreatmentsCount = (clone $activeTreatmentsQuery)->count();

                $activeTreatments = $activeTreatmentsQuery
                    ->with('pet:id,name')
                    ->withCount([
                        'sessions as completed_sessions_count' => fn ($query) => $query->where('status', 'completed'),
                    ])
                    ->orderByDesc('starts_on')
                    ->orderByDesc('id')
                    ->limit(5)
                    ->get();
            }

            $hasEligibleNextSession = fn (PetTreatment $petTreatment): bool => in_array(
                $petTreatment->status,
                ['pending', 'in_progress'],
                true,
            ) && $petTreatment->getAttribute('next_session_at') !== null;

            $nextTreatment = $activeTreatments
                ->filter($hasEligibleNextSession)
                ->sortBy(fn (PetTreatment $petTreatment) => sprintf(
                    '%s-%010d',
                    $petTreatment->getAttribute('next_session_at'),
                    $petTreatment->getAttribute('next_session_number'),
                ))
                ->first();

            return Inertia::render('client/dashboard', [
                'pets' => $pets->map(fn ($pet): array => [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'species' => $pet->species,
                ])->all(),
                'selectedPet' => $selectedPet === null ? null : [
                    'id' => $selectedPet->id,
                    'name' => $selectedPet->name,
                    'species' => $selectedPet->species,
                    'breed' => $selectedPet->breed,
                    'sex' => $selectedPet->sex,
                    'birthDate' => $selectedPet->birth_date?->toDateString(),
                    'weight' => $selectedPet->weight,
                    'hasPhoto' => $selectedPet->photo !== null,
                    'activeTreatmentsCount' => $activeTreatmentsCount,
                ],
                'nextSession' => $nextTreatment === null ? null : [
                    'treatmentId' => $nextTreatment->id,
                    'treatmentName' => $nextTreatment->treatment_name,
                    'sessionNumber' => $nextTreatment->getAttribute('next_session_number'),
                    'plannedSessions' => $nextTreatment->planned_sessions,
                    'scheduledAt' => CarbonImmutable::parse(
                        $nextTreatment->getAttribute('next_session_at'),
                        config('app.timezone'),
                    )->toIso8601String(),
                    'status' => 'pending',
                ],
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
                    'nextSession' => ! $hasEligibleNextSession($petTreatment) ? null : [
                        'scheduledAt' => CarbonImmutable::parse(
                            $petTreatment->getAttribute('next_session_at'),
                            config('app.timezone'),
                        )->toIso8601String(),
                        'sessionNumber' => $petTreatment->getAttribute('next_session_number'),
                    ],
                    'pet' => [
                        'id' => $petTreatment->pet->id,
                        'name' => $petTreatment->pet->name,
                    ],
                ])->all(),
                'timezone' => config('app.timezone'),
            ]);
        }

        abort(403);
    }
}
