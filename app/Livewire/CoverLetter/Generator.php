<?php

namespace App\Livewire\CoverLetter;

use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\CoverLetter\Actions\GenerateCoverLetterAction;
use App\Domain\CoverLetter\Models\CoverLetter;
use App\Domain\Job\Models\JobDescription;
use App\Domain\Resume\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Generator extends Component
{
    public JobDescription $jobDescription;

    public ?Resume $resume = null;

    public ?CoverLetter $letter = null;

    #[Validate('required|string|max:150')]
    public string $company = '';

    #[Validate('required|string|max:60')]
    public string $tone = 'Profissional e direto';

    public function mount(JobDescription $jobDescription): void
    {
        $this->authorize('view', $jobDescription);
        $this->jobDescription = $jobDescription;
        $this->resume = Auth::user()->resumes()->latest()->first();

        $this->letter = CoverLetter::where('job_description_id', $jobDescription->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if ($this->letter) {
            $this->company = $this->letter->company ?? '';
            $this->tone = $this->letter->tone ?? $this->tone;
        }
    }

    public function generate(GenerateCoverLetterAction $action): void
    {
        $this->resetErrorBag('ai');

        if (! $this->resume) {
            $this->addError('ai', 'Crie um currículo antes de gerar uma carta de apresentação.');

            return;
        }

        $this->validate();

        try {
            $this->letter = $action->handle(Auth::user(), $this->resume, $this->jobDescription, $this->company, $this->tone);
        } catch (InsufficientCreditsException $e) {
            $this->addError('ai', $e->getMessage());
        } catch (AiProviderException) {
            $this->addError('ai', 'Não foi possível gerar a carta agora. Tente novamente em instantes.');
        }
    }

    public function updateContent(string $content): void
    {
        if (! $this->letter) {
            return;
        }

        $this->authorize('view', $this->jobDescription);
        $this->letter->update(['content' => $content]);
    }

    public function render()
    {
        return view('livewire.cover-letter.generator')->layout('layouts.app');
    }
}
