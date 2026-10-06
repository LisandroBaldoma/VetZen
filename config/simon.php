<?php

return [
    'rate_limit' => (int) env('SIMON_RATE_LIMIT', 10),
    'retrieval_limit' => (int) env('SIMON_RETRIEVAL_LIMIT', 5),
    'trace_logging' => env('SIMON_TRACE_LOGGING', env('APP_DEBUG', false)),
    'llm' => [
        'provider' => env('SIMON_LLM_PROVIDER', 'groq'),
        'model' => env('SIMON_LLM_MODEL'),
        'connect_timeout' => (int) env('SIMON_LLM_CONNECT_TIMEOUT', 3),
        'timeout' => (int) env('SIMON_LLM_TIMEOUT', 15),
    ],
];
