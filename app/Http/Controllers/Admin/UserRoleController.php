<?php

namespace App\Http\Controllers\Admin;

use App\Actions\ChangeUserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    public function update(UpdateUserRoleRequest $request, User $user, ChangeUserRole $changeUserRole): RedirectResponse
    {
        $updatedUser = $changeUserRole->handle($request->user(), $user, $request->validated('role'));

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $updatedUser->hasRole('admin')
                ? __('Acceso administrativo concedido.')
                : __('Acceso administrativo retirado.'),
        ]);

        if ($updatedUser->hasRole('admin')) {
            return to_route('admin.administrators.index');
        }

        return to_route('admin.clients.edit', $updatedUser->client);
    }
}
