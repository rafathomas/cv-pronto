<?php

namespace App\Livewire\Billing;

use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Services\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use RuntimeException;

class Plans extends Component
{
    public function subscribe(int $planId, SubscriptionService $service): mixed
    {
        $this->resetErrorBag();

        $plan = Plan::findOrFail($planId);

        try {
            $result = $service->startCheckout(Auth::user(), $plan);
        } catch (RuntimeException $e) {
            $this->addError('billing', $e->getMessage());

            return null;
        }

        return redirect()->away($result->checkoutUrl);
    }

    public function render()
    {
        return view('livewire.billing.plans', [
            'plans' => Plan::where('is_active', true)->orderBy('price_cents')->get(),
            'currentPlan' => app(SubscriptionService::class)->currentPlan(Auth::user()),
        ])->layout('layouts.app');
    }
}
