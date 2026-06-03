<?php

namespace App\Services;

use App\Models\MarketingFunnelEvent;
use Carbon\Carbon;

class MarketingFunnelAnalytics
{
    /**
     * @return array{
     *     days: int,
     *     steps: list<array{key: string, label: string, count: int, rate_from_previous: float|null, rate_from_landing: float|null}>,
     *     has_data: bool
     * }
     */
    public function summary(int $days = 7): array
    {
        $start = Carbon::today()->subDays($days - 1)->startOfDay();
        $end = Carbon::today()->endOfDay();

        $landing = $this->uniqueVisitors(MarketingFunnelEvent::EVENT_LANDING_VIEW, $start, $end);
        $registerView = $this->uniqueVisitors(MarketingFunnelEvent::EVENT_REGISTER_VIEW, $start, $end);
        $registerSubmit = $this->eventCount(MarketingFunnelEvent::EVENT_REGISTER_SUBMIT, $start, $end);
        $firstSale = $this->eventCount(MarketingFunnelEvent::EVENT_FIRST_SALE, $start, $end);

        $steps = [
            [
                'key' => 'landing',
                'label' => 'Kunjungan landing',
                'count' => $landing,
                'rate_from_previous' => null,
                'rate_from_landing' => $landing > 0 ? 100.0 : null,
            ],
            [
                'key' => 'register_view',
                'label' => 'Buka halaman daftar',
                'count' => $registerView,
                'rate_from_previous' => $this->rate($registerView, $landing),
                'rate_from_landing' => $this->rate($registerView, $landing),
            ],
            [
                'key' => 'register_submit',
                'label' => 'Daftar berhasil (trial)',
                'count' => $registerSubmit,
                'rate_from_previous' => $this->rate($registerSubmit, $registerView),
                'rate_from_landing' => $this->rate($registerSubmit, $landing),
            ],
            [
                'key' => 'first_sale',
                'label' => 'Transaksi pertama',
                'count' => $firstSale,
                'rate_from_previous' => $this->rate($firstSale, $registerSubmit),
                'rate_from_landing' => $this->rate($firstSale, $landing),
            ],
        ];

        return [
            'days' => $days,
            'steps' => $steps,
            'has_data' => $landing > 0 || $registerView > 0 || $registerSubmit > 0,
        ];
    }

    private function uniqueVisitors(string $event, Carbon $start, Carbon $end): int
    {
        return (int) MarketingFunnelEvent::query()
            ->where('event', $event)
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('visitor_hash')
            ->distinct('visitor_hash')
            ->count('visitor_hash');
    }

    private function eventCount(string $event, Carbon $start, Carbon $end): int
    {
        return (int) MarketingFunnelEvent::query()
            ->where('event', $event)
            ->whereBetween('created_at', [$start, $end])
            ->count();
    }

    private function rate(int $numerator, int $denominator): ?float
    {
        if ($denominator <= 0) {
            return null;
        }

        return round(($numerator / $denominator) * 100, 1);
    }
}
