<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'position',
    'heading',
    'content',
    'search_text',
])]
class AssistantKnowledgeChunk extends Model
{
    public function document(): BelongsTo
    {
        return $this->belongsTo(AssistantKnowledgeDocument::class, 'assistant_knowledge_document_id');
    }
}
