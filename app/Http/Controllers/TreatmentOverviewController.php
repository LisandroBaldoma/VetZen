<?php

namespace App\Http\Controllers;

use App\Http\Requests\Treatment\ListPetTreatmentsRequest;
use App\Models\PetTreatment;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TreatmentOverviewController extends Controller
{
    public function index(ListPetTreatmentsRequest $request): Response
    {
        abort_unless($request->user()->hasRole('admin'), 403);

        return $this->render($request, true);
    }

    public function mine(ListPetTreatmentsRequest $request): Response
    {
        abort_unless($request->user()->hasRole('client'), 403);

        return $this->render($request, false);
    }

    private function render(ListPetTreatmentsRequest $request, bool $isAdmin): Response
    {
        Gate::authorize('viewAny', PetTreatment::class);

        $filters = $request->safe()->only(['search', 'service', 'pet', 'status']);
        $query = PetTreatment::query()
            ->with('pet.client.user:id,name')
            ->withCount(['sessions as completed_sessions_count' => fn (Builder $query) => $query->where('status', 'completed')]);

        if (! $isAdmin) {
            $query->whereHas('pet', fn (Builder $query) => $query->where('client_id', $request->user()->client?->id));
        }

        $treatments = $query
            ->when($filters['search'] ?? null, fn (Builder $query, string $search) => $query->where(function (Builder $query) use ($search): void {
                $query->where('treatment_name', 'like', '%'.$search.'%')
                    ->orWhereHas('pet', fn (Builder $query) => $query->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('pet.client.user', fn (Builder $query) => $query->where('name', 'like', '%'.$search.'%'));
            }))
            ->when($isAdmin && ($filters['service'] ?? null), fn (Builder $query, int $service) => $query->whereHas('treatment', fn (Builder $query) => $query->where('service_id', $service)))
            ->when($filters['pet'] ?? null, fn (Builder $query, int $pet) => $query->where('pet_id', $pet))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn (PetTreatment $treatment): array => [
                'id' => $treatment->id,
                'treatment_name' => $treatment->treatment_name,
                'planned_sessions' => $treatment->planned_sessions,
                'completed_sessions_count' => $treatment->completed_sessions_count,
                'status' => $treatment->status,
                'starts_on' => $treatment->starts_on->toDateString(),
                'pet' => [
                    'id' => $treatment->pet->id,
                    'name' => $treatment->pet->name,
                    ...($isAdmin ? ['client_name' => $treatment->pet->client->user->name] : []),
                ],
            ]);

        return Inertia::render('treatments/index', [
            'isAdmin' => $isAdmin,
            'treatments' => $treatments,
            'services' => $isAdmin ? Service::query()->orderBy('name')->orderBy('id')->get(['id', 'name']) : [],
            'pets' => $isAdmin ? [] : $request->user()->client?->pets()->orderBy('name')->orderBy('id')->get(['id', 'name']) ?? [],
            'filters' => [
                'search' => $filters['search'] ?? '',
                'service' => isset($filters['service']) ? (string) $filters['service'] : '',
                'pet' => isset($filters['pet']) ? (string) $filters['pet'] : '',
                'status' => $filters['status'] ?? '',
            ],
        ]);
    }
}
