<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Support\PlanPricing;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $plans = $this->loadPlans();

        return view('marketing.home', compact('plans'));
    }

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\Plan|object>
     */
    private function loadPlans(): \Illuminate\Support\Collection
    {
        try {
            $plans = Plan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            if ($plans->isNotEmpty()) {
                return $plans;
            }
        } catch (\Throwable) {
            // Database may be unavailable during deploy or in lightweight tests.
        }

        return collect(self::fallbackPlans());
    }

    /**
     * @return list<object>
     */
    public static function fallbackPlans(): array
    {
        $definitions = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price_monthly_idr' => 129000,
                'limits' => [
                    'max_outlets' => 1,
                    'max_users' => 2,
                    'max_transactions_monthly' => 2000,
                    'features' => ['cashier', 'reports_basic', 'products', 'categories'],
                ],
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'price_monthly_idr' => 249000,
                'limits' => [
                    'max_outlets' => 3,
                    'max_users' => 10,
                    'max_transactions_monthly' => 15000,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons'],
                ],
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price_monthly_idr' => 549000,
                'limits' => [
                    'max_outlets' => -1,
                    'max_users' => -1,
                    'max_transactions_monthly' => -1,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons', 'api', 'custom_domain'],
                ],
            ],
        ];

        return array_map(function (array $plan): object {
            return (object) array_merge($plan, [
                'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly($plan['price_monthly_idr']),
            ]);
        }, $definitions);
    }

    public function privacy(): View
    {
        return view('marketing.privacy');
    }

    public function terms(): View
    {
        return view('marketing.terms');
    }
}
