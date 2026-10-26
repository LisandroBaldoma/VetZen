<?php

namespace Tests\Feature\Simon;

use App\Models\AssistantKnowledgeDocument;
use App\Services\AssistantKnowledgeBaseIndexer;
use App\Services\AssistantKnowledgeBaseSearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Tests\TestCase;

class AssistantKnowledgeBaseTest extends TestCase
{
    use RefreshDatabase;

    private string $knowledgeBasePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->knowledgeBasePath = storage_path('framework/testing-simon-knowledge-base');
        File::deleteDirectory($this->knowledgeBasePath);
        File::ensureDirectoryExists($this->knowledgeBasePath);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->knowledgeBasePath);

        parent::tearDown();
    }

    public function test_command_indexes_help_documents_and_chunks(): void
    {
        $this->writeDocument('client/solicitar-atencion.md', 'client.solicitar-atencion', ['client'], 'service-requests', 'Solicitar atención', ['solicitar atención'], <<<'MARKDOWN'
# Solicitar atención

## Cómo hacerlo

1. Abrí Servicios disponibles.

## Importante

No reserva un turno.
MARKDOWN);

        $this->artisan('simon:index-knowledge-base', ['--path' => $this->knowledgeBasePath])
            ->expectsOutput('Indexed 1 Knowledge Base documents.')
            ->assertSuccessful();

        $document = AssistantKnowledgeDocument::query()->sole();

        $this->assertSame('client.solicitar-atencion', $document->document_id);
        $this->assertSame(['client'], $document->roles);
        $this->assertSame(3, $document->chunks()->count());
    }

    public function test_sync_removes_documents_for_deleted_help_files(): void
    {
        $this->writeDocument('client/mascotas.md', 'client.mascotas', ['client'], 'pets', 'Gestionar mascotas', ['mascotas'], '# Gestionar mascotas');
        $this->writeDocument('admin/pacientes.md', 'admin.pacientes', ['admin'], 'pets', 'Gestionar pacientes', ['pacientes'], '# Gestionar pacientes');

        app(AssistantKnowledgeBaseIndexer::class)->sync($this->knowledgeBasePath);

        File::delete($this->knowledgeBasePath.'/client/mascotas.md');
        app(AssistantKnowledgeBaseIndexer::class)->sync($this->knowledgeBasePath);

        $this->assertDatabaseMissing('assistant_knowledge_documents', ['document_id' => 'client.mascotas']);
        $this->assertDatabaseHas('assistant_knowledge_documents', ['document_id' => 'admin.pacientes']);
    }

    public function test_search_filters_results_by_role_before_scoring(): void
    {
        $this->writeDocument('client/solicitudes.md', 'client.solicitudes', ['client'], 'service-requests', 'Consultar solicitudes', ['solicitud'], <<<'MARKDOWN'
# Consultar solicitudes

## Cómo hacerlo

Podés consultar el estado de tu solicitud de atención.
MARKDOWN);
        $this->writeDocument('admin/resolver.md', 'admin.resolver-solicitudes', ['admin'], 'service-requests', 'Resolver solicitudes', ['resolver solicitud'], <<<'MARKDOWN'
# Resolver solicitudes

## Cómo hacerlo

Podés resolver una solicitud pendiente con una plantilla compatible.
MARKDOWN);

        app(AssistantKnowledgeBaseIndexer::class)->sync($this->knowledgeBasePath);

        $adminResults = app(AssistantKnowledgeBaseSearch::class)->search('resolver solicitud', 'admin');
        $clientResults = app(AssistantKnowledgeBaseSearch::class)->search('resolver solicitud', 'client');

        $this->assertSame(['admin.resolver-solicitudes'], $adminResults->pluck('document.document_id')->unique()->values()->all());
        $this->assertSame(['client.solicitudes'], $clientResults->pluck('document.document_id')->unique()->values()->all());
    }

    public function test_search_prioritizes_the_current_module_when_text_matches(): void
    {
        $this->writeDocument('client/solicitudes.md', 'client.solicitudes', ['client'], 'service-requests', 'Solicitud de atención', ['solicitud'], '# Solicitud\n\n## Estado\n\nRevisá la solicitud de atención.');
        $this->writeDocument('client/mascotas.md', 'client.mascotas', ['client'], 'pets', 'Solicitud desde mascota', ['solicitud'], '# Solicitud\n\n## Estado\n\nRevisá la solicitud de atención.');

        app(AssistantKnowledgeBaseIndexer::class)->sync($this->knowledgeBasePath);

        $results = app(AssistantKnowledgeBaseSearch::class)->search('solicitud atención', 'client', 'pets');

        $this->assertSame('client.mascotas', $results->first()->document->document_id);
    }

    public function test_indexer_rejects_documents_without_required_metadata(): void
    {
        File::put($this->knowledgeBasePath.'/invalid.md', "---\nid: invalid\n---\n\n# Invalid");

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('missing [title] metadata');

        app(AssistantKnowledgeBaseIndexer::class)->sync($this->knowledgeBasePath);
    }

    public function test_project_help_documents_are_indexable_and_retrievable(): void
    {
        $indexed = app(AssistantKnowledgeBaseIndexer::class)->sync(base_path('docs/help'));

        $results = app(AssistantKnowledgeBaseSearch::class)->search('completar sesión', 'admin', 'treatments');

        $this->assertGreaterThan(0, $indexed);
        $this->assertSame('admin.gestionar-sesiones', $results->first()->document->document_id);
        $this->assertSame(
            'not_allowed_for_role',
            AssistantKnowledgeDocument::query()
                ->where('document_id', 'client.consultar-tratamientos-y-sesiones')
                ->sole()
                ->grounding,
        );
    }

    public function test_evaluation_dataset_retrieves_its_expected_documents(): void
    {
        app(AssistantKnowledgeBaseIndexer::class)->sync(base_path('docs/help'));

        /** @var array<int, array{id: string, role: string, question: string, expected_documents: array<int, string>}> $cases */
        $cases = require base_path('tests/Fixtures/Simon/evaluation.php');

        $this->assertCount(11, $cases);

        foreach ($cases as $case) {
            $results = app(AssistantKnowledgeBaseSearch::class)->search($case['question'], $case['role']);
            $documentIds = $results->pluck('document.document_id')->unique()->values()->all();

            foreach ($case['expected_documents'] as $documentId) {
                $this->assertContains($documentId, $documentIds, $case['id']);
            }
        }
    }

    public function test_procedural_chunks_are_prioritized_for_c2_and_a4_without_module_context(): void
    {
        app(AssistantKnowledgeBaseIndexer::class)->sync(base_path('docs/help'));
        $search = app(AssistantKnowledgeBaseSearch::class);

        $clientResult = $search->search('Quiero que atiendan a mi mascota, ¿cómo solicito atención?', 'client')->first();
        $adminResult = $search->search('¿Cómo registro o completo una sesión de un tratamiento?', 'admin')->first();

        $this->assertSame('client.solicitar-atencion', $clientResult?->document->document_id);
        $this->assertSame('Cómo hacerlo', $clientResult?->heading);
        $this->assertSame('admin.gestionar-sesiones', $adminResult?->document->document_id);
        $this->assertSame('Cómo hacerlo', $adminResult?->heading);
    }

    public function test_retrieval_trace_reports_aggregate_context_metrics_without_content(): void
    {
        $this->writeDocument('client/solicitudes.md', 'client.solicitudes', ['client'], 'service-requests', 'Solicitudes', ['solicitud'], "# Solicitudes\n\n## Cómo hacerlo\n\nCreá una solicitud.");
        app(AssistantKnowledgeBaseIndexer::class)->sync($this->knowledgeBasePath);
        config(['simon.trace_logging' => true]);
        Log::spy();

        app(AssistantKnowledgeBaseSearch::class)->search('solicitud', 'client');

        Log::shouldHaveReceived('info')
            ->withArgs(function (string $event, array $context): bool {
                return $event === 'simon.retrieval.completed'
                    && $context['outcome'] === 'matched'
                    && $context['chunks_count'] === 2
                    && $context['unique_documents_count'] === 1
                    && $context['retrieved_characters'] > 0
                    && $context['retrieved_words'] > 0
                    && ! in_array('Creá una solicitud.', $context, true);
            })
            ->once();
    }

    /** @param array<int, string> $roles */
    private function writeDocument(
        string $path,
        string $id,
        array $roles,
        string $module,
        string $title,
        array $keywords,
        string $content,
    ): void {
        $file = $this->knowledgeBasePath.'/'.$path;

        File::ensureDirectoryExists(dirname($file));
        File::put($file, implode("\n", [
            '---',
            "id: {$id}",
            "title: {$title}",
            'roles: ['.implode(', ', $roles).']',
            "module: {$module}",
            'route_names: [pets.index]',
            'keywords: ['.implode(', ', $keywords).']',
            'status: available',
            'feature_ids: [08]',
            'source_refs: [routes/web.php]',
            'reviewed_at: 2026-10-06',
            '---',
            '',
            $content,
            '',
        ]));
    }
}
