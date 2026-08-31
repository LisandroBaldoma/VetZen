<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Treatment;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TreatmentCatalogController extends Controller
{
    public function __invoke(): Response
    {
        Gate::authorize('viewAny', Treatment::class);

        return Inertia::render('admin/treatments/index', [
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
            'treatments' => Treatment::query()
                ->with('service:id,name')
                ->withCount('procedures')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
