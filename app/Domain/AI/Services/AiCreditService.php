<?php

namespace App\Domain\AI\Services;

use App\Domain\AI\Enums\AiOperation;
use App\Domain\AI\Models\AiCredit;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Ledger de créditos de IA. O saldo é a soma das concessões ("grant") não
 * expiradas com todos os consumos ("consumption", valores negativos).
 */
class AiCreditService
{
    public function balance(User $user): int
    {
        return (int) AiCredit::query()
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('type', 'consumption')
                    ->orWhere(function ($query) {
                        $query->where('type', 'grant')
                            ->where(function ($query) {
                                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                            });
                    });
            })
            ->sum('amount');
    }

    public function hasEnoughCredits(User $user, AiOperation $operation): bool
    {
        return $this->balance($user) >= $operation->creditCost();
    }

    public function grant(User $user, int $amount, ?Carbon $expiresAt = null): AiCredit
    {
        return AiCredit::create([
            'user_id' => $user->id,
            'type' => 'grant',
            'amount' => $amount,
            'expires_at' => $expiresAt,
        ]);
    }

    public function consume(User $user, AiOperation $operation): AiCredit
    {
        return AiCredit::create([
            'user_id' => $user->id,
            'type' => 'consumption',
            'amount' => -$operation->creditCost(),
            'expires_at' => null,
        ]);
    }
}
