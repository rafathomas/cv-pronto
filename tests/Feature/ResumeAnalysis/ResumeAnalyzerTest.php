<?php

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\AI\Enums\AiOperation;
use App\Domain\AI\Services\AiCreditService;
use App\Domain\Resume\Models\Resume;
use App\Domain\ResumeAnalysis\Models\ResumeAnalysis;
use App\Livewire\Resume\Analyzer;
use App\Models\User;
use Livewire\Livewire;
use Tests\Support\FakeAiProvider;

beforeEach(function () {
    $this->app->instance(AiProviderInterface::class, new FakeAiProvider);
});

it('mostra estado vazio quando o currículo ainda não foi analisado', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Analyzer::class, ['resume' => $resume])
        ->assertSee('Você ainda não analisou este currículo.');
});

it('impede a análise de um currículo sem conteúdo preenchido', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['professional_summary' => null]);

    Livewire::actingAs($user)
        ->test(Analyzer::class, ['resume' => $resume])
        ->call('analyze')
        ->assertHasErrors('ai');

    expect(ResumeAnalysis::count())->toBe(0);
});

it('analisa o currículo, persiste a análise e atualiza a nota do currículo', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['professional_summary' => 'Analista de dados com experiência em BI.']);

    Livewire::actingAs($user)
        ->test(Analyzer::class, ['resume' => $resume])
        ->call('analyze')
        ->assertHasNoErrors()
        ->assertSee('80');

    expect(ResumeAnalysis::where('resume_id', $resume->id)->count())->toBe(1);
    expect($resume->fresh()->latest_score)->toBe(80);
});

it('impede um usuário de ver a análise do currículo de outro usuário', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $resume = Resume::factory()->for($owner)->create();

    Livewire::actingAs($intruder)
        ->test(Analyzer::class, ['resume' => $resume])
        ->assertForbidden();
});

it('não persiste análise quando o usuário não tem créditos suficientes', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['professional_summary' => 'Analista de dados com experiência em BI.']);

    $creditService = app(AiCreditService::class);
    while ($creditService->balance($user) > 0) {
        $creditService->consume($user, AiOperation::ResumeAnalysis);
    }

    Livewire::actingAs($user)
        ->test(Analyzer::class, ['resume' => $resume])
        ->call('analyze')
        ->assertHasErrors('ai');

    expect(ResumeAnalysis::count())->toBe(0);
    expect($resume->fresh()->latest_score)->toBeNull();
});
