<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditLogger
{
    /** @param array<string, mixed>|null $oldValues @param array<string, mixed>|null $newValues */
    public function record(?User $user, string $action, Model $auditable, ?array $oldValues = null, ?array $newValues = null): void
    {
        $audit = new AuditLog([
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
        $audit->user()->associate($user);
        $auditable->morphMany(AuditLog::class, 'auditable')->save($audit);
    }
}
