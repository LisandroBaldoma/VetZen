<?php

namespace App\Http\Controllers;

use App\Exceptions\AssistantProviderException;
use App\Http\Requests\Simon\StoreSimonMessageRequest;
use App\Services\SimonProductAssistant;
use Illuminate\Http\JsonResponse;

class SimonMessageController extends Controller
{
    public function store(StoreSimonMessageRequest $request, SimonProductAssistant $assistant): JsonResponse
    {
        try {
            $response = $assistant->answer(
                $request->user(),
                $request->validated('question'),
                $request->validated('module'),
            );
        } catch (AssistantProviderException) {
            return response()->json([
                'message' => 'Simón no está disponible en este momento. Intentá nuevamente más tarde.',
            ], 503);
        }

        return response()->json($response);
    }
}
