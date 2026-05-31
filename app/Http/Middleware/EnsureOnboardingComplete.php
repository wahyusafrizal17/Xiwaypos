<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = TenantContext::get();

        if ($tenant === null || $tenant->onboarding_completed_at !== null) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        $onboardingRoutes = config('xiway.onboarding_routes', []);

        if ($routeName !== null && in_array($routeName, $onboardingRoutes, true)) {
            return $next($request);
        }

        $allowed = config('xiway.billing_routes', []);
        if ($routeName !== null && in_array($routeName, $allowed, true)) {
            return $next($request);
        }

        if ($routeName === 'logout') {
            return $next($request);
        }

        return redirect()->route('onboarding.store');
    }
}
