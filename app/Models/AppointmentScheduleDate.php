<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['date', 'capacity'])]
class AppointmentScheduleDate extends Model
{
    use HasFactory;

    public function blocks(): HasMany
    {
        return $this->hasMany(AppointmentScheduleBlock::class);
    }

    public function starts(): HasMany
    {
        return $this->hasMany(AppointmentScheduleStart::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'capacity' => 'integer',
        ];
    }
}
