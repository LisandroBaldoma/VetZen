<?php

namespace App\Models;

use Database\Factories\PetFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $species
 * @property string|null $breed
 * @property string $sex
 * @property string|null $birth_date
 * @property string|null $weight
 * @property string|null $color
 * @property string|null $notes
 * @property string|null $photo
 */
#[Fillable(['name', 'species', 'breed', 'sex', 'birth_date', 'weight', 'color', 'notes', 'photo'])]
class Pet extends Model
{
    /** @use HasFactory<PetFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany<ClinicalRecord, $this>
     */
    public function clinicalRecords(): HasMany
    {
        return $this->hasMany(ClinicalRecord::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'weight' => 'decimal:2',
        ];
    }
}
