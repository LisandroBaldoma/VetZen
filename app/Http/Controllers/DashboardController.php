<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        if ($request->user()->hasRole('admin')) {
            return Inertia::render('admin/dashboard');
        }

        if ($request->user()->hasRole('client')) {
            return Inertia::render('client/dashboard', [
                'client' => $request->user()->client,
            ]);
        }

        abort(403);
    }
}
