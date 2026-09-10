<?php

namespace Tests\Support;

use App\Domain\Payment\Contracts\PaymentGatewayInterface;
use App\Domain\Payment\DTOs\CheckoutResult;
use App\Domain\Payment\DTOs\WebhookEvent;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class FakePaymentGateway implements PaymentGatewayInterface
{
    public ?WebhookEvent $nextWebhookEvent = null;

    public array $cancelledSubscriptions = [];

    public function createCheckout(User $user, Plan $plan): CheckoutResult
    {
        return new CheckoutResult(
            checkoutUrl: 'https://checkout.fake/preference/123',
            gatewaySubscriptionId: "user:{$user->id}:plan:{$plan->key}",
        );
    }

    public function handleWebhook(Request $request): WebhookEvent
    {
        return $this->nextWebhookEvent ?? new WebhookEvent(
            type: 'ignored',
            gatewaySubscriptionId: null,
            gatewayPaymentId: null,
            amountCents: null,
        );
    }

    public function cancelSubscription(Subscription $subscription): void
    {
        $this->cancelledSubscriptions[] = $subscription->id;
    }
}
