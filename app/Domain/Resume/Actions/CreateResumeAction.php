<?php

namespace App\Domain\Resume\Actions;

use App\Domain\Resume\DTOs\ResumePersonalData;
use App\Domain\Resume\Enums\ResumeSource;
use App\Domain\Resume\Models\Resume;
use App\Models\User;

class CreateResumeAction
{
    public function handle(User $user, ResumePersonalData $data, ResumeSource $source = ResumeSource::Manual): Resume
    {
        return $user->resumes()->create([
            ...$data->toArray(),
            'source' => $source,
        ]);
    }
}
