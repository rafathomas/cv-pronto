<?php

namespace App\Listeners;

use App\Domain\Analytics\Services\AnalyticsService;
use Illuminate\Auth\Events\Registered;

class TrackUserSignup
{
    public function __construct(private readonly AnalyticsService $analytics) {}

    public function handle(Registered $event): void
    {
        $this->analytics->track('signup', $event->user);
    }
}
