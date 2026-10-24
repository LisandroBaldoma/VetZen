<?php

namespace App\Http\Controllers;

use App\Exceptions\AssistantProviderException;
use App\Http\Requests\Simon\StoreSimonMessageRequest;
use App\Services\SimonProductAssistant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SimonMessageController extends Controller
{
    public function store(StoreSimonMessageRequest $request, SimonProductAssistant $assistant): JsonResponse
    {
        $startedAt = hrtime(true);
        $traceId = (string) Str::uuid();

        if (config('simon.trace_logging')) {
            Log::withContext([
                'simon_trace_id' => $traceId,
                'simon_user_id' => $request->user()->id,
            ]);
            Log::info('simon.message.received', [
                'module' => $request->validated('module'),
                'question_length' => Str::length($request->validated('question')),
            ]);
        }

        try {
            $response = $assistant->answer(
                $request->user(),
                $request->validated('question'),
                $request->validated('module'),
            );
        } catch (AssistantProviderException) {
            $this->trace('simon.message.failed', [
                'duration_ms' => $this->duration($startedAt),
                'error_type' => AssistantProviderException::class,
            ]);

            return response()->json([
                'message' => 'Simón no está disponible en este momento. Intentá nuevamente más tarde.',
            ], 503);
        }

        $this->trace('simon.message.completed', [
            'duration_ms' => $this->duration($startedAt),
            'sources_count' => count($response['sources']),
        ]);

        return response()->json($response);
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
}
