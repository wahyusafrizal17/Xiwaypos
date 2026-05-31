<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $todayTotal = (int) Transaction::paid()->whereDate('created_at', $today)->sum('total');
        $todayCount = Transaction::paid()->whereDate('created_at', $today)->count();

        $monthlyTotal = (int) Transaction::paid()->where('created_at', '>=', $monthStart)->sum('total');

        $topProducts = TransactionDetail::query()
            ->select('transaction_details.product_id', DB::raw('SUM(transaction_details.qty) as qty_sold'))
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->where('transactions.status', Transaction::STATUS_PAID)
            ->groupBy('transaction_details.product_id')
            ->orderByDesc('qty_sold')
            ->limit(5)
            ->with('product.category')
            ->get();

        $salesChart = $this->buildSalesChart(7);

        return view('dashboard', compact(
            'todayTotal',
            'todayCount',
            'monthlyTotal',
            'topProducts',
            'salesChart',
        ));
    }

    /**
     * @return array{
     *     labels: list<string>,
     *     revenue: list<int>,
     *     transactions: list<int>,
     *     period_total: int,
     *     period_count: int,
     *     has_data: bool
     * }
     */
    private function buildSalesChart(int $days): array
    {
        $start = Carbon::today()->subDays($days - 1)->startOfDay();
        $end = Carbon::today()->endOfDay();

        /** @var Collection<string, object{revenue: int, tx_count: int}> $grouped */
        $grouped = Transaction::paid()
            ->whereBetween('created_at', [$start, $end])
            ->get(['created_at', 'total'])
            ->groupBy(fn (Transaction $transaction) => $transaction->created_at->toDateString())
            ->map(fn (Collection $rows) => (object) [
                'revenue' => (int) $rows->sum('total'),
                'tx_count' => $rows->count(),
            ]);

        $labels = [];
        $revenue = [];
        $transactions = [];
        $periodTotal = 0;
        $periodCount = 0;

        foreach (CarbonPeriod::create($start->toDateString(), $end->toDateString()) as $date) {
            $key = $date->format('Y-m-d');
            $day = $grouped->get($key);
            $dayRevenue = (int) ($day->revenue ?? 0);
            $dayCount = (int) ($day->tx_count ?? 0);

            $labels[] = Carbon::parse($key)->locale('id')->isoFormat('ddd, D MMM');
            $revenue[] = $dayRevenue;
            $transactions[] = $dayCount;
            $periodTotal += $dayRevenue;
            $periodCount += $dayCount;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenue,
            'transactions' => $transactions,
            'period_total' => $periodTotal,
            'period_count' => $periodCount,
            'has_data' => $periodCount > 0,
        ];
    }
}
