<?php

namespace App\Support;

use App\Models\Plan;

class PlanPricing
{
    public static function fullYearFromMonthly(int $monthlyPrice): int
    {
        return $monthlyPrice * 12;
    }

    public static function yearlyPriceFromMonthly(int $monthlyPrice, ?int $freeMonths = null): int
    {
        $freeMonths ??= (int) config('xiway.yearly_billing_free_months', 2);

        return $monthlyPrice * max(1, 12 - $freeMonths);
    }

    public static function fullYearTotal(Plan $plan): int
    {
        return self::fullYearFromMonthly((int) $plan->price_monthly_idr);
    }

    public static function yearlySavings(Plan $plan): int
    {
        $yearly = (int) $plan->price_yearly_idr;

        if ($yearly <= 0) {
            return 0;
        }

        return max(0, self::fullYearTotal($plan) - $yearly);
    }

    public static function yearlyDiscountPercent(Plan $plan): int
    {
        $full = self::fullYearTotal($plan);

        if ($full <= 0) {
            return 0;
        }

        return (int) round((self::yearlySavings($plan) / $full) * 100);
    }

    public static function yearlyEquivalentMonthly(Plan $plan): int
    {
        $yearly = (int) $plan->price_yearly_idr;

        if ($yearly <= 0) {
            return (int) $plan->price_monthly_idr;
        }

        return (int) round($yearly / 12);
    }

    public static function hasYearlyDiscount(Plan $plan): bool
    {
        return self::yearlySavings($plan) > 0;
    }

    public static function freeMonthsLabel(): string
    {
        $months = (int) config('xiway.yearly_billing_free_months', 2);

        return $months === 1 ? '1 bulan gratis' : "{$months} bulan gratis";
    }
}
