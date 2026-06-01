<?php

namespace App\Services;

use App\Models\SiteVisit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SiteVisitAnalytics
{
    /**
     * @return array{
     *     labels: list<string>,
     *     page_views: list<int>,
     *     unique_visitors: list<int>,
     *     period_views: int,
     *     period_uniques: int,
     *     today_views: int,
     *     today_uniques: int,
     *     has_data: bool
     * }
     */
    public function chartData(int $days = 7): array
    {
        $start = Carbon::today()->subDays($days - 1)->startOfDay();
        $end = Carbon::today()->endOfDay();

        /** @var Collection<string, object{page_views: int, unique_visitors: int}> $grouped */
        $grouped = SiteVisit::query()
            ->whereBetween('visited_at', [$start, $end])
            ->select([
                DB::raw('DATE(visited_at) as visit_day'),
                DB::raw('COUNT(*) as page_views'),
                DB::raw('COUNT(DISTINCT visitor_hash) as unique_visitors'),
            ])
            ->groupBy('visit_day')
            ->get()
            ->keyBy(fn ($row) => (string) $row->visit_day);

        $labels = [];
        $pageViews = [];
        $uniqueVisitors = [];
        $periodViews = 0;
        $periodUniques = 0;

        foreach (CarbonPeriod::create($start->toDateString(), $end->toDateString()) as $date) {
            $key = $date->format('Y-m-d');
            $day = $grouped->get($key);
            $views = (int) ($day->page_views ?? 0);
            $uniques = (int) ($day->unique_visitors ?? 0);

            $labels[] = Carbon::parse($key)->locale('id')->isoFormat('ddd, D MMM');
            $pageViews[] = $views;
            $uniqueVisitors[] = $uniques;
            $periodViews += $views;
        }

        $periodUniques = (int) SiteVisit::query()
            ->whereBetween('visited_at', [$start, $end])
            ->distinct('visitor_hash')
            ->count('visitor_hash');

        $todayViews = (int) SiteVisit::query()
            ->whereDate('visited_at', Carbon::today())
            ->count();

        $todayUniques = (int) SiteVisit::query()
            ->whereDate('visited_at', Carbon::today())
            ->distinct('visitor_hash')
            ->count('visitor_hash');

        return [
            'labels' => $labels,
            'page_views' => $pageViews,
            'unique_visitors' => $uniqueVisitors,
            'period_views' => $periodViews,
            'period_uniques' => $periodUniques,
            'today_views' => $todayViews,
            'today_uniques' => $todayUniques,
            'has_data' => $periodViews > 0,
        ];
    }
}
