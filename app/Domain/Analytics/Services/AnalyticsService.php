<?php

namespace App\Domain\Analytics\Services;

use App\Domain\Analytics\Models\AnalyticsEvent;
use App\Models\User;

/**
 * Registro simples de eventos de funil (landing_view, signup, resume_created,
 * resume_analysis, pdf_generated, checkout_started, payment_completed, ...).
 */
class AnalyticsService
{
    public function track(string $event, ?User $user = null, array $properties = []): void
    {
        AnalyticsEvent::create([
            'user_id' => $user?->id,
            'event' => $event,
            'properties' => $properties,
        ]);
    }
}
