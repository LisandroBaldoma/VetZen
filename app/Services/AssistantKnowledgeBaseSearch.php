<?php

namespace App\Services;

use App\Models\AssistantKnowledgeChunk;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AssistantKnowledgeBaseSearch
{
    /** @var array<int, string> */
    private const array STOP_WORDS = [
        'como',
        'cual',
        'cuales',
        'cuando',
        'donde',
        'para',
        'porque',
        'que',
        'quiero',
    ];

    /**
     * @return Collection<int, AssistantKnowledgeChunk>
     */
    public function search(string $query, string $role, ?string $module = null, int $limit = 5): Collection
    {
        if (! in_array($role, ['admin', 'client'], true) || $limit < 1) {
            return collect();
        }

        $terms = $this->terms($query);

        if ($terms === []) {
            return collect();
        }

        return AssistantKnowledgeChunk::query()
            ->with('document')
            ->whereHas('document', function ($document) use ($role): void {
                $document->where('status', 'available')
                    ->where('roles_index', 'like', '%|'.$role.'|%');
            })
            ->get()
            ->map(function (AssistantKnowledgeChunk $chunk) use ($terms, $module): AssistantKnowledgeChunk {
                $score = collect($terms)->sum(
                    fn (string $term): int => substr_count($chunk->search_text, $term),
                );

                if ($module !== null && $chunk->document->module === $module) {
                    $score += 3;
                }

                $chunk->setAttribute('relevance', $score);

                return $chunk;
            })
            ->filter(fn (AssistantKnowledgeChunk $chunk): bool => $chunk->getAttribute('relevance') > 0)
            ->sortByDesc(fn (AssistantKnowledgeChunk $chunk): int => $chunk->getAttribute('relevance'))
            ->take($limit)
            ->values();
    }

    /** @return array<int, string> */
    private function terms(string $query): array
    {
        return collect(preg_split('/\s+/', Str::lower(Str::ascii($query))) ?: [])
            ->map(fn (string $term): string => trim($term, " \t\n\r\0\x0B.,;:!?¿¡()[]{}\"'"))
            ->filter(fn (string $term): bool => Str::length($term) >= 3)
            ->reject(fn (string $term): bool => in_array($term, self::STOP_WORDS, true))
            ->unique()
            ->values()
            ->all();
    }
}
