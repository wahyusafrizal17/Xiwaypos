<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPaymentRequest;
use App\Models\Tenant;
use App\Services\SiteVisitAnalytics;
use Illuminate\View\View;

class PlatformDashboardController extends Controller
{
    public function __construct(
        protected SiteVisitAnalytics $siteVisitAnalytics
    ) {}

    public function index(): View
    {
        $totalTenants = Tenant::query()->count();

        $trialingTenants = Tenant::query()
            ->whereHas('subscription', fn ($q) => $q->where('status', Subscription::STATUS_TRIALING))
            ->count();

        $activeTenants = Tenant::query()
            ->whereHas('subscription', fn ($q) => $q->where('status', Subscription::STATUS_ACTIVE))
            ->count();

        $pendingPayments = SubscriptionPaymentRequest::query()
            ->where('status', SubscriptionPaymentRequest::STATUS_PENDING)
            ->count();

        $recentTenants = Tenant::query()
            ->with(['owner', 'subscription.plan'])
            ->latest()
            ->limit(5)
            ->get();

        $recentPaymentRequests = SubscriptionPaymentRequest::query()
            ->with(['tenant', 'plan', 'requester'])
            ->where('status', SubscriptionPaymentRequest::STATUS_PENDING)
            ->latest()
            ->limit(5)
            ->get();

        $trafficChart = $this->siteVisitAnalytics->chartData(7);

        return view('platform.dashboard', compact(
            'totalTenants',
            'trialingTenants',
            'activeTenants',
            'pendingPayments',
            'recentTenants',
            'recentPaymentRequests',
            'trafficChart',
        ));
    }
}
