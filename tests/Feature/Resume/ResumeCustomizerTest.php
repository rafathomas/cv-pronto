<?php

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\Job\Models\JobDescription;
use App\Domain\Resume\Models\CustomizedResume;
use App\Domain\Resume\Models\Resume;
use App\Livewire\Resume\Customizer;
use App\Models\User;
use Livewire\Livewire;
use Tests\Support\FakeAiProvider;

beforeEach(function () {
    $this->app->instance(AiProviderInterface::class, new FakeAiProvider);
});

it('gera uma versão adaptada do currículo para a vaga', function () {
    $user = User::factory()->create();
    Resume::factory()->for($user)->create();
    $jobDescription = JobDescription::create(['user_id' => $user->id, 'raw_text' => 'Vaga de teste']);

    Livewire::actingAs($user)
        ->test(Customizer::class, ['jobDescription' => $jobDescription])
        ->call('generate')
        ->assertHasNoErrors();

    expect(CustomizedResume::where('job_description_id', $jobDescription->id)->count())->toBe(1);
});

it('impede um usuário de adaptar o currículo usando a vaga de outro usuário', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $jobDescription = JobDescription::create(['user_id' => $owner->id, 'raw_text' => 'Vaga de teste']);

    Livewire::actingAs($intruder)
        ->test(Customizer::class, ['jobDescription' => $jobDescription])
        ->assertForbidden();
});
