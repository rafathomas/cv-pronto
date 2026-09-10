<?php

namespace App\Domain\Payment\Gateways;

use App\Domain\Payment\Contracts\PaymentGatewayInterface;
use App\Domain\Payment\DTOs\CheckoutResult;
use App\Domain\Payment\DTOs\WebhookEvent;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MercadoPagoGateway implements PaymentGatewayInterface
{
    private const BASE_URL = 'https://api.mercadopago.com';

    public function __construct(private readonly array $config) {}

    public function createCheckout(User $user, Plan $plan): CheckoutResult
    {
        $this->ensureConfigured();

        $response = Http::withToken($this->config['access_token'])
            ->post(self::BASE_URL.'/checkout/preferences', [
                'items' => [[
                    'title' => "CVPronto - Plano {$plan->name}",
                    'quantity' => 1,
                    'currency_id' => 'BRL',
                    'unit_price' => $plan->price_cents / 100,
                ]],
                'payer' => ['email' => $user->email],
                'external_reference' => "user:{$user->id}:plan:{$plan->key}",
                'back_urls' => [
                    'success' => route('billing.success'),
                    'failure' => route('billing.plans'),
                    'pending' => route('billing.plans'),
                ],
                'auto_return' => 'approved',
                'notification_url' => route('billing.webhook', 'mercadopago'),
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Não foi possível iniciar o checkout no Mercado Pago: '.$response->body());
        }

        return new CheckoutResult(
            checkoutUrl: $response->json('init_point'),
            gatewaySubscriptionId: $response->json('id'),
        );
    }

    public function handleWebhook(Request $request): WebhookEvent
    {
        $this->ensureConfigured();

        $type = $request->input('type', $request->input('topic'));
        $paymentId = $request->input('data.id', $request->input('id'));

        if ($type !== 'payment' || ! $paymentId) {
            return new WebhookEvent(type: 'ignored', gatewaySubscriptionId: null, gatewayPaymentId: null, amountCents: null);
        }

        $response = Http::withToken($this->config['access_token'])
            ->get(self::BASE_URL."/v1/payments/{$paymentId}");

        if ($response->failed()) {
            throw new RuntimeException('Não foi possível consultar o pagamento no Mercado Pago.');
        }

        $status = $response->json('status');
        $externalReference = $response->json('external_reference');

        return new WebhookEvent(
            type: $status === 'approved' ? 'payment.approved' : 'payment.failed',
            gatewaySubscriptionId: $externalReference,
            gatewayPaymentId: (string) $paymentId,
            amountCents: (int) round(($response->json('transaction_amount') ?? 0) * 100),
        );
    }

    public function cancelSubscription(Subscription $subscription): void
    {
        $this->ensureConfigured();

        if (! $subscription->gateway_subscription_id) {
            return;
        }

        Http::withToken($this->config['access_token'])
            ->put(self::BASE_URL."/preapproval/{$subscription->gateway_subscription_id}", [
                'status' => 'cancelled',
            ]);
    }

    private function ensureConfigured(): void
    {
        if (empty($this->config['access_token'])) {
            throw new RuntimeException('Gateway de pagamento Mercado Pago não está configurado (MERCADOPAGO_ACCESS_TOKEN ausente).');
        }
    }
}
