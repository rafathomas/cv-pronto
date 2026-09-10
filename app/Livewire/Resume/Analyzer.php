<?php

namespace App\Livewire\Resume;

use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\Resume\Models\Resume;
use App\Domain\ResumeAnalysis\Actions\AnalyzeResumeAction;
use App\Domain\ResumeAnalysis\Models\ResumeAnalysis;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Analyzer extends Component
{
    public Resume $resume;

    public ?ResumeAnalysis $analysis = null;

    public bool $isAnalyzing = false;

    public function mount(?Resume $resume = null): void
    {
        if ($resume && $resume->exists) {
            $this->authorize('view', $resume);
            $this->resume = $resume;
        } else {
            $existing = Auth::user()->resumes()->latest()->first();

            if (! $existing) {
                $this->resume = new Resume;
                $this->redirectRoute('resume.builder', navigate: true);

                return;
            }

            $this->resume = $existing;
        }

        $this->analysis = $this->resume->latestAnalysis;
    }

    #[Computed]
    public function hasContent(): bool
    {
        return filled($this->resume->professional_summary)
            || $this->resume->experiences()->exists()
            || $this->resume->skills()->exists();
    }

    public function analyze(AnalyzeResumeAction $action): void
    {
        $this->authorize('view', $this->resume);
        $this->resetErrorBag('ai');

        if (! $this->hasContent) {
            $this->addError('ai', 'Preencha ao menos o resumo profissional, uma experiência ou uma habilidade antes de analisar.');

            return;
        }

        $this->isAnalyzing = true;

        try {
            $this->analysis = $action->handle(Auth::user(), $this->resume);
        } catch (InsufficientCreditsException $e) {
            $this->addError('ai', $e->getMessage());
        } catch (AiProviderException) {
            $this->addError('ai', 'Não foi possível analisar o currículo agora. Tente novamente em instantes.');
        } finally {
            $this->isAnalyzing = false;
        }
    }

    public function render()
    {
        return view('livewire.resume.analyzer')->layout('layouts.app');
    }
}
