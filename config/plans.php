<?php

/*
|--------------------------------------------------------------------------
| Planos e limites
|--------------------------------------------------------------------------
|
| Estrutura de fallback usada para popular a tabela `plans` via seeder e
| como referência de limites quando a feature flag `plans.use_database`
| estiver desativada. Alterar preços/limites aqui NÃO exige alteração de
| código do domínio de Subscription.
|
*/

return [

    'default' => 'free',

    'feature_labels' => [
        'pdf_basic' => 'Exportação em PDF',
        'pdf_premium' => 'Templates premium em PDF',
        'job_customization' => 'Currículo adaptado para cada vaga',
        'premium_templates' => 'Acesso a todos os templates',
        'cover_letters' => 'Carta de apresentação com IA',
        'advanced_analysis' => 'Análise avançada de currículo',
        'online_resume' => 'Currículo online compartilhável',
        'application_tracker' => 'Acompanhamento de candidaturas',
    ],

    'plans' => [
        'free' => [
            'name' => 'Free',
            'price' => 0,
            'interval' => 'month',
            'limits' => [
                'resumes' => 1,
                'analyses_per_month' => 1,
                'templates' => 1,
                'job_matches_per_month' => 0,
                'cover_letters_per_month' => 0,
            ],
            'features' => [
                'pdf_basic',
            ],
        ],

        'pro' => [
            'name' => 'Pro',
            'price' => 2900,
            'interval' => 'month',
            'limits' => [
                'resumes' => null,
                'analyses_per_month' => 20,
                'templates' => null,
                'job_matches_per_month' => 20,
                'cover_letters_per_month' => 10,
            ],
            'features' => [
                'pdf_basic',
                'pdf_premium',
                'job_customization',
                'premium_templates',
                'cover_letters',
            ],
        ],

        'premium' => [
            'name' => 'Premium',
            'price' => 4900,
            'interval' => 'month',
            'limits' => [
                'resumes' => null,
                'analyses_per_month' => null,
                'templates' => null,
                'job_matches_per_month' => null,
                'cover_letters_per_month' => null,
            ],
            'features' => [
                'pdf_basic',
                'pdf_premium',
                'job_customization',
                'premium_templates',
                'cover_letters',
                'advanced_analysis',
                'online_resume',
                'application_tracker',
            ],
        ],
    ],
];
