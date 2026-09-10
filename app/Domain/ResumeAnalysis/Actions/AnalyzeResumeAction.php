<?php

namespace App\Domain\ResumeAnalysis\Actions;

use App\Domain\AI\DTOs\ResumeContextData;
use App\Domain\AI\Services\AiUsageService;
use App\Domain\Analytics\Services\AnalyticsService;
use App\Domain\Resume\Models\Resume;
use App\Domain\ResumeAnalysis\Models\ResumeAnalysis;
use App\Models\User;

class AnalyzeResumeAction
{
    public function __construct(
        private readonly AiUsageService $usageService,
        private readonly AnalyticsService $analytics,
    ) {}

    public function handle(User $user, Resume $resume): ResumeAnalysis
    {
        $result = $this->usageService->analyzeResume($user, ResumeContextData::fromModel($resume));

        $analysis = $resume->analyses()->create([
            'user_id' => $user->id,
            'score' => $result->score,
            'summary' => $result->summary,
            'strengths' => $result->strengths,
            'weaknesses' => $result->weaknesses,
            'recommendations' => $result->recommendations,
            'categories' => $result->categories,
        ]);

        $resume->update(['latest_score' => $result->score]);

        $this->analytics->track('resume_analysis', $user, ['score' => $result->score]);

        return $analysis;
    }
}
