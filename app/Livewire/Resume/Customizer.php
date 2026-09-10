<?php

namespace App\Livewire\Resume;

use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\Job\Models\JobDescription;
use App\Domain\Resume\Actions\CustomizeResumeAction;
use App\Domain\Resume\Models\CustomizedResume;
use App\Domain\Resume\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Customizer extends Component
{
    public JobDescription $jobDescription;

    public ?Resume $resume = null;

    public ?CustomizedResume $customized = null;

    public function mount(JobDescription $jobDescription): void
    {
        $this->authorize('view', $jobDescription);
        $this->jobDescription = $jobDescription;
        $this->resume = Auth::user()->resumes()->latest()->first();

        if ($this->resume) {
            $this->customized = CustomizedResume::where('resume_id', $this->resume->id)
                ->where('job_description_id', $jobDescription->id)
                ->latest()
                ->first();
        }
    }

    public function generate(CustomizeResumeAction $action): void
    {
        $this->resetErrorBag('ai');

        if (! $this->resume) {
            $this->addError('ai', 'Crie um currículo antes de gerar uma versão adaptada.');

            return;
        }

        try {
            $this->customized = $action->handle(Auth::user(), $this->resume, $this->jobDescription);
        } catch (InsufficientCreditsException $e) {
            $this->addError('ai', $e->getMessage());
        } catch (AiProviderException) {
            $this->addError('ai', 'Não foi possível gerar a adaptação agora. Tente novamente em instantes.');
        }
    }

    public function render()
    {
        return view('livewire.resume.customizer')->layout('layouts.app');
    }
}
