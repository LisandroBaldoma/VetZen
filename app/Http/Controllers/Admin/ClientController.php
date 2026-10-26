<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/clients/index', [
            'clients' => Client::query()
                ->with('user')
                ->whereHas('user', fn ($query) => $query->role('client'))
                ->whereDoesntHave('user.roles', fn ($query) => $query->where('name', 'admin'))
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function edit(Client $client): Response
    {
        $this->ensureUserIsClient($client);

        return Inertia::render('admin/clients/edit', [
            'client' => $client->load('user'),
            'canPromote' => $client->user->hasRole('client') && ! $client->user->hasRole('admin'),
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->ensureUserIsClient($client);

        $client->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Cliente actualizado.')]);

        return to_route('admin.clients.edit', $client);
    }

    private function ensureUserIsClient(Client $client): void
    {
        abort_unless(
            $client->user->hasRole('client') && ! $client->user->hasRole('admin'),
            404,
        );
    }
}
