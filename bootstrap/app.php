<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureOnboardingComplete;
use App\Http\Middleware\EnsurePlanFeature;
use App\Http\Middleware\EnsurePlatformAdmin;
use App\Http\Middleware\EnsureStaff;
use App\Http\Middleware\EnsureSubscriptionActive;
use App\Http\Middleware\EnsureTenantMember;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            $marketingDomain = config('xiway.marketing_domain');
            $appDomain = config('xiway.app_domain');
            $useDomainSplit = $marketingDomain && $appDomain && $marketingDomain !== $appDomain;

            if ($useDomainSplit) {
                Route::middleware('web')
                    ->domain($marketingDomain)
                    ->name('marketing.')
                    ->group(base_path('routes/marketing.php'));

                Route::middleware('web')
                    ->domain($appDomain)
                    ->group(base_path('routes/web.php'));
            } else {
                if (config('xiway.landing_on_root', true)) {
                    Route::middleware('web')
                        ->name('marketing.')
                        ->group(base_path('routes/marketing.php'));
                }

                Route::middleware('web')
                    ->group(base_path('routes/web.php'));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'staff' => EnsureStaff::class,
            'tenant' => ResolveTenant::class,
            'tenant.member' => EnsureTenantMember::class,
            'subscription' => EnsureSubscriptionActive::class,
            'onboarding' => EnsureOnboardingComplete::class,
            'platform.admin' => EnsurePlatformAdmin::class,
            'plan.feature' => EnsurePlanFeature::class,
        ]);

        $middleware->appendToGroup('web', [
            ResolveTenant::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
