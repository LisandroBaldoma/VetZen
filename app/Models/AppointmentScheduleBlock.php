<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['starts_at', 'ends_at'])]
class AppointmentScheduleBlock extends Model
{
    public function scheduleDate(): BelongsTo
    {
        return $this->belongsTo(AppointmentScheduleDate::class, 'appointment_schedule_date_id');
    }
}
