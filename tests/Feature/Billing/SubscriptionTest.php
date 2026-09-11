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

it('marca a assinatura como atrasada quando o pagamento falha', function () {
    $fake = new FakePaymentGateway;
    $this->app->instance(PaymentGatewayInterface::class, $fake);

    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();
    app(SubscriptionService::class)->startCheckout($user, $pro);

    $subscription = Subscription::where('user_id', $user->id)->firstOrFail();

    $fake->nextWebhookEvent = new WebhookEvent(
        type: 'payment.failed',
        gatewaySubscriptionId: $subscription->gateway_subscription_id,
        gatewayPaymentId: 'pay_456',
        amountCents: 2900,
    );

    app(SubscriptionService::class)->handleWebhook(request());

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::PastDue);
});

it('cancela a assinatura quando o webhook informa cancelamento', function () {
    $fake = new FakePaymentGateway;
    $this->app->instance(PaymentGatewayInterface::class, $fake);

    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();
    $subscription = Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $pro->id,
        'gateway' => 'mercadopago',
        'gateway_subscription_id' => 'sub_789',
        'status' => SubscriptionStatus::Active,
    ]);

    $fake->nextWebhookEvent = new WebhookEvent(
        type: 'subscription.cancelled',
        gatewaySubscriptionId: 'sub_789',
        gatewayPaymentId: null,
        amountCents: null,
    );

    app(SubscriptionService::class)->handleWebhook(request());

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Cancelled);
    expect($subscription->fresh()->cancelled_at)->not->toBeNull();
});

it('renova a assinatura e registra novo pagamento quando o webhook informa renovação', function () {
    $fake = new FakePaymentGateway;
    $this->app->instance(PaymentGatewayInterface::class, $fake);

    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();
    $subscription = Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $pro->id,
        'gateway' => 'mercadopago',
        'gateway_subscription_id' => 'sub_999',
        'status' => SubscriptionStatus::Active,
    ]);

    $fake->nextWebhookEvent = new WebhookEvent(
        type: 'subscription.renewed',
        gatewaySubscriptionId: 'sub_999',
        gatewayPaymentId: 'pay_renew_1',
        amountCents: 2900,
    );

    app(SubscriptionService::class)->handleWebhook(request());

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Active);
    expect(Payment::where('subscription_id', $subscription->id)->count())->toBe(1);
});

it('ignora eventos de webhook sem assinatura correspondente', function () {
    $fake = new FakePaymentGateway;
    $this->app->instance(PaymentGatewayInterface::class, $fake);

    $fake->nextWebhookEvent = new WebhookEvent(
        type: 'payment.approved',
        gatewaySubscriptionId: 'inexistente',
        gatewayPaymentId: 'pay_orphan',
        amountCents: 1000,
    );

    app(SubscriptionService::class)->handleWebhook(request());

    expect(Payment::where('gateway_payment_id', 'pay_orphan')->exists())->toBeFalse();
});

it('atribui um plano manualmente via admin e cancela a assinatura anterior', function () {
    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();
    $premium = Plan::where('key', 'premium')->firstOrFail();

    $old = Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $pro->id,
        'gateway' => 'manual',
        'status' => SubscriptionStatus::Active,
    ]);

    $new = app(SubscriptionService::class)->adminAssignPlan($user, $premium);

    expect($old->fresh()->status)->toBe(SubscriptionStatus::Cancelled);
    expect($new->status)->toBe(SubscriptionStatus::Active);
    expect(app(SubscriptionService::class)->currentPlan($user)->key)->toBe('premium');
});

it('atribuir o plano default via admin apenas cancela a assinatura ativa, sem criar uma nova', function () {
    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();
    $free = Plan::where('key', 'free')->firstOrFail();

    Subscription::create([
        'user_id' => $user->id,
        'plan_id' => $pro->id,
        'gateway' => 'manual',
        'status' => SubscriptionStatus::Active,
    ]);

    $result = app(SubscriptionService::class)->adminAssignPlan($user, $free);

    expect($result)->toBeNull();
    expect(app(SubscriptionService::class)->currentPlan($user)->key)->toBe('free');
});

it('admin consegue trocar e cancelar o plano de um usuário pelo painel', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $user = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();

    $this->actingAs($admin)
        ->patch(route('admin.subscriptions.update', $user), ['plan_id' => $pro->id])
        ->assertRedirect();

    expect(app(SubscriptionService::class)->currentPlan($user)->key)->toBe('pro');

    $this->actingAs($admin)
        ->delete(route('admin.subscriptions.destroy', $user))
        ->assertRedirect();

    expect(app(SubscriptionService::class)->currentPlan($user)->key)->toBe('free');
});

it('usuário não-admin não consegue alterar plano de outro usuário', function () {
    $user = User::factory()->create();
    $target = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();

    $this->actingAs($user)
        ->patch(route('admin.subscriptions.update', $target), ['plan_id' => $pro->id])
        ->assertForbidden();
});
