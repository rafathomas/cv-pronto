<?php

namespace App\Domain\AI\DTOs;

final readonly class AiUsageMeta
{
    public function __construct(
        public string $provider,
        public string $model,
        public int $inputTokens,
        public int $outputTokens,
    ) {}

    public function estimatedCostUsd(): float
    {
        $pricing = config("ai.pricing.{$this->model}");

        if (! $pricing) {
            return 0.0;
        }

        return round(
            ($this->inputTokens / 1000) * $pricing['input']
            + ($this->outputTokens / 1000) * $pricing['output'],
            6,
        );
    }
}
