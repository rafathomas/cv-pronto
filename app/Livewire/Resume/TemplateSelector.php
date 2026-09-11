<?php

namespace App\Livewire\Resume;

use App\Domain\Resume\Enums\AccentColor;
use App\Domain\Resume\Enums\ResumeTemplate;
use App\Domain\Resume\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class TemplateSelector extends Component
{
    public Resume $resume;

    public string $selectedTemplate;

    public string $selectedColor;

    public function mount(Resume $resume): void
    {
        $this->authorize('view', $resume);
        $this->resume = $resume;
        $this->selectedTemplate = $resume->template;
        $this->selectedColor = $resume->accent_color ?: AccentColor::DEFAULT;
    }

    public function selectTemplate(string $template): void
    {
        $this->authorize('update', $this->resume);

        $resumeTemplate = ResumeTemplate::tryFrom($template);

        if (! $resumeTemplate) {
            return;
        }

        if (Gate::denies('use-premium-template', $resumeTemplate)) {
            $this->addError('template', 'Este template é exclusivo dos planos Pro e Premium. Faça upgrade para usá-lo.');

            return;
        }

        $this->selectedTemplate = $template;
        $this->resume->update(['template' => $template]);
    }

    public function hasPremiumTemplates(): bool
    {
        return Gate::forUser(Auth::user())->allows('access-premium-templates');
    }

    public function selectColor(string $color): void
    {
        $this->authorize('update', $this->resume);

        if (! in_array($color, AccentColor::PALETTE, true)) {
            return;
        }

        $this->selectedColor = $color;
        $this->resume->update(['accent_color' => $color]);
    }

    public function render()
    {
        return view('livewire.resume.template-selector', [
            'templates' => ResumeTemplate::cases(),
            'accentColors' => AccentColor::PALETTE,
            'hasPremiumTemplates' => $this->hasPremiumTemplates(),
        ])->layout('layouts.app');
    }
}
