<?php

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\Enums\AiOperation;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\AI\Models\AiUsage;
use App\Domain\AI\Services\AiCreditService;
use App\Domain\AI\Services\AiUsageService;
use App\Domain\Resume\Models\Resume;
use App\Models\User;
use Tests\Support\FakeAiProvider;

beforeEach(function () {
    $this->app->instance(AiProviderInterface::class, new FakeAiProvider);
});

it('concede créditos de IA automaticamente ao criar um usuário', function () {
    $user = User::factory()->create();

    expect(app(AiCreditService::class)->balance($user))->toBe(config('ai.signup_credits'));
});

it('executa uma operação de IA, registra o uso e desconta um crédito', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();
    $creditService = app(AiCreditService::class);
    $balanceBefore = $creditService->balance($user);

    $result = app(AiUsageService::class)->improveResume($user, ResumeContextData::fromModel($resume));

    expect($result->improvedSummary)->toBe('Resumo melhorado pela IA de teste.');
    expect($creditService->balance($user))->toBe($balanceBefore - AiOperation::ResumeImprovement->creditCost());

    $this->assertDatabaseHas('ai_usages', [
        'user_id' => $user->id,
        'operation' => AiOperation::ResumeImprovement->value,
        'provider' => 'fake',
        'model' => 'fake-model',
    ]);
});

it('impede a execução quando o usuário não tem créditos suficientes', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    // Zera o saldo consumindo os créditos concedidos no cadastro.
    $creditService = app(AiCreditService::class);
    while ($creditService->balance($user) > 0) {
        $creditService->consume($user, AiOperation::ResumeImprovement);
    }

    expect(fn () => app(AiUsageService::class)->improveResume($user, ResumeContextData::fromModel($resume)))
        ->toThrow(InsufficientCreditsException::class);

    expect(AiUsage::where('user_id', $user->id)->count())->toBe(0);
});
