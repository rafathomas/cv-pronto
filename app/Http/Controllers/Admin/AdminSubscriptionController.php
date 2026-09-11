<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Subscription\Enums\SubscriptionStatus;
use App\Domain\Subscription\Models\Plan;
use App\Domain\Subscription\Services\SubscriptionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserPlanRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminSubscriptionController extends Controller
{
    public function index(SubscriptionService $service): View
    {
        $users = User::query()
            ->with(['subscriptions' => fn ($q) => $q->where('status', SubscriptionStatus::Active)->with('plan')->latest()])
            ->orderBy('name')
            ->paginate(20);

        $users->getCollection()->transform(function (User $user) use ($service) {
            $user->currentPlan = $service->currentPlan($user);
            $user->activeSubscription = $user->subscriptions->first();

            return $user;
        });

        return view('admin.subscriptions', [
            'users' => $users,
            'plans' => Plan::where('is_active', true)->orderBy('price_cents')->get(),
        ]);
    }

    public function update(UpdateUserPlanRequest $request, User $user, SubscriptionService $service): RedirectResponse
    {
        $plan = Plan::findOrFail($request->validated('plan_id'));

        $service->adminAssignPlan($user, $plan);

        return back()->with('status', "Plano de {$user->name} atualizado para {$plan->name}.");
    }

    public function destroy(User $user, SubscriptionService $service): RedirectResponse
    {
        $service->cancel($user);

        return back()->with('status', "Assinatura de {$user->name} cancelada.");
    }
}
