<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Service::class);

        return Inertia::render('services/index', [
            'services' => Service::query()
                ->with(['procedures' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->orderBy('id')])
                ->where('is_active', true)
                ->orderBy('name')
                ->orderBy('id')
                ->get()
                ->map(fn (Service $service): array => $this->commercialData($service)),
            'pets' => $this->clientPets($request),
        ]);
    }

    public function show(Request $request, Service $service): Response
    {
        Gate::authorize('view', $service);

        $service->load(['procedures' => fn ($query) => $query
            ->where('is_active', true)
            ->orderBy('name')
            ->orderBy('id')]);

        return Inertia::render('services/show', [
            'service' => $this->commercialData($service),
            'pets' => $this->clientPets($request),
        ]);
    }

    /** @return array<string, mixed> */
    private function commercialData(Service $service): array
    {
        return [
            ...$service->only(['id', 'name', 'description']),
            'procedures' => $service->procedures->map(fn ($procedure): array => $procedure->only([
                'id',
                'name',
                'description',
                'duration_minutes',
            ]))->values()->all(),
        ];
    }

    /** @return array<int, array{id: int, name: string}> */
    private function clientPets(Request $request): array
    {
        if (! $request->user()->hasRole('client') || ! $request->user()->client) {
            return [];
        }

        return $request->user()->client->pets()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($pet): array => $pet->only(['id', 'name']))
            ->all();
    }
}
