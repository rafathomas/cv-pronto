<?php

namespace App\Livewire\Resume;

use App\Domain\AI\DTOs\ResumeExtractionResult;
use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\Resume\Actions\CreateResumeFromExtractionAction;
use App\Domain\Resume\Actions\ImportResumeAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use RuntimeException;

class Importer extends Component
{
    use WithFileUploads;

    #[Validate('required|file|mimes:pdf,docx|max:5120')]
    public $file = null;

    public bool $isProcessing = false;

    /** @var array|null Snapshot serializável do App\Domain\AI\DTOs\ResumeExtractionResult */
    public ?array $extracted = null;

    public function upload(ImportResumeAction $action): void
    {
        $this->resetErrorBag();
        $this->validate();

        $this->isProcessing = true;

        try {
            $result = $action->handle(Auth::user(), $this->file);

            $this->extracted = [
                'full_name' => $result->fullName,
                'email' => $result->email,
                'phone' => $result->phone,
                'city' => $result->city,
                'state' => $result->state,
                'linkedin_url' => $result->linkedinUrl,
                'github_url' => $result->githubUrl,
                'portfolio_url' => $result->portfolioUrl,
                'professional_summary' => $result->professionalSummary,
                'experiences' => $result->experiences,
                'education' => $result->education,
                'courses' => $result->courses,
                'skills' => $result->skills,
                'languages' => $result->languages,
            ];
        } catch (InsufficientCreditsException $e) {
            $this->addError('ai', $e->getMessage());
        } catch (RuntimeException $e) {
            $this->addError('ai', $e->getMessage());
        } catch (AiProviderException) {
            $this->addError('ai', 'Não foi possível processar o arquivo agora. Tente novamente em instantes.');
        } finally {
            $this->isProcessing = false;
        }
    }

    public function confirm(CreateResumeFromExtractionAction $action): void
    {
        if (! $this->extracted) {
            return;
        }

        $resume = $action->handle(Auth::user(), ResumeExtractionResult::fromArray($this->extracted));

        $this->redirectRoute('resume.builder', ['resume' => $resume], navigate: true);
    }

    public function render()
    {
        return view('livewire.resume.importer')->layout('layouts.app');
    }
}
