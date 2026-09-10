<?php

namespace App\Domain\Resume\Actions;

use App\Domain\AI\DTOs\ResumeExtractionResult;
use App\Domain\Resume\DTOs\EducationData;
use App\Domain\Resume\DTOs\ExperienceData;
use App\Domain\Resume\DTOs\ResumePersonalData;
use App\Domain\Resume\Enums\LanguageLevel;
use App\Domain\Resume\Enums\ResumeSource;
use App\Domain\Resume\Models\Resume;
use App\Domain\Resume\Services\ResumeService;
use App\Models\User;

class CreateResumeFromExtractionAction
{
    public function __construct(
        private readonly CreateResumeAction $createResume,
        private readonly ResumeService $resumeService,
    ) {}

    public function handle(User $user, ResumeExtractionResult $data): Resume
    {
        $resume = $this->createResume->handle(
            $user,
            new ResumePersonalData(
                fullName: $data->fullName !== '' ? $data->fullName : $user->name,
                email: $data->email,
                phone: $data->phone,
                city: $data->city,
                state: $data->state,
                linkedinUrl: $data->linkedinUrl,
                githubUrl: $data->githubUrl,
                portfolioUrl: $data->portfolioUrl,
            ),
            ResumeSource::Import,
        );

        $resume->update([
            'professional_summary' => $data->professionalSummary,
            'imported_at' => now(),
        ]);

        foreach ($data->experiences as $experience) {
            if (empty($experience['company']) || empty($experience['position']) || empty($experience['start_date'])) {
                continue;
            }

            $this->resumeService->addExperience($resume, ExperienceData::fromArray($experience));
        }

        foreach ($data->education as $education) {
            if (empty($education['institution']) || empty($education['course'])) {
                continue;
            }

            $this->resumeService->addEducation($resume, EducationData::fromArray($education));
        }

        foreach ($data->courses as $course) {
            if (empty($course['name'])) {
                continue;
            }

            $this->resumeService->addCourse($resume, $course['name'], $course['institution'] ?? null);
        }

        foreach ($data->skills as $skill) {
            $this->resumeService->addSkill($resume, $skill);
        }

        foreach ($data->languages as $language) {
            if (empty($language['name'])) {
                continue;
            }

            $level = LanguageLevel::tryFrom($language['level'] ?? '')?->value ?? LanguageLevel::Intermediario->value;
            $this->resumeService->addLanguage($resume, $language['name'], $level);
        }

        return $resume->fresh(['experiences', 'education', 'courses', 'skills', 'languages']);
    }
}
