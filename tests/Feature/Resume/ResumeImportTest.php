<?php

use App\Domain\AI\DTOs\ResumeExtractionResult;
use App\Domain\Resume\Actions\CreateResumeFromExtractionAction;
use App\Domain\Resume\Enums\ResumeSource;
use App\Livewire\Resume\Importer;
use App\Models\User;
use Livewire\Livewire;

it('cria um currículo a partir dos dados extraídos, ignorando entradas incompletas', function () {
    $user = User::factory()->create();

    $extraction = new ResumeExtractionResult(
        fullName: 'Rafael Souza',
        email: 'rafael@teste.com',
        phone: null,
        city: 'São Paulo',
        state: 'SP',
        linkedinUrl: null,
        githubUrl: null,
        portfolioUrl: null,
        professionalSummary: 'Analista de dados com experiência em BI.',
        experiences: [
            ['company' => 'Blue Service', 'position' => 'Analista de Dados', 'start_date' => '2023-01-01', 'end_date' => null, 'is_current' => true, 'description' => null],
            ['company' => '', 'position' => 'Incompleto', 'start_date' => '', 'end_date' => null, 'is_current' => false, 'description' => null],
        ],
        education: [
            ['institution' => 'USP', 'course' => 'Sistemas de Informação', 'degree' => null, 'start_date' => null, 'end_date' => null],
        ],
        courses: [],
        skills: ['SQL', 'Python'],
        languages: [
            ['name' => 'Inglês', 'level' => 'avancado'],
        ],
    );

    $resume = app(CreateResumeFromExtractionAction::class)->handle($user, $extraction);

    expect($resume->full_name)->toBe('Rafael Souza');
    expect($resume->source)->toBe(ResumeSource::Import);
    expect($resume->imported_at)->not->toBeNull();
    expect($resume->experiences)->toHaveCount(1); // a entrada incompleta foi ignorada
    expect($resume->education)->toHaveCount(1);
    expect($resume->skills)->toHaveCount(2);
    expect($resume->languages->first()->name)->toBe('Inglês');
});

it('confirma a importação pelo componente Livewire e redireciona para o editor', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Importer::class)
        ->set('extracted', [
            'full_name' => 'Rafael Souza',
            'email' => null,
            'phone' => null,
            'city' => null,
            'state' => null,
            'linkedin_url' => null,
            'github_url' => null,
            'portfolio_url' => null,
            'professional_summary' => null,
            'experiences' => [],
            'education' => [],
            'courses' => [],
            'skills' => [],
            'languages' => [],
        ])
        ->call('confirm')
        ->assertRedirect();

    expect($user->resumes()->count())->toBe(1);
});
