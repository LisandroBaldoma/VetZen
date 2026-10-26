<?php

namespace App\Services;

use App\Models\AssistantKnowledgeChunk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AssistantKnowledgeBaseSearch
{
    private const int PROCEDURAL_HEADING_BOOST = 7;

    private const int UNAVAILABLE_DOCUMENT_BOOST = 20;

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

    public function search(string $query, string $role, ?string $module = null, int $limit = 5): AssistantKnowledgeBaseSearchResult
    {
        if (! in_array($role, ['admin', 'client'], true) || $limit < 1) {
            return new AssistantKnowledgeBaseSearchResult(collect());
        }

        $terms = $this->terms($query);

        if ($terms === []) {
            $this->trace($role, $module, $terms, collect(), 'no_search_terms');

            return new AssistantKnowledgeBaseSearchResult(collect());
        }

        $chunks = AssistantKnowledgeChunk::query()
            ->with('document')
            ->whereHas('document', function ($document) use ($role): void {
                $document->whereIn('status', ['available', 'unavailable'])
                    ->where('roles_index', 'like', '%|'.$role.'|%');
            })
            ->get()
            ->map(function (AssistantKnowledgeChunk $chunk) use ($terms, $module): AssistantKnowledgeChunk {
                $score = collect($terms)->sum(
                    fn (string $term): int => substr_count($chunk->search_text, $term),
                );

                if ($chunk->document->status === 'unavailable' && $this->matchesDocumentMetadata($chunk, $terms)) {
                    $score += self::UNAVAILABLE_DOCUMENT_BOOST;
                }

                if ($module !== null && $chunk->document->module === $module) {
                    $score += 3;
                }

                if ($score > 0 && $this->isProceduralHeading($chunk->heading)) {
                    $score += self::PROCEDURAL_HEADING_BOOST;
                }

                $chunk->setAttribute('relevance', $score);

                return $chunk;
            })
            ->filter(fn (AssistantKnowledgeChunk $chunk): bool => $chunk->getAttribute('relevance') > 0)
            ->sortByDesc(fn (AssistantKnowledgeChunk $chunk): int => $chunk->getAttribute('relevance'))
            ->take($limit)
            ->values();

        $this->trace($role, $module, $terms, $chunks, $chunks->isEmpty() ? 'no_matches' : 'matched');

        return new AssistantKnowledgeBaseSearchResult($chunks);
    }

    private function isProceduralHeading(?string $heading): bool
    {
        return Str::lower(Str::ascii($heading ?? '')) === 'como hacerlo';
    }

    /** @param array<int, string> $terms */
    private function matchesDocumentMetadata(AssistantKnowledgeChunk $chunk, array $terms): bool
    {
        $metadata = Str::lower(Str::ascii(implode(' ', [
            $chunk->document->title,
            implode(' ', $chunk->document->keywords),
        ])));

        return collect($terms)->contains(fn (string $term): bool => str_contains($metadata, $term));
    }

    /**
     * @param  array<int, string>  $terms
     * @param  Collection<int, AssistantKnowledgeChunk>  $chunks
     */
    private function trace(string $role, ?string $module, array $terms, Collection $chunks, string $outcome): void
    {
        if (! config('simon.trace_logging')) {
            return;
        }

        Log::info('simon.retrieval.completed', [
            'role' => $role,
            'module' => $module,
            'outcome' => $outcome,
            'terms_count' => count($terms),
            ...new AssistantKnowledgeBaseSearchResult($chunks)->metrics(),
        ]);
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
