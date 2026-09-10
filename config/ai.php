<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Provedor de IA padrão
    |--------------------------------------------------------------------------
    |
    | Define qual implementação de AiProviderInterface será resolvida pelo
    | container. Permite trocar de provedor (OpenAI, Gemini, Groq, ...) sem
    | alterar o restante da aplicação.
    |
    */

    'provider' => env('AI_PROVIDER', 'openai'),

    'model' => env('AI_MODEL', 'gpt-4o-mini'),

    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'organization' => env('OPENAI_ORGANIZATION'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'model' => env('AI_MODEL', 'gpt-4o-mini'),
            'timeout' => (int) env('AI_TIMEOUT', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Controle de custo / retry
    |--------------------------------------------------------------------------
    */

    'max_retries' => (int) env('AI_MAX_RETRIES', 2),

    'retry_backoff_ms' => (int) env('AI_RETRY_BACKOFF_MS', 500),

    /*
    |--------------------------------------------------------------------------
    | Custo estimado por 1.000 tokens (USD)
    |--------------------------------------------------------------------------
    |
    | Usado pelo AiUsageService para estimar o custo de cada chamada e ser
    | exibido no painel administrativo.
    |
    */

    'pricing' => [
        'gpt-4o-mini' => [
            'input' => 0.00015,
            'output' => 0.00060,
        ],
        'gpt-4o' => [
            'input' => 0.0025,
            'output' => 0.010,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custo em créditos por operação
    |--------------------------------------------------------------------------
    */

    'credit_cost' => [
        'resume_analysis' => 1,
        'resume_improvement' => 1,
        'resume_extraction' => 1,
        'job_analysis' => 1,
        'resume_matching' => 1,
        'resume_customization' => 2,
        'cover_letter' => 1,
    ],
];
