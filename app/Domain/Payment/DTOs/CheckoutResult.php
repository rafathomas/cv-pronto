<?php

namespace App\Domain\Payment\DTOs;

final readonly class CheckoutResult
{
    public function __construct(
        public string $checkoutUrl,
        public ?string $gatewaySubscriptionId = null,
    ) {}
}
