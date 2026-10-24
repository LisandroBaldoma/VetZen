<?php

namespace Database\Factories;

use App\Models\AssistantKnowledgeDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AssistantKnowledgeDocument> */
class AssistantKnowledgeDocumentFactory extends Factory
{
    public function definition(): array
    {
        $documentId = fake()->unique()->slug(3);

        return [
            'document_id' => $documentId,
            'path' => "client/{$documentId}.md",
            'title' => fake()->sentence(3),
            'module' => 'pets',
            'roles_index' => '|client|',
            'roles' => ['client'],
            'route_names' => ['pets.index'],
            'keywords' => [fake()->word()],
            'status' => 'available',
            'feature_ids' => ['05'],
            'source_refs' => ['routes/web.php'],
            'reviewed_at' => fake()->date(),
            'content' => fake()->paragraph(),
            'content_hash' => hash('sha256', fake()->uuid()),
        ];
    }
}
