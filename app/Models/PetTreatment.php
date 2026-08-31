<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['treatment_name', 'treatment_description', 'planned_sessions', 'default_session_price', 'currency', 'starts_on', 'status', 'notes'])]
class PetTreatment extends Model
{
    use HasFactory;

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class);
    }

    public function procedureSnapshots(): HasMany
    {
        return $this->hasMany(PetTreatmentProcedure::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TreatmentSession::class);
    }

    public function serviceRequest(): HasOne
    {
        return $this->hasOne(ServiceRequest::class);
    }

    protected function casts(): array
    {
        return [
            'planned_sessions' => 'integer',
            'default_session_price' => 'decimal:2',
            'starts_on' => 'date',
        ];
    }
}
