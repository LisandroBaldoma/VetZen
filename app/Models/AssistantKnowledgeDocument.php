<?php

namespace App\Models;

use Database\Factories\AssistantKnowledgeDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'document_id',
    'path',
    'title',
    'module',
    'roles_index',
    'roles',
    'route_names',
    'keywords',
    'status',
    'grounding',
    'feature_ids',
    'source_refs',
    'reviewed_at',
    'content',
    'content_hash',
])]
class AssistantKnowledgeDocument extends Model
{
    /** @use HasFactory<AssistantKnowledgeDocumentFactory> */
    use HasFactory;

    public function chunks(): HasMany
    {
        return $this->hasMany(AssistantKnowledgeChunk::class);
    }

    protected function casts(): array
    {
        return [
            'roles' => 'array',
            'route_names' => 'array',
            'keywords' => 'array',
            'feature_ids' => 'array',
            'source_refs' => 'array',
            'reviewed_at' => 'date',
        ];
    }
}
