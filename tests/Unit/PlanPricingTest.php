<?php

namespace Tests\Unit;

use App\Models\Plan;
use App\Support\PlanPricing;
use PHPUnit\Framework\TestCase;

class PlanPricingTest extends TestCase
{
    public function test_yearly_price_is_cheaper_than_twelve_months(): void
    {
        $plan = new Plan([
            'price_monthly_idr' => 249000,
            'price_yearly_idr' => 2490000,
        ]);

        $this->assertSame(2988000, PlanPricing::fullYearTotal($plan));
        $this->assertSame(498000, PlanPricing::yearlySavings($plan));
        $this->assertSame(17, PlanPricing::yearlyDiscountPercent($plan));
        $this->assertSame(207500, PlanPricing::yearlyEquivalentMonthly($plan));
    }

    public function test_yearly_price_from_monthly_applies_free_months(): void
    {
        $this->assertSame(1290000, PlanPricing::yearlyPriceFromMonthly(129000, 2));
        $this->assertSame(2490000, PlanPricing::yearlyPriceFromMonthly(249000, 2));
        $this->assertSame(5490000, PlanPricing::yearlyPriceFromMonthly(549000, 2));
    }
}
