<?php

namespace App\Livewire\Resume;

use App\Domain\Resume\Enums\ResumeTemplate;
use App\Domain\Resume\Models\Resume;
use Livewire\Component;

class TemplateSelector extends Component
{
    /**
     * Paleta de cores de destaque disponíveis para qualquer template.
     *
     * @var array<string, string>
     */
    public const ACCENT_COLORS = [
        'Cinza' => '#64748B',
        'Vermelho' => '#DC2626',
        'Laranja' => '#EA580C',
        'Amarelo' => '#CA8A04',
        'Verde' => '#16A34A',
        'Turquesa' => '#0D9488',
        'Azul' => '#2563EB',
        'Roxo' => '#7C3AED',
    ];

    public Resume $resume;

    public string $selectedTemplate;

    public string $selectedColor;

    public function mount(Resume $resume): void
    {
        $this->authorize('view', $resume);
        $this->resume = $resume;
        $this->selectedTemplate = $resume->template;
        $this->selectedColor = $resume->accent_color ?: '#16A34A';
    }

    public function selectTemplate(string $template): void
    {
        $this->authorize('update', $this->resume);

        if (! ResumeTemplate::tryFrom($template)) {
            return;
        }

        $this->selectedTemplate = $template;
        $this->resume->update(['template' => $template]);
    }

    public function selectColor(string $color): void
    {
        $this->authorize('update', $this->resume);

        if (! in_array($color, self::ACCENT_COLORS, true)) {
            return;
        }

        $this->selectedColor = $color;
        $this->resume->update(['accent_color' => $color]);
    }

    public function render()
    {
        return view('livewire.resume.template-selector', [
            'templates' => ResumeTemplate::cases(),
            'accentColors' => self::ACCENT_COLORS,
        ])->layout('layouts.app');
    }
}
