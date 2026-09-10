<?php

use App\Domain\Payment\Contracts\PaymentGatewayInterface;
use App\Domain\Payment\DTOs\WebhookEvent;
use App\Domain\Payment\Models\Payment;
use App\Domain\Subscription\Enums\SubscriptionStatus;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Models\Subscription;
use App\Domain\Subscription\Services\SubscriptionService;
use App\Livewire\Billing\Plans;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Livewire\Livewire;
use Tests\Support\FakePaymentGateway;

beforeEach(function () {
    $this->seed(PlanSeeder::class);
});

it('usuário novo começa no plano free', function () {
    $user = User::factory()->create();

    expect(app(SubscriptionService::class)->currentPlan($user)->key)->toBe('free');
});

it('inicia um checkout e cria uma assinatura pendente', function () {
    $fake = new FakePaymentGateway;
    $this->app->instance(PaymentGatewayInterface::class, $fake);

    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();

    $result = app(SubscriptionService::class)->startCheckout($user, $pro);

    expect($result->checkoutUrl)->toBe('https://checkout.fake/preference/123');
    expect(Subscription::where('user_id', $user->id)->where('status', SubscriptionStatus::Pending)->count())->toBe(1);
});

it('ativa a assinatura e registra o pagamento quando o webhook confirma o pagamento', function () {
    $fake = new FakePaymentGateway;
    $this->app->instance(PaymentGatewayInterface::class, $fake);

    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();
    app(SubscriptionService::class)->startCheckout($user, $pro);

    $subscription = Subscription::where('user_id', $user->id)->firstOrFail();

    $fake->nextWebhookEvent = new WebhookEvent(
        type: 'payment.approved',
        gatewaySubscriptionId: $subscription->gateway_subscription_id,
        gatewayPaymentId: 'pay_123',
        amountCents: 2900,
    );

    app(SubscriptionService::class)->handleWebhook(request());

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Active);
    expect(Payment::where('subscription_id', $subscription->id)->count())->toBe(1);
    expect(app(SubscriptionService::class)->currentPlan($user)->key)->toBe('pro');
});

it('cancela a assinatura ativa do usuário', function () {
    $fake = new FakePaymentGateway;
    $this->app->instance(PaymentGatewayInterface::class, $fake);

    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();
    $subscription = Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $pro->id,
        'gateway' => 'mercadopago',
        'status' => SubscriptionStatus::Active,
    ]);

    app(SubscriptionService::class)->cancel($user);

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Cancelled);
    expect($fake->cancelledSubscriptions)->toContain($subscription->id);
});

it('mostra os planos disponíveis na página de billing', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Plans::class)
        ->assertSee('Pro')
        ->assertSee('Premium');
});
