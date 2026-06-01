<?php

namespace App\Services;

use App\Models\SiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteVisitTracker
{
    /** @var list<string> */
    private const TRACKED_ROUTE_NAMES = [
        'marketing.home',
        'marketing.privacy',
        'marketing.terms',
        'register',
        'login',
    ];

    public function shouldTrack(Request $request): bool
    {
        if (! config('xiway.track_site_visits', true)) {
            return false;
        }

        if (! $request->isMethod('GET')) {
            return false;
        }

        $routeName = $request->route()?->getName();

        return $routeName !== null && in_array($routeName, self::TRACKED_ROUTE_NAMES, true);
    }

    public function record(Request $request): void
    {
        if (! $this->shouldTrack($request)) {
            return;
        }

        $userAgent = (string) $request->userAgent();

        if ($this->looksLikeBot($userAgent)) {
            return;
        }

        $path = '/'.ltrim($request->path(), '/');
        $visitorHash = hash('sha256', $request->ip().'|'.substr($userAgent, 0, 160));
        $throttleKey = 'site-visit:'.$visitorHash.':'.$path;

        if (! Cache::add($throttleKey, true, now()->addMinutes(2))) {
            return;
        }

        SiteVisit::query()->create([
            'path' => $path,
            'visitor_hash' => $visitorHash,
            'visited_at' => now(),
        ]);
    }

    private function looksLikeBot(string $userAgent): bool
    {
        if ($userAgent === '') {
            return true;
        }

        return (bool) preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit/i', $userAgent);
    }
}
