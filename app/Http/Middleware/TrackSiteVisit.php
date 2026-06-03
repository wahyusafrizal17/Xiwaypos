<?php

namespace App\Http\Middleware;

use App\Services\MarketingFunnelTracker;
use App\Services\SiteVisitTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function __construct(
        protected SiteVisitTracker $tracker,
        protected MarketingFunnelTracker $funnelTracker
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful() && $this->tracker->shouldTrack($request)) {
            $this->tracker->record($request);

            $routeName = $request->route()?->getName();
            if ($routeName === 'marketing.home') {
                $this->funnelTracker->recordLandingView($request);
            } elseif ($routeName === 'register') {
                $this->funnelTracker->recordRegisterView($request);
            }
        }

        return $response;
    }
}
