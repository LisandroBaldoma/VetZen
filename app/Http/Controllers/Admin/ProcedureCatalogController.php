<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ProcedureCatalogController extends Controller
{
    public function __invoke(): Response
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
        Gate::authorize('viewAny', Procedure::class);

        return Inertia::render('admin/procedures/index', [
            'procedures' => Procedure::query()
                ->with('service:id,name')
                ->orderBy('name')
                ->orderBy('id')
                ->get(),
        ]);
    }
}
