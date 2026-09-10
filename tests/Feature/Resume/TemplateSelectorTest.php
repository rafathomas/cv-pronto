<?php

use App\Domain\Resume\Models\Resume;
use App\Livewire\Resume\TemplateSelector;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;

it('permite trocar o template do currículo', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['template' => 'classico']);

    Livewire::actingAs($user)
        ->test(TemplateSelector::class, ['resume' => $resume])
        ->call('selectTemplate', 'moderno')
        ->assertSet('selectedTemplate', 'moderno');

    expect($resume->fresh()->template)->toBe('moderno');
});

it('permite trocar a cor de destaque do currículo', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['accent_color' => '#16A34A']);

    Livewire::actingAs($user)
        ->test(TemplateSelector::class, ['resume' => $resume])
        ->call('selectColor', '#2563EB')
        ->assertSet('selectedColor', '#2563EB');

    expect($resume->fresh()->accent_color)->toBe('#2563EB');
});

it('ignora uma cor fora da paleta permitida', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['accent_color' => '#16A34A']);

    Livewire::actingAs($user)
        ->test(TemplateSelector::class, ['resume' => $resume])
        ->call('selectColor', '#000000')
        ->assertSet('selectedColor', '#16A34A');

    expect($resume->fresh()->accent_color)->toBe('#16A34A');
});

it('aplica a cor de destaque escolhida no HTML gerado do template', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->for($user)->create(['accent_color' => '#7C3AED']);

    $html = View::make('pdf.resume.classico', [
        'resume' => $resume->load(['experiences', 'education', 'courses', 'skills', 'languages']),
        'accentColor' => $resume->accent_color,
    ])->render();

    expect($html)->toContain('#7C3AED');
});
