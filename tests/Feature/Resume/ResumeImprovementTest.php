<?php

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\AI\DTOs\ResumeImprovementResult;
use App\Domain\AI\Enums\AiOperation;
use App\Domain\AI\Services\AiCreditService;
use App\Domain\Resume\Models\Resume;
use App\Livewire\Resume\Builder;
use App\Models\User;
use Livewire\Livewire;
use Tests\Support\FakeAiProvider;

it('melhora o resumo profissional com IA sem persistir automaticamente', function () {
    $this->app->instance(AiProviderInterface::class, new FakeAiProvider);

    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['professional_summary' => 'Resumo original.']);

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->call('improveSummary')
        ->assertSet('professionalSummary', 'Resumo melhorado pela IA de teste.');

    expect($resume->fresh()->professional_summary)->toBe('Resumo original.');
});

it('melhora a descrição de uma experiência e persiste a alteração', function () {
    $fake = new FakeAiProvider;
    $fake->improvementResult = new ResumeImprovementResult(
        improvedSummary: null,
        experienceImprovements: [],
        missingInfoSuggestions: ['Adicione resultados quantificados na experiência mais recente.'],
    );
    $this->app->instance(AiProviderInterface::class, $fake);

    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();
    $experience = $resume->experiences()->create([
        'company' => 'Blue Service',
        'position' => 'Analista de Dados',
        'start_date' => '2023-01-01',
        'is_current' => true,
        'description' => 'Descrição original.',
    ]);

    $fake->improvementResult = new ResumeImprovementResult(
        improvedSummary: null,
        experienceImprovements: [$experience->id => 'Descrição melhorada pela IA de teste.'],
        missingInfoSuggestions: [],
    );

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->call('improveExperience', $experience->id);

    expect($experience->fresh()->description)->toBe('Descrição melhorada pela IA de teste.');
});

it('mostra erro amigável quando o usuário não tem créditos de IA', function () {
    $this->app->instance(AiProviderInterface::class, new FakeAiProvider);

    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    $creditService = app(AiCreditService::class);
    while ($creditService->balance($user) > 0) {
        $creditService->consume($user, AiOperation::ResumeImprovement);
    }

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->call('improveSummary')
        ->assertHasErrors('ai');
});
