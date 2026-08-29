<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int|null $duration_minutes
 * @property string|null $price
 * @property string $currency
 * @property array<int, string> $modalities
 * @property bool $is_active
 */
#[Fillable(['name', 'description', 'duration_minutes', 'price', 'currency', 'modalities', 'is_active'])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    protected $attributes = [
        'currency' => 'ARS',
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'price' => 'decimal:2',
            'modalities' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
