<?php

namespace App\Models;

use App\Support\PlanPricing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'limits',
        'price_monthly_idr',
        'price_yearly_idr',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'limits' => 'array',
            'price_monthly_idr' => 'integer',
            'price_yearly_idr' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /** @return HasMany<Subscription, $this> */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function limit(string $key, mixed $default = null): mixed
    {
        return data_get($this->limits, $key, $default);
    }

    public function hasFeature(string $feature): bool
    {
        $features = $this->limit('features', []);

        return in_array($feature, $features, true);
    }

    public function fullYearMonthlyTotal(): int
    {
        return PlanPricing::fullYearTotal($this);
    }

    public function yearlySavings(): int
    {
        return PlanPricing::yearlySavings($this);
    }

    public function yearlyDiscountPercent(): int
    {
        return PlanPricing::yearlyDiscountPercent($this);
    }

    public function yearlyEquivalentMonthly(): int
    {
        return PlanPricing::yearlyEquivalentMonthly($this);
    }

    public function hasYearlyDiscount(): bool
    {
        return PlanPricing::hasYearlyDiscount($this);
    }
}
