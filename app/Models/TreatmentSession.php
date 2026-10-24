<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['session_number', 'scheduled_at', 'price', 'currency', 'status', 'notes'])]
class TreatmentSession extends Model
{
    use HasFactory;

    public function petTreatment(): BelongsTo
    {
        return $this->belongsTo(PetTreatment::class);
    }

    public function clinicalRecords(): HasMany
    {
        return $this->hasMany(ClinicalRecord::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    protected function casts(): array
    {
        return ['session_number' => 'integer', 'scheduled_at' => 'datetime', 'price' => 'decimal:2'];
    }
}
