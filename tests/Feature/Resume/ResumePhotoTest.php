<?php

use App\Domain\Resume\Models\Resume;
use App\Livewire\Resume\Builder;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('resumes');
});

it('faz upload da foto do currículo e mostra no assistente', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('photo', UploadedFile::fake()->image('foto.jpg', 400, 400))
        ->assertHasNoErrors();

    $resume->refresh();

    expect($resume->photo_path)->not->toBeNull();
    Storage::disk('resumes')->assertExists($resume->photo_path);
});

it('rejeita um arquivo que não é imagem como foto', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('photo', UploadedFile::fake()->create('curriculo.pdf', 100))
        ->assertHasErrors(['photo' => 'image']);

    expect($resume->fresh()->photo_path)->toBeNull();
});

it('remove a foto do currículo e apaga o arquivo do disco', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    $component = Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('photo', UploadedFile::fake()->image('foto.jpg'));

    $path = $resume->fresh()->photo_path;
    Storage::disk('resumes')->assertExists($path);

    $component->call('removePhoto');

    expect($resume->fresh()->photo_path)->toBeNull();
    Storage::disk('resumes')->assertMissing($path);
});

it('substitui a foto anterior ao enviar uma nova', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('photo', UploadedFile::fake()->image('primeira.jpg'));

    $firstPath = $resume->fresh()->photo_path;

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('photo', UploadedFile::fake()->image('segunda.jpg'));

    $secondPath = $resume->fresh()->photo_path;

    expect($secondPath)->not->toBe($firstPath);
    Storage::disk('resumes')->assertMissing($firstPath);
    Storage::disk('resumes')->assertExists($secondPath);
});

it('embute a foto como data URI no PDF gerado', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create();

    Livewire::actingAs($user)
        ->test(Builder::class, ['resume' => $resume])
        ->set('photo', UploadedFile::fake()->image('foto.jpg'));

    $resume->refresh();

    $html = View::make('pdf.resume.classico', [
        'resume' => $resume->load(['experiences', 'education', 'courses', 'skills', 'languages']),
        'accentColor' => $resume->accent_color,
    ])->render();

    expect($html)->toContain('data:image');
});
