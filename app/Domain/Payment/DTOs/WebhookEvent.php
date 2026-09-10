<?php

namespace App\Domain\Payment\DTOs;

final readonly class WebhookEvent
{
    public function __construct(
        public string $type, // payment.approved | payment.failed | subscription.cancelled | subscription.renewed
        public ?string $gatewaySubscriptionId,
        public ?string $gatewayPaymentId,
        public ?int $amountCents,
    ) {}
}
