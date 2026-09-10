<?php

namespace App\Domain\AI\Observers;

use App\Domain\AI\Services\AiCreditService;
use App\Models\User;

class UserCreditObserver
{
    public function __construct(private readonly AiCreditService $credits) {}

    public function created(User $user): void
    {
        $amount = (int) config('ai.signup_credits', 0);

        if ($amount > 0) {
            $this->credits->grant($user, $amount);
        }
    }
}
