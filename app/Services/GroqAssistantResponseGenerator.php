<?php

namespace App\Services;

use App\Contracts\AssistantResponseGenerator;
use App\Exceptions\AssistantProviderException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAssistantResponseGenerator implements AssistantResponseGenerator
{
    public function generate(string $instructions, string $prompt): string
    {
        $startedAt = hrtime(true);
        $provider = config('simon.llm.provider');
        $key = config('services.groq.key');
        $model = config('simon.llm.model');

        if ($provider !== 'groq' || ! is_string($key) || $key === '' || ! is_string($model) || $model === '') {
            $this->trace('simon.provider.not_configured', ['provider' => $provider]);

            throw new AssistantProviderException('Groq is not configured.');
        }

        $this->trace('simon.provider.request_started', ['model' => $model]);

        try {
            $response = Http::baseUrl(config('services.groq.base_url'))
                ->withToken($key)
                ->acceptJson()
                ->connectTimeout(config('simon.llm.connect_timeout'))
                ->timeout(config('simon.llm.timeout'))
                ->post('chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'developer', 'content' => $instructions],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ])
                ->throw();
        } catch (ConnectionException|RequestException $exception) {
            $this->trace('simon.provider.failed', [
                'duration_ms' => $this->duration($startedAt),
                'error_type' => $exception::class,
            ]);

            throw new AssistantProviderException('Groq request failed.', previous: $exception);
        }

        $answer = data_get($response->json(), 'choices.0.message.content');

        if (! is_string($answer) || trim($answer) === '') {
            $this->trace('simon.provider.failed', [
                'duration_ms' => $this->duration($startedAt),
                'error_type' => 'empty_content',
            ]);

            throw new AssistantProviderException('Groq returned no assistant content.');
        }

        $this->trace('simon.provider.completed', [
            'model' => $model,
            'duration_ms' => $this->duration($startedAt),
            'prompt_tokens' => $this->tokenCount(data_get($response->json(), 'usage.prompt_tokens')),
            'completion_tokens' => $this->tokenCount(data_get($response->json(), 'usage.completion_tokens')),
            'total_tokens' => $this->tokenCount(data_get($response->json(), 'usage.total_tokens')),
        ]);

        return trim($answer);
    }

    /** @param array<string, mixed> $context */
    private function trace(string $event, array $context): void
    {
        if (config('simon.trace_logging')) {
            Log::info($event, $context);
        }
    }

    private function duration(int $startedAt): int
    {
        return (int) round((hrtime(true) - $startedAt) / 1_000_000);
    }

    private function tokenCount(mixed $value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
