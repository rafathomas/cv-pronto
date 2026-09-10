<?php

namespace App\Livewire\Job;

use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\Job\Actions\MatchResumeWithJobAction;
use App\Domain\JobMatch\Models\JobMatch;
use App\Domain\Resume\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Matcher extends Component
{
    public ?Resume $resume = null;

    #[Validate('required|string|min:30|max:8000')]
    public string $jobDescription = '';

    public ?JobMatch $result = null;

    public function mount(): void
    {
        $this->resume = Auth::user()->resumes()->latest()->first();
    }

    public function compare(MatchResumeWithJobAction $action): void
    {
        $this->resetErrorBag();

        if (! $this->resume) {
            $this->addError('ai', 'Crie um currículo antes de comparar com uma vaga.');

            return;
        }

        $this->validate();

        try {
            $this->result = $action->handle(Auth::user(), $this->resume, $this->jobDescription);
        } catch (InsufficientCreditsException $e) {
            $this->addError('ai', $e->getMessage());
        } catch (AiProviderException) {
            $this->addError('ai', 'Não foi possível comparar com a vaga agora. Tente novamente em instantes.');
        }
    }

    public function render()
    {
        return view('livewire.job.matcher')->layout('layouts.app');
    }
}
