<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['procedure_id', 'procedure_name', 'procedure_description'])]
class PetTreatmentProcedure extends Model
{
    public function petTreatment(): BelongsTo
    {
        return $this->belongsTo(PetTreatment::class);
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }
}
