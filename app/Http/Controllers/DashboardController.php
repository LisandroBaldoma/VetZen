<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        if ($request->user()->can('clients.viewAny')) {
            return Inertia::render('admin/dashboard');
        }

        return Inertia::render('client/dashboard', [
            'client' => $request->user()->client,
        ]);
    }
}
