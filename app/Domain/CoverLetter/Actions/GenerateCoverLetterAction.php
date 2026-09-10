<?php

namespace App\Domain\CoverLetter\Actions;

use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\Services\AiUsageService;
use App\Domain\CoverLetter\Models\CoverLetter;
use App\Domain\Job\Models\JobDescription;
use App\Domain\Resume\Models\Resume;
use App\Models\User;

class GenerateCoverLetterAction
{
    public function __construct(private readonly AiUsageService $usageService) {}

    public function handle(User $user, Resume $resume, JobDescription $jobDescription, string $company, ?string $tone = null): CoverLetter
    {
        $result = $this->usageService->generateCoverLetter(
            $user,
            ResumeContextData::fromModel($resume),
            $jobDescription->raw_text,
            $company,
            $tone,
        );

        return CoverLetter::create([
            'user_id' => $user->id,
            'resume_id' => $resume->id,
            'job_description_id' => $jobDescription->id,
            'company' => $company,
            'tone' => $tone,
            'content' => $result->content,
        ]);
    }
}
