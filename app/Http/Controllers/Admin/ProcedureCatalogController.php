<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Procedure\ListProceduresRequest;
use App\Models\Procedure;
use App\Models\Service;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ProcedureCatalogController extends Controller
{
    public function __invoke(ListProceduresRequest $request): Response
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
        Gate::authorize('viewAny', Procedure::class);

        $filters = $request->safe()->only(['search', 'service', 'status']);

        $procedures = Procedure::query()
            ->with('service:id,name')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query
                ->where('name', 'like', '%'.$search.'%'))
            ->when($filters['service'] ?? null, fn ($query, $service) => $query
                ->where('service_id', $service))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query
                ->where('is_active', $status === 'active'))
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/procedures/index', [
            'procedures' => $procedures,
            'services' => Service::query()->orderBy('name')->orderBy('id')->get(['id', 'name']),
            'filters' => [
                'search' => $filters['search'] ?? '',
                'service' => isset($filters['service']) ? (string) $filters['service'] : '',
                'status' => $filters['status'] ?? '',
            ],
        ]);
    }
}
