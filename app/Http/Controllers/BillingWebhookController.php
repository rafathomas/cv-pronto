<?php

namespace App\Http\Controllers;

use App\Domain\Subscription\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BillingWebhookController extends Controller
{
    public function __invoke(Request $request, string $gateway, SubscriptionService $service): Response
    {
        $service->handleWebhook($request);

        return response()->noContent();
    }
}
