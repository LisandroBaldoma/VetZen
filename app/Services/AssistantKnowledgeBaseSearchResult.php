<?php

namespace App\Services;

use App\Models\AssistantKnowledgeChunk;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AssistantKnowledgeBaseSearchResult
{
    /** @param Collection<int, AssistantKnowledgeChunk> $chunks */
    public function __construct(public readonly Collection $chunks) {}

    public function outcome(): string
    {
        if ($this->chunks->isEmpty()) {
            return 'no_evidence';
        }

        $grounding = $this->chunks->first()->document->grounding;

        return in_array($grounding, ['unavailable', 'not_allowed_for_role'], true)
            ? $grounding
            : 'available';
    }

    /** @return array{chunks_count: int, unique_documents_count: int, retrieved_characters: int, retrieved_words: int} */
    public function metrics(): array
    {
        $contents = $this->chunks->pluck('content');

        return [
            'chunks_count' => $this->chunks->count(),
            'unique_documents_count' => $this->chunks->pluck('document.document_id')->unique()->count(),
            'retrieved_characters' => $contents->sum(fn (string $content): int => Str::length($content)),
            'retrieved_words' => $contents->sum(fn (string $content): int => count(preg_split('/\s+/u', trim($content)) ?: [])),
        ];
    }
}
