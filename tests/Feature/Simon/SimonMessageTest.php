<?php

namespace Tests\Feature\Simon;

use App\Contracts\AssistantResponseGenerator;
use App\Models\Client;
use App\Models\User;
use App\Services\AssistantKnowledgeBaseIndexer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SimonMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.groq.key' => 'test-key',
            'services.groq.base_url' => 'https://api.groq.com/openai/v1',
            'simon.llm.provider' => 'groq',
            'simon.llm.model' => 'test-model',
        ]);

        app(AssistantKnowledgeBaseIndexer::class)->sync(base_path('docs/help'));
    }

    public function test_client_receives_a_grounded_response_with_knowledge_base_sources(): void
    {
        $client = Client::factory()->create();
        $generator = $this->mock(AssistantResponseGenerator::class);

        $generator->shouldReceive('generate')
            ->once()
            ->withArgs(function (string $instructions, string $prompt) use ($client): bool {
                return str_contains($instructions, 'Sos Simón')
                    && str_contains($prompt, 'Rol: client')
                    && str_contains($prompt, 'Registrar y editar una mascota')
                    && ! str_contains($prompt, $client->user->email);
            })
            ->andReturn('Abrí Mis mascotas y elegí la opción para agregar una mascota.');

        $response = $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => '¿Cómo registro una mascota?',
            'module' => 'pets',
        ]);

        $response->assertOk()
            ->assertJsonPath('answer', 'Abrí Mis mascotas y elegí la opción para agregar una mascota.')
            ->assertJsonPath('sources.0.id', 'client.gestionar-mascotas');
    }

    public function test_client_never_sends_admin_only_evidence_to_the_generator(): void
    {
        $client = Client::factory()->create();
        $generator = $this->mock(AssistantResponseGenerator::class);

        $generator->shouldReceive('generate')
            ->once()
            ->withArgs(function (string $instructions, string $prompt): bool {
                return ! str_contains($prompt, 'Revisar y resolver solicitudes')
                    && str_contains($prompt, 'Consultar solicitudes de atención');
            })
            ->andReturn('Podés consultar las solicitudes de atención de tu mascota.');

        $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => '¿Cómo resuelvo una solicitud?',
            'module' => 'service-requests',
        ])->assertOk();

    }

    public function test_question_without_evidence_does_not_call_the_generator(): void
    {
        $generator = $this->mock(AssistantResponseGenerator::class);
        $generator->shouldNotReceive('generate');
        $client = Client::factory()->create();

        $response = $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => '¿Qué medicamento le doy a mi perro?',
        ]);

        $response->assertOk()
            ->assertJsonPath('answer', 'No encuentro información suficiente en la documentación actual de VetZen para indicarte cómo hacer eso.')
            ->assertJsonPath('sources', []);
    }

    public function test_unconfigured_provider_returns_a_safe_unavailable_response(): void
    {
        config(['services.groq.key' => null, 'simon.llm.model' => null]);
        $client = Client::factory()->create();

        $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => '¿Cómo registro una mascota?',
            'module' => 'pets',
        ])->assertStatus(503)
            ->assertJsonPath('message', 'Simón no está disponible en este momento. Intentá nuevamente más tarde.');
    }

    public function test_provider_failure_returns_a_safe_unavailable_response(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response(['error' => ['message' => 'invalid key']], 500),
        ]);
        $client = Client::factory()->create();

        $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => '¿Cómo registro una mascota?',
            'module' => 'pets',
        ])->assertStatus(503)
            ->assertJsonPath('message', 'Simón no está disponible en este momento. Intentá nuevamente más tarde.');
    }

    public function test_endpoint_requires_an_authenticated_supported_role(): void
    {
        $this->postJson(route('simon.messages.store'), ['question' => '¿Cómo registro una mascota?'])
            ->assertUnauthorized();

        $user = User::factory()->create();
        $user->syncRoles([]);

        $this->actingAs($user)->postJson(route('simon.messages.store'), ['question' => '¿Cómo registro una mascota?'])
            ->assertForbidden();
    }

    public function test_endpoint_validates_question_and_module(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => str_repeat('a', 1001),
            'module' => 'clinical-data',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['question', 'module']);
    }
}
