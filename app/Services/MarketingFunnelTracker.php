<?php

namespace App\Services;

use App\Models\MarketingFunnelEvent;
use App\Models\Tenant;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MarketingFunnelTracker
{
    private const SESSION_KEY = 'marketing_visitor_hash';

    public function resolveVisitorHash(Request $request): string
    {
        $existing = $request->session()->get(self::SESSION_KEY);

        if (is_string($existing) && $existing !== '') {
            return $existing;
        }

        $userAgent = (string) $request->userAgent();
        $hash = hash('sha256', $request->ip().'|'.substr($userAgent, 0, 160));
        $request->session()->put(self::SESSION_KEY, $hash);

        return $hash;
    }

    public function recordLandingView(Request $request): void
    {
        $this->recordThrottled($request, MarketingFunnelEvent::EVENT_LANDING_VIEW);
    }

    public function recordRegisterView(Request $request): void
    {
        $this->recordThrottled($request, MarketingFunnelEvent::EVENT_REGISTER_VIEW);
    }

    public function recordRegisterSubmit(Request $request, Tenant $tenant): void
    {
        if (! config('xiway.track_site_visits', true)) {
            return;
        }

        MarketingFunnelEvent::query()->create([
            'event' => MarketingFunnelEvent::EVENT_REGISTER_SUBMIT,
            'visitor_hash' => $this->resolveVisitorHash($request),
            'tenant_id' => $tenant->id,
            'created_at' => now(),
        ]);
    }

    public function recordFirstSaleIfFirst(Transaction $transaction): void
    {
        if (! config('xiway.track_site_visits', true)) {
            return;
        }

        if ($transaction->status !== Transaction::STATUS_PAID || $transaction->tenant_id === null) {
            return;
        }

        $alreadyRecorded = MarketingFunnelEvent::query()
            ->where('tenant_id', $transaction->tenant_id)
            ->where('event', MarketingFunnelEvent::EVENT_FIRST_SALE)
            ->exists();

        if ($alreadyRecorded) {
            return;
        }

        $hadPriorSale = Transaction::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $transaction->tenant_id)
            ->where('status', Transaction::STATUS_PAID)
            ->where('id', '!=', $transaction->id)
            ->exists();

        if ($hadPriorSale) {
            return;
        }

        MarketingFunnelEvent::query()->create([
            'event' => MarketingFunnelEvent::EVENT_FIRST_SALE,
            'visitor_hash' => null,
            'tenant_id' => $transaction->tenant_id,
            'created_at' => now(),
        ]);
    }

    private function recordThrottled(Request $request, string $event): void
    {
        if (! config('xiway.track_site_visits', true)) {
            return;
        }

        $userAgent = (string) $request->userAgent();

        if ($this->looksLikeBot($userAgent)) {
            return;
        }

        $visitorHash = $this->resolveVisitorHash($request);
        $throttleKey = 'funnel:'.$event.':'.$visitorHash;

        if (! Cache::add($throttleKey, true, now()->addMinutes(2))) {
            return;
        }

        MarketingFunnelEvent::query()->create([
            'event' => $event,
            'visitor_hash' => $visitorHash,
            'tenant_id' => null,
            'created_at' => now(),
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
