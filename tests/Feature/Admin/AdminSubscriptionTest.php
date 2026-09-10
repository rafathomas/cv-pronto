<?php

use App\Domain\Subscription\Enums\SubscriptionStatus;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Models\Subscription;
use App\Models\User;
use Database\Seeders\PlanSeeder;

beforeEach(function () {
    $this->seed(PlanSeeder::class);
});

it('impede um usuário comum de acessar a gestão de assinaturas', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)->get(route('admin.subscriptions.index'))->assertForbidden();
});

it('lista os usuários com o plano atual de cada um', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $other = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.subscriptions.index'))
        ->assertOk()
        ->assertSee($other->email)
        ->assertSee('Free');
});

it('permite que um admin conceda um plano manualmente a um usuário', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $target = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();

    $this->actingAs($admin)
        ->patch(route('admin.subscriptions.update', $target), ['plan_id' => $pro->id])
        ->assertRedirect();

    $subscription = Subscription::where('user_id', $target->id)->latest()->firstOrFail();

    expect($subscription->plan_id)->toBe($pro->id);
    expect($subscription->status)->toBe(SubscriptionStatus::Active);
    expect($subscription->gateway)->toBe('manual');
});

it('permite que um admin cancele a assinatura de um usuário', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $target = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();

    $subscription = Subscription::create([
        'user_id' => $target->id,
        'plan_id' => $pro->id,
        'gateway' => 'manual',
        'status' => SubscriptionStatus::Active,
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.subscriptions.destroy', $target))
        ->assertRedirect();

    expect($subscription->fresh()->status)->toBe(SubscriptionStatus::Cancelled);
});

it('impede um usuário comum de alterar o plano de outro usuário', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $target = User::factory()->create();
    $pro = Plan::where('key', 'pro')->firstOrFail();

    $this->actingAs($user)
        ->patch(route('admin.subscriptions.update', $target), ['plan_id' => $pro->id])
        ->assertForbidden();
});
