<?php

namespace App\Domain\Job\Actions;

use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\Services\AiUsageService;
use App\Domain\Job\Models\JobDescription;
use App\Domain\JobMatch\Models\JobMatch;
use App\Domain\Resume\Models\Resume;
use App\Models\User;

class MatchResumeWithJobAction
{
    public function __construct(private readonly AiUsageService $usageService) {}

    public function handle(User $user, Resume $resume, string $jobDescriptionText): JobMatch
    {
        $result = $this->usageService->matchResumeWithJob($user, ResumeContextData::fromModel($resume), $jobDescriptionText);

        $jobDescription = JobDescription::create([
            'user_id' => $user->id,
            'raw_text' => $jobDescriptionText,
        ]);

        return JobMatch::create([
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'job_description_id' => $jobDescription->id,
            'match_score' => $result->matchScore,
            'matched_skills' => $result->matchedSkills,
            'partial_skills' => $result->partialSkills,
            'missing_skills' => $result->missingSkills,
            'keywords' => $result->keywords,
            'recommendations' => $result->recommendations,
        ]);
    }
}
