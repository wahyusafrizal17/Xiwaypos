<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Support\TenantContext;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;

class SubscriptionGuard
{
    public function current(): ?Subscription
    {
        $tenant = TenantContext::get();

        if ($tenant === null) {
            return null;
        }

        return $tenant->subscription()->with('plan')->first();
    }

    public function allowsWrite(): bool
    {
        $subscription = $this->current();

        if ($subscription === null) {
            return false;
        }

        $this->refreshExpiredStates($subscription);

        return $subscription->fresh()->allowsWrite();
    }

    public function isReadOnly(): bool
    {
        $subscription = $this->current();

        if ($subscription === null) {
            return true;
        }

        $this->refreshExpiredStates($subscription);

        return $subscription->fresh()->isReadOnly();
    }

    public function isBlocked(): bool
    {
        $subscription = $this->current();

        if ($subscription === null) {
            return true;
        }

        $this->refreshExpiredStates($subscription);

        return $subscription->fresh()->isBlocked();
    }

    public function trialDaysRemaining(): ?int
    {
        $subscription = $this->current();

        if ($subscription === null || $subscription->status !== Subscription::STATUS_TRIALING) {
            return null;
        }

        if ($subscription->trial_ends_at === null) {
            return null;
        }

        $days = (int) now()->startOfDay()->diffInDays($subscription->trial_ends_at->copy()->startOfDay(), false);

        return max(0, $days);
    }

    public function trialUrgency(): ?string
    {
        $days = $this->trialDaysRemaining();

        if ($days === null) {
            return null;
        }

        if ($days <= 1) {
            return 'critical';
        }
        if ($days <= 3) {
            return 'warning';
        }
        if ($days <= 7) {
            return 'notice';
        }

        return 'info';
    }

    public function withinTransactionLimit(): bool
    {
        $subscription = $this->current();

        if ($subscription === null || $subscription->plan === null) {
            return false;
        }

        $max = $subscription->plan->limit('max_transactions_monthly');

        if ($max === null || $max === -1) {
            return true;
        }

        $tenantId = TenantContext::requireId();
        $count = Cache::remember(
            "tenant:{$tenantId}:trx:".now()->format('Y-m'),
            now()->addMinutes(5),
            fn () => Transaction::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->where('status', Transaction::STATUS_PAID)
                ->where('created_at', '>=', now()->startOfMonth())
                ->count()
        );

        return $count < (int) $max;
    }

    public function maxUsers(): int
    {
        $subscription = $this->current();

        if ($subscription?->plan === null) {
            return 0;
        }

        return (int) $subscription->plan->limit('max_users', 2);
    }

    public function hasUnlimitedUsers(): bool
    {
        return $this->maxUsers() === -1;
    }

    public function currentPlan(): ?\App\Models\Plan
    {
        return $this->current()?->plan;
    }

    public function hasFeature(string $feature): bool
    {
        $subscription = $this->current();

        if ($subscription === null || $subscription->plan === null) {
            return false;
        }

        if ($subscription->isBlocked()) {
            return false;
        }

        return $subscription->plan->hasFeature($feature);
    }

    public function refreshExpiredStates(Subscription $subscription): void
    {
        if ($subscription->status === Subscription::STATUS_TRIALING
            && $subscription->trial_ends_at !== null
            && $subscription->trial_ends_at->isPast()) {

            if (config('xiway.trial_skip_grace', true)) {
                $subscription->update(['status' => Subscription::STATUS_EXPIRED]);

                return;
            }

            $graceDays = (int) config('xiway.grace_days_after_trial', 3);
            $subscription->update([
                'status' => Subscription::STATUS_GRACE,
                'grace_ends_at' => now()->addDays($graceDays),
            ]);
        }

        if ($subscription->status === Subscription::STATUS_GRACE
            && $subscription->grace_ends_at !== null
            && $subscription->grace_ends_at->isPast()) {
            $subscription->update(['status' => Subscription::STATUS_EXPIRED]);
        }

        if ($subscription->status === Subscription::STATUS_CANCELLED
            && $subscription->current_period_end !== null
            && $subscription->current_period_end->isPast()) {
            $subscription->update(['status' => Subscription::STATUS_EXPIRED]);
        }
    }

    public function statusMessage(): ?string
    {
        $subscription = $this->current();

        if ($subscription === null) {
            return 'Langganan tidak ditemukan.';
        }

        $days = $this->trialDaysRemaining();

        return match ($subscription->status) {
            Subscription::STATUS_TRIALING => $days !== null
                ? "Masa trial tersisa {$days} hari."
                : null,
            Subscription::STATUS_GRACE => 'Langganan dalam masa tenggang. Segera perpanjang untuk melanjutkan transaksi.',
            Subscription::STATUS_EXPIRED => 'Trial atau langganan berakhir. Hubungi admin untuk upgrade.',
            Subscription::STATUS_SUSPENDED => 'Akun bisnis ditangguhkan. Hubungi Xiway POS.',
            Subscription::STATUS_PAST_DUE => 'Pembayaran gagal. Perbarui metode pembayaran.',
            default => null,
        };
    }
}
