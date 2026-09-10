<?php

namespace App\Domain\Payment\Contracts;

use App\Domain\Payment\DTOs\CheckoutResult;
use App\Domain\Payment\DTOs\WebhookEvent;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Abstração para qualquer gateway de pagamento brasileiro (Mercado Pago,
 * Stripe, PagSeguro, ...). O domínio de Subscription/Payment nunca deve
 * depender de uma implementação concreta.
 */
interface PaymentGatewayInterface
{
    public function createCheckout(User $user, Plan $plan): CheckoutResult;

    public function handleWebhook(Request $request): WebhookEvent;

    public function cancelSubscription(Subscription $subscription): void;
}
