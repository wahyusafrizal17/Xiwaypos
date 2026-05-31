<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionGuard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanFeature
{
    public function __construct(
        protected SubscriptionGuard $subscriptionGuard
    ) {}

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if ($this->subscriptionGuard->hasFeature($feature)) {
            return $next($request);
        }

        $message = 'Fitur ini tidak tersedia di paket langganan Anda. Upgrade paket untuk melanjutkan.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return redirect()
            ->route('billing.index')
            ->with('error', $message);
    }
}
