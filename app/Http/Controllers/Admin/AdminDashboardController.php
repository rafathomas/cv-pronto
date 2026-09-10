<?php

namespace App\Http\Controllers\Admin;

use App\Domain\AI\Models\AiUsage;
use App\Domain\Payment\Models\Payment;
use App\Domain\Resume\Models\Resume;
use App\Domain\ResumeAnalysis\Models\ResumeAnalysis;
use App\Domain\Subscription\Enums\SubscriptionStatus;
use App\Domain\Subscription\Models\Subscription;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'usersActiveLast30Days' => User::where('updated_at', '>=', now()->subDays(30))->count(),
            'resumesCount' => Resume::count(),
            'analysesCount' => ResumeAnalysis::count(),
            'activeSubscriptions' => Subscription::where('status', SubscriptionStatus::Active)->count(),
            'revenueCents' => Payment::where('status', 'approved')->sum('amount_cents'),
            'aiCallsCount' => AiUsage::count(),
            'aiCostUsd' => AiUsage::sum('estimated_cost'),
            'recentUsers' => User::latest()->limit(10)->get(),
            'recentPayments' => Payment::with('user')->latest()->limit(10)->get(),
        ]);
    }
}
