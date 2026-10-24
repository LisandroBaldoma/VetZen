<?php

namespace App\Actions;

use App\Models\User;
use App\Models\UserRoleChange;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class ChangeUserRole
{
    public function handle(User $actor, User $subject, string $newRole): User
    {
        return DB::transaction(function () use ($actor, $subject, $newRole): User {
            $users = User::query()
                ->whereKey([$actor->getKey(), $subject->getKey()])
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $lockedActor = $users->get($actor->getKey());
            $lockedSubject = $users->get($subject->getKey());

            if (! $lockedActor?->hasRole('admin')) {
                abort(403);
            }

            if ($lockedActor->is($lockedSubject)) {
                throw ValidationException::withMessages([
                    'role' => __('No podés cambiar tu propio rol.'),
                ]);
            }

            $currentRole = $lockedSubject->hasRole('admin')
                ? 'admin'
                : ($lockedSubject->hasRole('client') ? 'client' : null);

            if ($currentRole === null || $currentRole === $newRole) {
                throw ValidationException::withMessages([
                    'role' => __('El cambio de rol solicitado no es válido.'),
                ]);
            }

            if ($newRole === 'client' && ! $lockedSubject->client()->exists()) {
                throw ValidationException::withMessages([
                    'role' => __('Este administrador no tiene un perfil de cliente al cual regresar.'),
                ]);
            }

            if ($currentRole === 'admin') {
                $this->ensureAnotherAdministratorExists($lockedSubject);
            }

            $lockedSubject->syncRoles([$newRole]);

            UserRoleChange::create([
                'subject_user_id' => $lockedSubject->id,
                'actor_user_id' => $lockedActor->id,
                'subject_name' => $lockedSubject->name,
                'subject_email' => $lockedSubject->email,
                'actor_name' => $lockedActor->name,
                'actor_email' => $lockedActor->email,
                'from_role' => $currentRole,
                'to_role' => $newRole,
            ]);

            return $lockedSubject->refresh();
        });
    }

    private function ensureAnotherAdministratorExists(User $subject): void
    {
        $adminRole = Role::findByName('admin', 'web');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $roleKey = $columnNames['role_pivot_key'] ?? 'role_id';
        $modelKey = $columnNames['model_morph_key'] ?? 'model_id';

        $administratorIds = DB::table($tableNames['model_has_roles'])
            ->where($roleKey, $adminRole->id)
            ->where('model_type', $subject->getMorphClass())
            ->lockForUpdate()
            ->pluck($modelKey);

        if ($administratorIds->count() <= 1) {
            throw ValidationException::withMessages([
                'role' => __('La plataforma debe conservar al menos un administrador.'),
            ]);
        }
    }
}
