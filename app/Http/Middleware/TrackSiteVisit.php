<?php

namespace App\Http\Middleware;

use App\Services\SiteVisitTracker;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function __construct(
        protected SiteVisitTracker $tracker
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful() && $this->tracker->shouldTrack($request)) {
            $this->tracker->record($request);
        }

        return $response;
    }
}
