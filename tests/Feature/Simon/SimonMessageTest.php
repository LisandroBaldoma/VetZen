<?php

namespace Tests\Feature\Simon;

use App\Contracts\AssistantResponseGenerator;
use App\Models\Client;
use App\Models\User;
use App\Services\AssistantKnowledgeBaseIndexer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
            'simon.trace_logging' => false,
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

    public function test_admin_receives_grounded_guidance_for_managing_appointments(): void
    {
        $generator = $this->mock(AssistantResponseGenerator::class);
        $generator->shouldReceive('generate')
            ->once()
            ->withArgs(fn (string $instructions, string $prompt): bool => str_contains($instructions, 'Sos Simón')
                && str_contains($prompt, 'Estado: available')
                && str_contains($prompt, 'Gestionar turnos'))
            ->andReturn('Abrí Turnos, seleccioná Crear turno y elegí paciente, servicio y horario publicado.');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->postJson(route('simon.messages.store'), [
            'question' => '¿Cómo creo un turno para que un cliente venga mañana a las 10?',
            'module' => 'appointments',
        ])->assertOk()
            ->assertJsonPath('sources.0.id', 'admin.gestionar-turnos');
    }

    public function test_client_receives_appointment_guidance_without_admin_evidence_or_dynamic_data(): void
    {
        $client = Client::factory()->create();
        $generator = $this->mock(AssistantResponseGenerator::class);

        $generator->shouldReceive('generate')
            ->once()
            ->withArgs(fn (string $instructions, string $prompt): bool => str_contains($prompt, 'Gestionar mis turnos')
                && str_contains($prompt, 'Simón no puede consultar tus turnos reales')
                && ! str_contains($prompt, 'Gestionar turnos\n'))
            ->andReturn('Podés abrir Turnos, elegir Solicitar turno y seleccionar una mascota, servicio y horario.');

        $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => '¿Cómo solicito un turno?',
            'module' => 'appointments',
        ])->assertOk()
            ->assertJsonPath('sources.0.id', 'client.gestionar-turnos');
    }

    public function test_client_treatment_assignment_prompt_preserves_the_read_only_boundary(): void
    {
        $generator = $this->mock(AssistantResponseGenerator::class);
        $generator->shouldReceive('generate')
            ->once()
            ->withArgs(fn (string $instructions, string $prompt): bool => str_contains($instructions, 'NOT_ALLOWED_FOR_ROLE')
                && str_contains($prompt, 'Grounding: NOT_ALLOWED_FOR_ROLE')
                && str_contains($prompt, 'Los tratamientos los asigna la veterinaria')
                && ! str_contains($prompt, 'Asignar y gestionar tratamientos de un paciente'))
            ->andReturn('La veterinaria asigna los tratamientos. Como cliente podés consultarlos en modo lectura desde Mis mascotas.');
        $client = Client::factory()->create();

        $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => '¿Cómo asigno un tratamiento a mi mascota?',
        ])->assertOk()
            ->assertJsonPath('answer', 'La veterinaria asigna los tratamientos. Como cliente podés consultarlos en modo lectura desde Mis mascotas.')
            ->assertJsonPath('sources.0.id', 'client.consultar-tratamientos-y-sesiones');
    }

    public function test_trace_logging_records_metadata_without_the_question_or_answer(): void
    {
        config(['simon.trace_logging' => true]);
        Log::spy();
        $client = Client::factory()->create();
        $question = '¿Cómo registro una mascota?';
        $answer = 'Abrí Mis mascotas y elegí la opción para agregar una mascota.';
        $generator = $this->mock(AssistantResponseGenerator::class);
        $generator->shouldReceive('generate')->once()->andReturn($answer);

        $this->actingAs($client->user)->postJson(route('simon.messages.store'), [
            'question' => $question,
            'module' => 'pets',
        ])->assertOk();

        Log::shouldHaveReceived('info')
            ->withArgs(function (string $event, array $context) use ($question, $answer): bool {
                return $event === 'simon.message.received'
                    && $context['module'] === 'pets'
                    && $context['question_length'] === mb_strlen($question)
                    && ! array_key_exists('question', $context)
                    && ! in_array($question, $context, true)
                    && ! in_array($answer, $context, true);
            })
            ->once();

        Log::shouldHaveReceived('info')
            ->withArgs(function (string $event, array $context): bool {
                return $event === 'simon.retrieval.completed'
                    && $context['role'] === 'client'
                    && $context['chunks_count'] > 0
                    && array_key_exists('source_document_ids', $context);
            })
            ->once();
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

    public function test_dedicated_simon_page_is_not_available(): void
    {
        $client = Client::factory()->create();

        $this->actingAs($client->user)
            ->get('/simon')
            ->assertNotFound();
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
