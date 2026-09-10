<?php

namespace App\Livewire\Resume;

use App\Domain\Resume\Enums\ResumeTemplate;
use App\Domain\Resume\Models\Resume;
use Livewire\Component;

class TemplateSelector extends Component
{
    public Resume $resume;

    public string $selectedTemplate;

    public function mount(Resume $resume): void
    {
        $this->authorize('view', $resume);
        $this->resume = $resume;
        $this->selectedTemplate = $resume->template;
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

    public function render()
    {
        return view('livewire.resume.template-selector', [
            'templates' => ResumeTemplate::cases(),
        ])->layout('layouts.app');
    }
}
