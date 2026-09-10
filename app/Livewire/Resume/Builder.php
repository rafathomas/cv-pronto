<?php

namespace App\Livewire\Resume;

use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\DTOs\ResumeImprovementResult;
use App\Domain\AI\Exceptions\AiProviderException;
use App\Domain\AI\Exceptions\InsufficientCreditsException;
use App\Domain\AI\Services\AiUsageService;
use App\Domain\Resume\DTOs\EducationData;
use App\Domain\Resume\DTOs\ExperienceData;
use App\Domain\Resume\DTOs\ResumePersonalData;
use App\Domain\Resume\Enums\LanguageLevel;
use App\Domain\Resume\Models\Resume;
use App\Domain\Resume\Models\ResumeCourse;
use App\Domain\Resume\Models\ResumeEducation;
use App\Domain\Resume\Models\ResumeExperience;
use App\Domain\Resume\Models\ResumeLanguage;
use App\Domain\Resume\Models\ResumeSkill;
use App\Domain\Resume\Services\ResumeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Builder extends Component
{
    public Resume $resume;

    public string $activeTab = 'dados';

    // Dados pessoais
    #[Validate('required|string|max:150')]
    public string $fullName = '';

    #[Validate('nullable|email|max:150')]
    public ?string $email = null;

    #[Validate('nullable|string|max:30')]
    public ?string $phone = null;

    #[Validate('nullable|string|max:100')]
    public ?string $city = null;

    #[Validate('nullable|string|max:2')]
    public ?string $state = null;

    #[Validate('nullable|url|max:255')]
    public ?string $linkedinUrl = null;

    #[Validate('nullable|url|max:255')]
    public ?string $githubUrl = null;

    #[Validate('nullable|url|max:255')]
    public ?string $portfolioUrl = null;

    // Resumo
    #[Validate('nullable|string|max:1200')]
    public ?string $professionalSummary = null;

    // Nova experiência
    public string $newCompany = '';

    public string $newPosition = '';

    public string $newStartDate = '';

    public ?string $newEndDate = null;

    public bool $newIsCurrent = false;

    public ?string $newDescription = null;

    // Nova formação
    public string $newInstitution = '';

    public string $newCourse = '';

    public ?string $newDegree = null;

    public ?string $newEduStartDate = null;

    public ?string $newEduEndDate = null;

    // Novo curso
    public string $newCourseName = '';

    public ?string $newCourseInstitution = null;

    // Nova habilidade
    public string $newSkillName = '';

    // Novo idioma
    public string $newLanguageName = '';

    public string $newLanguageLevel = 'intermediario';

    /** @var string[] */
    public array $missingInfoSuggestions = [];

    public function mount(?Resume $resume = null): void
    {
        if ($resume && $resume->exists) {
            $this->authorize('update', $resume);
            $this->resume = $resume;
        } else {
            $this->resume = Auth::user()->resumes()->firstOrCreate([], [
                'full_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]);
        }

        $this->fullName = $this->resume->full_name;
        $this->email = $this->resume->email;
        $this->phone = $this->resume->phone;
        $this->city = $this->resume->city;
        $this->state = $this->resume->state;
        $this->linkedinUrl = $this->resume->linkedin_url;
        $this->githubUrl = $this->resume->github_url;
        $this->portfolioUrl = $this->resume->portfolio_url;
        $this->professionalSummary = $this->resume->professional_summary;
    }

    /**
     * Passos do assistente, na ordem em que são exibidos.
     *
     * @return array<string, string>
     */
    public static function steps(): array
    {
        return [
            'dados' => 'Dados pessoais',
            'resumo' => 'Resumo',
            'experiencia' => 'Experiência',
            'formacao' => 'Formação',
            'cursos' => 'Cursos',
            'habilidades' => 'Habilidades',
            'idiomas' => 'Idiomas',
        ];
    }

    public function goToStep(string $step): void
    {
        if (array_key_exists($step, self::steps())) {
            $this->activeTab = $step;
        }
    }

    public function previousStep(): void
    {
        $keys = array_keys(self::steps());
        $index = array_search($this->activeTab, $keys, true);

        if ($index !== false && $index > 0) {
            $this->activeTab = $keys[$index - 1];
        }
    }

    public function nextStep(): void
    {
        $keys = array_keys(self::steps());
        $index = array_search($this->activeTab, $keys, true);

        if ($index !== false && $index < count($keys) - 1) {
            $this->activeTab = $keys[$index + 1];

            return;
        }

        $this->redirectRoute('resume.templates', ['resume' => $this->resume], navigate: true);
    }

    public function continueFromDados(ResumeService $service): void
    {
        $this->savePersonalData($service);
        $this->nextStep();
    }

    public function continueFromResumo(ResumeService $service): void
    {
        $this->saveSummary($service);
        $this->nextStep();
    }

    public function savePersonalData(ResumeService $service): void
    {
        $this->validate([
            'fullName' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:30',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'linkedinUrl' => 'nullable|url|max:255',
            'githubUrl' => 'nullable|url|max:255',
            'portfolioUrl' => 'nullable|url|max:255',
        ]);

        $service->updatePersonalData($this->resume, new ResumePersonalData(
            fullName: $this->fullName,
            email: $this->email,
            phone: $this->phone,
            city: $this->city,
            state: $this->state,
            linkedinUrl: $this->linkedinUrl,
            githubUrl: $this->githubUrl,
            portfolioUrl: $this->portfolioUrl,
        ));

        $this->dispatch('resume-saved');
    }

    public function saveSummary(ResumeService $service): void
    {
        $this->validate(['professionalSummary' => 'nullable|string|max:1200']);

        $service->updateProfessionalSummary($this->resume, $this->professionalSummary ?? '');

        $this->dispatch('resume-saved');
    }

    public function addExperience(ResumeService $service): void
    {
        $this->validate([
            'newCompany' => 'required|string|max:150',
            'newPosition' => 'required|string|max:150',
            'newStartDate' => 'required|date',
            'newEndDate' => 'nullable|date|after_or_equal:newStartDate',
        ]);

        $service->addExperience($this->resume, ExperienceData::fromArray([
            'company' => $this->newCompany,
            'position' => $this->newPosition,
            'start_date' => $this->newStartDate,
            'end_date' => $this->newEndDate,
            'is_current' => $this->newIsCurrent,
            'description' => $this->newDescription,
        ]));

        $this->reset(['newCompany', 'newPosition', 'newStartDate', 'newEndDate', 'newIsCurrent', 'newDescription']);
        $this->resume->refresh();
    }

    public function deleteExperience(ResumeExperience $experience, ResumeService $service): void
    {
        $this->authorize('update', $this->resume);
        abort_unless($experience->resume_id === $this->resume->id, 403);

        $service->deleteExperience($experience);
        $this->resume->refresh();
    }

    public function addEducation(ResumeService $service): void
    {
        $this->validate([
            'newInstitution' => 'required|string|max:150',
            'newCourse' => 'required|string|max:150',
            'newDegree' => 'nullable|string|max:100',
            'newEduStartDate' => 'nullable|date',
            'newEduEndDate' => 'nullable|date',
        ]);

        $service->addEducation($this->resume, EducationData::fromArray([
            'institution' => $this->newInstitution,
            'course' => $this->newCourse,
            'degree' => $this->newDegree,
            'start_date' => $this->newEduStartDate,
            'end_date' => $this->newEduEndDate,
        ]));

        $this->reset(['newInstitution', 'newCourse', 'newDegree', 'newEduStartDate', 'newEduEndDate']);
        $this->resume->refresh();
    }

    public function deleteEducation(ResumeEducation $education, ResumeService $service): void
    {
        $this->authorize('update', $this->resume);
        abort_unless($education->resume_id === $this->resume->id, 403);

        $service->deleteEducation($education);
        $this->resume->refresh();
    }

    public function addCourse(ResumeService $service): void
    {
        $this->validate([
            'newCourseName' => 'required|string|max:150',
            'newCourseInstitution' => 'nullable|string|max:150',
        ]);

        $service->addCourse($this->resume, $this->newCourseName, $this->newCourseInstitution);

        $this->reset(['newCourseName', 'newCourseInstitution']);
        $this->resume->refresh();
    }

    public function deleteCourse(ResumeCourse $course, ResumeService $service): void
    {
        $this->authorize('update', $this->resume);
        abort_unless($course->resume_id === $this->resume->id, 403);

        $service->deleteCourse($course);
        $this->resume->refresh();
    }

    public function addSkill(ResumeService $service): void
    {
        $this->validate(['newSkillName' => 'required|string|max:60']);

        $service->addSkill($this->resume, $this->newSkillName);

        $this->reset(['newSkillName']);
        $this->resume->refresh();
    }

    public function removeSkill(ResumeSkill $skill, ResumeService $service): void
    {
        $this->authorize('update', $this->resume);
        abort_unless($skill->resume_id === $this->resume->id, 403);

        $service->removeSkill($skill);
        $this->resume->refresh();
    }

    public function addLanguage(ResumeService $service): void
    {
        $this->validate([
            'newLanguageName' => 'required|string|max:60',
            'newLanguageLevel' => 'required|in:'.implode(',', array_column(LanguageLevel::cases(), 'value')),
        ]);

        $service->addLanguage($this->resume, $this->newLanguageName, $this->newLanguageLevel);

        $this->reset(['newLanguageName']);
        $this->newLanguageLevel = 'intermediario';
        $this->resume->refresh();
    }

    public function removeLanguage(ResumeLanguage $language, ResumeService $service): void
    {
        $this->authorize('update', $this->resume);
        abort_unless($language->resume_id === $this->resume->id, 403);

        $service->removeLanguage($language);
        $this->resume->refresh();
    }

    public function improveSummary(AiUsageService $usageService): void
    {
        $result = $this->runImprovement($usageService);

        if (! $result) {
            return;
        }

        if ($result->improvedSummary) {
            $this->professionalSummary = $result->improvedSummary;
        }
    }

    public function improveExperience(int $experienceId, AiUsageService $usageService): void
    {
        $result = $this->runImprovement($usageService);

        if (! $result) {
            return;
        }

        if (isset($result->experienceImprovements[$experienceId])) {
            $experience = $this->resume->experiences->firstWhere('id', $experienceId);

            if ($experience) {
                $experience->update(['description' => $result->experienceImprovements[$experienceId]]);
                $this->resume->refresh();
            }
        }
    }

    private function runImprovement(AiUsageService $usageService): ?ResumeImprovementResult
    {
        $this->authorize('update', $this->resume);
        $this->resetErrorBag('ai');
        $this->missingInfoSuggestions = [];

        try {
            $result = $usageService->improveResume(Auth::user(), ResumeContextData::fromModel($this->resume));
        } catch (InsufficientCreditsException $e) {
            $this->addError('ai', $e->getMessage());

            return null;
        } catch (AiProviderException) {
            $this->addError('ai', 'Não foi possível melhorar o texto agora. Tente novamente em instantes.');

            return null;
        }

        $this->missingInfoSuggestions = $result->missingInfoSuggestions;

        return $result;
    }

    public function render()
    {
        $keys = array_keys(self::steps());

        return view('livewire.resume.builder', [
            'languageLevels' => LanguageLevel::cases(),
            'steps' => self::steps(),
            'currentStepIndex' => array_search($this->activeTab, $keys, true),
            'isLastStep' => $this->activeTab === end($keys),
        ])->layout('layouts.app');
    }
}
