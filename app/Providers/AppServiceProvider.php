<?php

namespace App\Providers;

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\AI\Observers\UserCreditObserver;
use App\Domain\AI\Providers\OpenAiProvider;
use App\Domain\Resume\Models\Resume;
use App\Models\User;
use App\Policies\ResumePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AiProviderInterface::class, function () {
            $provider = config('ai.provider');

            return match ($provider) {
                'openai' => new OpenAiProvider(config('ai.providers.openai')),
                default => throw new \InvalidArgumentException("Provedor de IA não suportado: {$provider}"),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Resume::class, ResumePolicy::class);

        User::observe(UserCreditObserver::class);
    }
}
