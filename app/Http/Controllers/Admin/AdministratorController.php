<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdministratorController extends Controller
{
    public function index(Request $request): Response
    {
        $administrators = User::role('admin')
            ->select(['id', 'name', 'email', 'google_id', 'two_factor_confirmed_at', 'created_at'])
            ->withExists('client')
            ->orderBy('name')
            ->orderBy('id')
            ->get();

        return Inertia::render('admin/administrators/index', [
            'administrators' => $administrators->map(fn (User $administrator): array => [
                'id' => $administrator->id,
                'name' => $administrator->name,
                'email' => $administrator->email,
                'googleLinked' => $administrator->google_id !== null,
                'twoFactorEnabled' => $administrator->two_factor_confirmed_at !== null,
                'hasClientProfile' => $administrator->client_exists,
                'isCurrentUser' => $administrator->is($request->user()),
                'canDemote' => ! $administrator->is($request->user()) && $administrator->client_exists,
                'createdAt' => $administrator->created_at->toIso8601String(),
            ])->all(),
        ]);
    }
}
