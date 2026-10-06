<?php

namespace App\Services;

use App\Models\AssistantKnowledgeDocument;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AssistantKnowledgeBaseIndexer
{
    private const array REQUIRED_METADATA = [
        'id',
        'title',
        'roles',
        'module',
        'route_names',
        'keywords',
        'status',
        'feature_ids',
        'source_refs',
        'reviewed_at',
    ];

    /** @var array<int, string> */
    private const array ROLES = ['admin', 'client'];

    /** @var array<int, string> */
    private const array STATUSES = ['available', 'unavailable'];

    /** @var array<int, string> */
    private const array GROUNDINGS = ['allowed', 'unavailable', 'not_allowed_for_role'];

    public function __construct(private Filesystem $files) {}

    public function sync(string $path): int
    {
        if (! $this->files->isDirectory($path)) {
            throw new InvalidArgumentException("Knowledge Base directory [{$path}] does not exist.");
        }

        return DB::transaction(function () use ($path): int {
            $indexedPaths = collect($this->files->allFiles($path))
                ->filter(fn (\SplFileInfo $file): bool => $file->getExtension() === 'md')
                ->reject(fn (\SplFileInfo $file): bool => $file->getFilename() === 'README.md')
                ->map(fn (\SplFileInfo $file): string => $file->getPathname())
                ->map(function (string $file) use ($path): string {
                    $this->index($file, $path);

                    return $this->relativePath($file, $path);
                })
                ->values();

            AssistantKnowledgeDocument::query()
                ->when(
                    $indexedPaths->isNotEmpty(),
                    fn ($query) => $query->whereNotIn('path', $indexedPaths),
                )
                ->delete();

            return $indexedPaths->count();
        });
    }

    private function index(string $file, string $basePath): void
    {
        $markdown = $this->files->get($file);
        $parsed = $this->parse($markdown, $file);
        $metadata = $parsed['metadata'];
        $content = $parsed['content'];
        $path = $this->relativePath($file, $basePath);

        $document = AssistantKnowledgeDocument::query()->updateOrCreate(
            ['path' => $path],
            [
                'document_id' => $metadata['id'],
                'title' => $metadata['title'],
                'module' => $metadata['module'],
                'roles_index' => '|'.implode('|', $metadata['roles']).'|',
                'roles' => $metadata['roles'],
                'route_names' => $metadata['route_names'],
                'keywords' => $metadata['keywords'],
                'status' => $metadata['status'],
                'grounding' => $metadata['grounding'],
                'feature_ids' => $metadata['feature_ids'],
                'source_refs' => $metadata['source_refs'],
                'reviewed_at' => $metadata['reviewed_at'],
                'content' => $content,
                'content_hash' => hash('sha256', $markdown),
            ],
        );

        $document->chunks()->delete();

        foreach ($this->chunks($content) as $position => $chunk) {
            $document->chunks()->create([
                'position' => $position + 1,
                'heading' => $chunk['heading'],
                'content' => $chunk['content'],
                'search_text' => $this->normalize(implode(' ', [
                    $metadata['title'],
                    $metadata['module'],
                    implode(' ', $metadata['keywords']),
                    $chunk['heading'] ?? '',
                    $chunk['content'],
                ])),
            ]);
        }
    }

    /**
     * @return array{metadata: array<string, array<int, string>|string>, content: string}
     */
    private function parse(string $markdown, string $file): array
    {
        if (preg_match('/\A---\R(?<frontmatter>.*?)\R---\R?(?<content>.*)\z/s', $markdown, $matches) !== 1) {
            throw new InvalidArgumentException("Knowledge Base document [{$file}] must have YAML frontmatter.");
        }

        $metadata = $this->parseFrontmatter($matches['frontmatter'], $file);

        foreach (self::REQUIRED_METADATA as $key) {
            if (! array_key_exists($key, $metadata) || $metadata[$key] === '' || $metadata[$key] === []) {
                throw new InvalidArgumentException("Knowledge Base document [{$file}] is missing [{$key}] metadata.");
            }
        }

        if (array_diff($metadata['roles'], self::ROLES) !== []) {
            throw new InvalidArgumentException("Knowledge Base document [{$file}] contains an unsupported role.");
        }

        if (! in_array($metadata['status'], self::STATUSES, true)) {
            throw new InvalidArgumentException("Knowledge Base document [{$file}] contains an unsupported status.");
        }

        $grounding = $metadata['grounding'] ?? ($metadata['status'] === 'unavailable' ? 'unavailable' : 'allowed');

        if (! in_array($grounding, self::GROUNDINGS, true)) {
            throw new InvalidArgumentException("Knowledge Base document [{$file}] contains an unsupported grounding.");
        }

        $metadata['grounding'] = $grounding;

        return [
            'metadata' => $metadata,
            'content' => trim($matches['content']),
        ];
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    private function parseFrontmatter(string $frontmatter, string $file): array
    {
        return collect(preg_split('/\R/', trim($frontmatter)))
            ->filter()
            ->mapWithKeys(function (string $line) use ($file): array {
                if (preg_match('/^(?<key>[a-z_]+):\s*(?<value>.*)$/', $line, $matches) !== 1) {
                    throw new InvalidArgumentException("Knowledge Base document [{$file}] has invalid frontmatter.");
                }

                $value = trim($matches['value']);

                return [$matches['key'] => Str::startsWith($value, '[')
                    ? $this->parseList($value, $file)
                    : trim($value, "\"'")];
            })
            ->all();
    }

    /** @return array<int, string> */
    private function parseList(string $value, string $file): array
    {
        if (! Str::startsWith($value, '[') || ! Str::endsWith($value, ']')) {
            throw new InvalidArgumentException("Knowledge Base document [{$file}] has an invalid list.");
        }

        return collect(explode(',', trim($value, '[]')))
            ->map(fn (string $item): string => trim(trim($item), "\"'"))
            ->filter()
            ->values()
            ->all();
    }

    /** @return array<int, array{heading: string|null, content: string}> */
    private function chunks(string $content): array
    {
        $sections = preg_split('/(?=^##\s+)/m', $content) ?: [];

        return collect($sections)
            ->map(function (string $section): array {
                preg_match('/^##\s+(.+)$/m', $section, $matches);

                return [
                    'heading' => $matches[1] ?? null,
                    'content' => trim($section),
                ];
            })
            ->filter(fn (array $chunk): bool => $chunk['content'] !== '')
            ->values()
            ->all();
    }

    private function normalize(string $value): string
    {
        return Str::lower(Str::ascii($value));
    }

    private function relativePath(string $file, string $basePath): string
    {
        return str_replace(
            DIRECTORY_SEPARATOR,
            '/',
            Str::replaceStart(rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR, '', $file),
        );
    }
}
