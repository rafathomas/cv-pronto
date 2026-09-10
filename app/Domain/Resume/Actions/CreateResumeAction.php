<?php

namespace App\Domain\Resume\Actions;

use App\Domain\Analytics\Services\AnalyticsService;
use App\Domain\Resume\DTOs\ResumePersonalData;
use App\Domain\Resume\Enums\ResumeSource;
use App\Domain\Resume\Models\Resume;
use App\Models\User;

class CreateResumeAction
{
    public function __construct(private readonly AnalyticsService $analytics) {}

    public function handle(User $user, ResumePersonalData $data, ResumeSource $source = ResumeSource::Manual): Resume
    {
        $resume = $user->resumes()->create([
            ...$data->toArray(),
            'source' => $source,
        ]);

        $this->analytics->track('resume_created', $user, ['source' => $source->value]);

        return $resume;
    }
}
