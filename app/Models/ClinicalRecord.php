<?php

namespace App\Models;

use Database\Factories\ClinicalRecordFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'title', 'content', 'occurred_at', 'is_visible_to_client'])]
class ClinicalRecord extends Model
{
    /** @use HasFactory<ClinicalRecordFactory> */
    use HasFactory;

    public const TYPES = [
        'consultation',
        'evaluation',
        'evolution',
        'session',
        'other',
    ];

    /**
     * @return BelongsTo<Pet, $this>
     */
    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return HasMany<ClinicalRecordAudit, $this>
     */
    public function audits(): HasMany
    {
        return $this->hasMany(ClinicalRecordAudit::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'is_visible_to_client' => 'boolean',
        ];
    }
}
