<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['old_starts_at', 'old_ends_at', 'new_starts_at', 'new_ends_at'])]
class AppointmentRescheduleHistory extends Model
{
    public const UPDATED_AT = null;

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    protected function casts(): array
    {
        return ['old_starts_at' => 'datetime', 'old_ends_at' => 'datetime', 'new_starts_at' => 'datetime', 'new_ends_at' => 'datetime'];
    }
}
