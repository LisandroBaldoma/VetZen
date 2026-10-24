<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['starts_at', 'ends_at'])]
class AppointmentScheduleTemplateBlock extends Model
{
    public function template(): BelongsTo
    {
        return $this->belongsTo(AppointmentScheduleTemplate::class, 'appointment_schedule_template_id');
    }
}
