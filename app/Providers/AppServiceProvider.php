<?php

namespace App\Providers;

use App\Domain\AI\Contracts\AiProviderInterface;
use App\Domain\AI\Observers\UserCreditObserver;
use App\Domain\AI\Providers\OpenAiProvider;
use App\Domain\Job\Models\JobDescription;
use App\Domain\Payment\Contracts\PaymentGatewayInterface;
use App\Domain\Payment\Gateways\MercadoPagoGateway;
use App\Domain\Resume\Models\Resume;
use App\Models\User;
use App\Policies\JobDescriptionPolicy;
use App\Policies\ResumePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
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

        $this->app->bind(PaymentGatewayInterface::class, function () {
            $gateway = config('payment.gateway');

            return match ($gateway) {
                'mercadopago' => new MercadoPagoGateway(config('payment.gateways.mercadopago')),
                default => throw new \InvalidArgumentException("Gateway de pagamento não suportado: {$gateway}"),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Resume::class, ResumePolicy::class);
        Gate::policy(JobDescription::class, JobDescriptionPolicy::class);

        Gate::define('admin', fn (User $user) => $user->is_admin);

        RateLimiter::for('ai', fn (Request $request) => Limit::perMinute(30)->by($request->user()?->id ?: $request->ip()));

        User::observe(UserCreditObserver::class);
    }
}
