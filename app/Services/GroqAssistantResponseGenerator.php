<?php

namespace App\Services;

use App\Contracts\AssistantResponseGenerator;
use App\Exceptions\AssistantProviderException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class GroqAssistantResponseGenerator implements AssistantResponseGenerator
{
    public function generate(string $instructions, string $prompt): string
    {
        $provider = config('simon.llm.provider');
        $key = config('services.groq.key');
        $model = config('simon.llm.model');

        if ($provider !== 'groq' || ! is_string($key) || $key === '' || ! is_string($model) || $model === '') {
            throw new AssistantProviderException('Groq is not configured.');
        }

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
            throw new AssistantProviderException('Groq request failed.', previous: $exception);
        }

        $answer = data_get($response->json(), 'choices.0.message.content');

        if (! is_string($answer) || trim($answer) === '') {
            throw new AssistantProviderException('Groq returned no assistant content.');
        }

        return trim($answer);
    }
}
