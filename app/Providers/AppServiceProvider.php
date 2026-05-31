<?php

namespace App\Providers;

use App\Services\SubscriptionGuard;
use App\Support\TenantContext;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(['layouts.*', 'partials.*', 'cashier.*', 'admin.*', 'dashboard', 'billing.*'], function ($view): void {
            $view->with('currentTenant', TenantContext::get());
        });

        View::composer(['layouts.admin', 'layouts.pos'], function ($view): void {
            if (! auth()->check() || ! TenantContext::hasTenant()) {
                $view->with([
                    'trialDaysRemaining' => null,
                    'trialUrgency' => null,
                    'subscriptionStatus' => null,
                    'currentPlan' => null,
                    'planHasFeature' => fn (): bool => false,
                ]);

                return;
            }

            $guard = app(SubscriptionGuard::class);
            $subscription = $guard->current();

            $view->with([
                'trialDaysRemaining' => $guard->trialDaysRemaining(),
                'trialUrgency' => $guard->trialUrgency(),
                'subscriptionStatus' => $subscription?->status,
                'currentPlan' => $guard->currentPlan(),
                'planHasFeature' => fn (string $feature): bool => $guard->hasFeature($feature),
            ]);
        });
    }
}
