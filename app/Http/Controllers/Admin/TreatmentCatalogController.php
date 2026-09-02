<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Treatment\ListTreatmentsRequest;
use App\Models\Service;
use App\Models\Treatment;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TreatmentCatalogController extends Controller
{
    public function __invoke(ListTreatmentsRequest $request): Response
    {
        Gate::authorize('viewAny', Treatment::class);

        $filters = $request->safe()->only(['search', 'service', 'status']);

        return Inertia::render('admin/treatments/index', [
            'services' => Service::query()
                ->orderBy('name')
                ->orderBy('id')
                ->get(['id', 'name', 'is_active']),
            'creationServices' => Service::query()
                ->where('is_active', true)
                ->whereHas('procedures', fn ($query) => $query->where('is_active', true))
                ->orderBy('name')
                ->orderBy('id')
                ->get(['id', 'name']),
            'treatments' => Treatment::query()
                ->with('service:id,name')
                ->withCount('procedures')
                ->when($filters['search'] ?? null, fn ($query, $search) => $query
                    ->where('name', 'like', '%'.$search.'%'))
                ->when($filters['service'] ?? null, fn ($query, $service) => $query
                    ->where('service_id', $service))
                ->when($filters['status'] ?? null, fn ($query, $status) => $query
                    ->where('is_active', $status === 'active'))
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(10)
                ->withQueryString(),
            'filters' => [
                'search' => $filters['search'] ?? '',
                'service' => isset($filters['service']) ? (string) $filters['service'] : '',
                'status' => $filters['status'] ?? '',
            ],
        ]);
    }
}
