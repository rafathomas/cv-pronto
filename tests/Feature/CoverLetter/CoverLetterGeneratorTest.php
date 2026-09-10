<?php

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\CoverLetter\Models\CoverLetter;
use App\Domain\Job\Models\JobDescription;
use App\Domain\Resume\Models\Resume;
use App\Livewire\CoverLetter\Generator;
use App\Models\User;
use Livewire\Livewire;
use Tests\Support\FakeAiProvider;

beforeEach(function () {
    $this->app->instance(AiProviderInterface::class, new FakeAiProvider);
});

it('gera uma carta de apresentação para a vaga', function () {
    $user = User::factory()->create();
    Resume::factory()->for($user)->create();
    $jobDescription = JobDescription::create(['user_id' => $user->id, 'raw_text' => 'Vaga de teste']);

    Livewire::actingAs($user)
        ->test(Generator::class, ['jobDescription' => $jobDescription])
        ->set('company', 'Empresa Teste')
        ->call('generate')
        ->assertHasNoErrors()
        ->assertSet('letter.content', 'Carta de teste.');

    expect(CoverLetter::where('job_description_id', $jobDescription->id)->count())->toBe(1);
});

it('exige o nome da empresa para gerar a carta', function () {
    $user = User::factory()->create();
    Resume::factory()->for($user)->create();
    $jobDescription = JobDescription::create(['user_id' => $user->id, 'raw_text' => 'Vaga de teste']);

    Livewire::actingAs($user)
        ->test(Generator::class, ['jobDescription' => $jobDescription])
        ->set('company', '')
        ->call('generate')
        ->assertHasErrors('company');
});
