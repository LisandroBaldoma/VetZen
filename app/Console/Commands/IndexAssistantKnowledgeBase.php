<?php

namespace App\Console\Commands;

use App\Services\AssistantKnowledgeBaseIndexer;
use Illuminate\Console\Command;

class IndexAssistantKnowledgeBase extends Command
{
    protected $signature = 'simon:index-knowledge-base {--path= : Absolute path to the Knowledge Base directory}';

    protected $description = 'Index the curated Simón Knowledge Base documents';

    public function handle(AssistantKnowledgeBaseIndexer $indexer): int
    {
        $path = $this->option('path') ?: base_path('docs/help');
        $documents = $indexer->sync($path);

        $this->info("Indexed {$documents} Knowledge Base documents.");

        return self::SUCCESS;
    }
}
