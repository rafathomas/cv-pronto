<?php

namespace App\Domain\Resume\Actions;

use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\Services\AiUsageService;
use App\Domain\Job\Models\JobDescription;
use App\Domain\Resume\Models\CustomizedResume;
use App\Domain\Resume\Models\Resume;
use App\Models\User;

class CustomizeResumeAction
{
    public function __construct(private readonly AiUsageService $usageService) {}

    public function handle(User $user, Resume $resume, JobDescription $jobDescription): CustomizedResume
    {
        $result = $this->usageService->customizeResume($user, ResumeContextData::fromModel($resume), $jobDescription->raw_text);

        return CustomizedResume::create([
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'job_description_id' => $jobDescription->id,
            'summary' => $result->summary,
            'experience_descriptions' => $result->experienceDescriptions,
            'highlighted_skills' => $result->highlightedSkills,
            'notes' => $result->notes,
        ]);
    }
}
