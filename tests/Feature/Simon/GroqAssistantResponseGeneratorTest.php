<?php

namespace Tests\Feature\Simon;

use App\Exceptions\AssistantProviderException;
use App\Services\GroqAssistantResponseGenerator;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
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
}
