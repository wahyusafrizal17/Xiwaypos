<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionGuard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionActive
{
    public function __construct(
        protected SubscriptionGuard $subscriptionGuard
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! config('xiway.tenancy_enabled', true)) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        $allowed = config('xiway.billing_routes', []);

        if ($routeName !== null && in_array($routeName, $allowed, true)) {
            return $next($request);
        }

        if ($this->subscriptionGuard->isBlocked()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Langganan berakhir. Silakan upgrade paket.',
                ], 402);
            }

            return redirect()
                ->route('upgrade.index')
                ->with('error', $this->subscriptionGuard->statusMessage());
        }

        if ($this->subscriptionGuard->isReadOnly() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Masa tenggang: transaksi baru dinonaktifkan.',
                ], 402);
            }

            return redirect()
                ->route('upgrade.index')
                ->with('error', $this->subscriptionGuard->statusMessage());
        }

        return $next($request);
    }
}
