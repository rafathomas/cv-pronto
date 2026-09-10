<?php

namespace App\Domain\Subscription\Services;

use App\Domain\Analytics\Services\AnalyticsService;
use App\Domain\Payment\Contracts\PaymentGatewayInterface;
use App\Domain\Payment\DTOs\CheckoutResult;
use App\Domain\Payment\DTOs\WebhookEvent;
use App\Domain\Payment\Models\Payment;
use App\Domain\Subscription\Enums\SubscriptionStatus;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionService
{
    public function __construct(
        private readonly PaymentGatewayInterface $gateway,
        private readonly AnalyticsService $analytics,
    ) {}

    public function currentPlan(User $user): Plan
    {
        $subscription = $user->subscriptions()
            ->where('status', SubscriptionStatus::Active)
            ->latest()
            ->first();

        return $subscription?->plan ?? Plan::where('key', config('plans.default'))->firstOrFail();
    }

    public function startCheckout(User $user, Plan $plan): CheckoutResult
    {
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'gateway' => config('payment.gateway'),
            'status' => SubscriptionStatus::Pending,
        ]);

        $result = $this->gateway->createCheckout($user, $plan);

        $subscription->update(['gateway_subscription_id' => $result->gatewaySubscriptionId]);

        $this->analytics->track('checkout_started', $user, ['plan' => $plan->key]);

        return $result;
    }

    public function handleWebhook(Request $request): void
    {
        $event = $this->gateway->handleWebhook($request);

        if ($event->type === 'ignored') {
            return;
        }

        $subscription = $this->resolveSubscription($event);

        if (! $subscription) {
            return;
        }

        match ($event->type) {
            'payment.approved' => $this->activate($subscription, $event),
            'payment.failed' => $subscription->update(['status' => SubscriptionStatus::PastDue]),
            'subscription.cancelled' => $subscription->update(['status' => SubscriptionStatus::Cancelled, 'cancelled_at' => now()]),
            'subscription.renewed' => $this->activate($subscription, $event),
            default => null,
        };
    }

    public function cancel(User $user): void
    {
        $subscription = $user->subscriptions()->where('status', SubscriptionStatus::Active)->latest()->first();

        if (! $subscription) {
            return;
        }

        $this->gateway->cancelSubscription($subscription);

        $subscription->update(['status' => SubscriptionStatus::Cancelled, 'cancelled_at' => now()]);
    }

    /**
     * Concede um plano manualmente (uso administrativo: cortesia, correção de
     * pagamento, downgrade forçado). Nunca passa pelo gateway de pagamento.
     */
    public function adminAssignPlan(User $user, Plan $plan): ?Subscription
    {
        $user->subscriptions()
            ->where('status', SubscriptionStatus::Active)
            ->update(['status' => SubscriptionStatus::Cancelled, 'cancelled_at' => now()]);

        if ($plan->key === config('plans.default')) {
            return null;
        }

        return $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'gateway' => 'manual',
            'status' => SubscriptionStatus::Active,
            'current_period_end' => null,
        ]);
    }

    private function activate(Subscription $subscription, WebhookEvent $event): void
    {
        $subscription->update([
            'status' => SubscriptionStatus::Active,
            'current_period_end' => now()->addMonth(),
        ]);

        Payment::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'gateway' => $subscription->gateway,
            'gateway_payment_id' => $event->gatewayPaymentId,
            'amount_cents' => $event->amountCents ?? $subscription->plan->price_cents,
            'status' => 'approved',
            'paid_at' => now(),
        ]);

        $this->analytics->track('payment_completed', $subscription->user, ['plan' => $subscription->plan->key]);
    }

    private function resolveSubscription(WebhookEvent $event): ?Subscription
    {
        return Subscription::where('gateway_subscription_id', $event->gatewaySubscriptionId)->latest()->first();
    }
}
