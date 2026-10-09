<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'service_name',
    'duration_minutes',
    'starts_at',
    'ends_at',
    'status',
])]
class Appointment extends Model
{
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scheduleDate(): BelongsTo
    {
        return $this->belongsTo(AppointmentScheduleDate::class, 'appointment_schedule_date_id');
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /** @return HasMany<AppointmentStatusHistory, $this> */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(AppointmentStatusHistory::class);
    }

    /** @return HasMany<AppointmentRescheduleHistory, $this> */
    public function rescheduleHistory(): HasMany
    {
        return $this->hasMany(AppointmentRescheduleHistory::class);
    }

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }
}
