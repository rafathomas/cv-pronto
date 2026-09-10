<?php

namespace App\Policies;

use App\Domain\Job\Models\JobDescription;
use App\Models\User;

class JobDescriptionPolicy
{
    public function view(User $user, JobDescription $jobDescription): bool
    {
        return $user->id === $jobDescription->user_id;
    }
}
