<?php

namespace Tests\Feature\Simon;

use App\Exceptions\AssistantProviderException;
use App\Services\GroqAssistantResponseGenerator;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class GroqAssistantResponseGeneratorTest extends TestCase
{
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
    }

    public function test_it_sends_a_chat_completion_request_to_groq(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [['message' => ['content' => 'Respuesta de prueba']]],
            ]),
        ]);

        $answer = app(GroqAssistantResponseGenerator::class)->generate('Instrucciones', 'Pregunta');

        $this->assertSame('Respuesta de prueba', $answer);

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://api.groq.com/openai/v1/chat/completions'
                && $request->data()['model'] === 'test-model'
                && $request->data()['messages'] === [
                    ['role' => 'developer', 'content' => 'Instrucciones'],
                    ['role' => 'user', 'content' => 'Pregunta'],
                ];
        });
    }

    public function test_it_requires_the_groq_configuration(): void
    {
        config(['services.groq.key' => null]);

        $this->expectException(AssistantProviderException::class);

        app(GroqAssistantResponseGenerator::class)->generate('Instrucciones', 'Pregunta');
    }

    public function test_it_logs_provider_usage_without_prompt_or_answer_content(): void
    {
        config(['simon.trace_logging' => true]);
        Log::spy();
        Http::preventStrayRequests();
        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [['message' => ['content' => 'Respuesta de prueba']]],
                'usage' => [
                    'prompt_tokens' => 120,
                    'completion_tokens' => 24,
                    'total_tokens' => 144,
                ],
            ]),
        ]);

        app(GroqAssistantResponseGenerator::class)->generate('Instrucciones privadas', 'Pregunta privada');

        Log::shouldHaveReceived('info')
            ->withArgs(function (string $event, array $context): bool {
                return $event === 'simon.provider.completed'
                    && $context['model'] === 'test-model'
                    && $context['prompt_tokens'] === 120
                    && $context['completion_tokens'] === 24
                    && $context['total_tokens'] === 144
                    && $context['duration_ms'] >= 0
                    && ! in_array('Instrucciones privadas', $context, true)
                    && ! in_array('Pregunta privada', $context, true)
                    && ! in_array('Respuesta de prueba', $context, true);
            })
            ->once();
    }
}
