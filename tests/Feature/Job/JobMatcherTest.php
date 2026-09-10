<?php

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\JobMatch\Models\JobMatch;
use App\Domain\Resume\Models\Resume;
use App\Livewire\Job\Matcher;
use App\Models\User;
use Livewire\Livewire;
use Tests\Support\FakeAiProvider;

beforeEach(function () {
    $this->app->instance(AiProviderInterface::class, new FakeAiProvider);
});

it('compara o currículo com a vaga e persiste o resultado', function () {
    $user = User::factory()->create();
    Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Matcher::class)
        ->set('jobDescription', str_repeat('Vaga de analista de dados com experiência em SQL e Python. ', 3))
        ->call('compare')
        ->assertHasNoErrors()
        ->assertSee('75%');

    expect(JobMatch::where('user_id', $user->id)->count())->toBe(1);
});

it('exige uma descrição de vaga com tamanho mínimo', function () {
    $user = User::factory()->create();
    Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Matcher::class)
        ->set('jobDescription', 'curta')
        ->call('compare')
        ->assertHasErrors('jobDescription');
});

it('avisa quando o usuário ainda não tem currículo', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Matcher::class)
        ->set('jobDescription', str_repeat('Vaga de analista de dados. ', 3))
        ->call('compare')
        ->assertHasErrors('ai');
});
