<?php

use App\Domain\Resume\Models\Resume;
use App\Livewire\Resume\Builder;
use App\Models\User;
use Livewire\Livewire;

it('cria um currículo automaticamente ao acessar o editor pela primeira vez', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('resume.builder'))->assertOk();

    expect(Resume::where('user_id', $user->id)->count())->toBe(1);
});

it('salva os dados pessoais do currículo', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('fullName', 'Rafael Souza')
        ->set('email', 'rafael@teste.com')
        ->set('city', 'São Paulo')
        ->set('state', 'SP')
        ->call('savePersonalData')
        ->assertHasNoErrors();

    expect($resume->fresh())
        ->full_name->toBe('Rafael Souza')
        ->email->toBe('rafael@teste.com')
        ->city->toBe('São Paulo');
});

it('exige nome completo para salvar os dados pessoais', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('fullName', '')
        ->call('savePersonalData')
        ->assertHasErrors(['fullName' => 'required']);
});

it('adiciona uma experiência profissional ao currículo', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('newCompany', 'Blue Service')
        ->set('newPosition', 'Analista de Dados Pleno')
        ->set('newStartDate', '2023-03-01')
        ->set('newIsCurrent', true)
        ->call('addExperience')
        ->assertHasNoErrors();

    expect($resume->experiences()->count())->toBe(1);
    expect($resume->experiences()->first())
        ->company->toBe('Blue Service')
        ->is_current->toBeTrue();
});

it('adiciona e remove habilidades do currículo', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    $component = Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('newSkillName', 'SQL')
        ->call('addSkill');

    expect($resume->skills()->count())->toBe(1);

    $skill = $resume->skills()->first();

    $component->call('removeSkill', $skill->id);

    expect($resume->skills()->count())->toBe(0);
});

it('impede um usuário de editar o currículo de outro usuário', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $resume = Resume::factory()->for($owner)->create();

    Livewire::actingAs($intruder)
        ->test(Builder::class, ['resume' => $resume])
        ->assertForbidden();
});

it('começa o assistente no primeiro passo (dados pessoais)', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->assertSet('activeTab', 'dados');
});

it('não avança do passo de dados pessoais quando o nome está vazio', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('fullName', '')
        ->call('continueFromDados')
        ->assertHasErrors(['fullName' => 'required'])
        ->assertSet('activeTab', 'dados');
});

it('salva os dados pessoais e avança para o passo de resumo ao continuar', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('fullName', 'Rafael Souza')
        ->call('continueFromDados')
        ->assertHasNoErrors()
        ->assertSet('activeTab', 'resumo');

    expect($resume->fresh()->full_name)->toBe('Rafael Souza');
});

it('permite navegar entre os passos com avançar e voltar', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->call('goToStep', 'habilidades')
        ->assertSet('activeTab', 'habilidades')
        ->call('previousStep')
        ->assertSet('activeTab', 'cursos')
        ->call('nextStep')
        ->assertSet('activeTab', 'habilidades');
});

it('redireciona para a escolha de template ao continuar a partir do último passo', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->call('goToStep', 'idiomas')
        ->call('nextStep')
        ->assertRedirect(route('resume.templates', $resume));
});
