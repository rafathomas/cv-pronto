<?php

namespace Database\Seeders;

use App\Domain\Subscription\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('plans.plans') as $key => $plan) {
            Plan::updateOrCreate(
                ['key' => $key],
                [
                    'name' => $plan['name'],
                    'price_cents' => $plan['price'],
                    'interval' => $plan['interval'],
                    'limits' => $plan['limits'],
                    'features' => $plan['features'],
                    'is_active' => true,
                ],
            );
        }
    }
}
