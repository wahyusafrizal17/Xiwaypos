<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\TrialNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyTrialExpiry extends Command
{
    protected $signature = 'subscriptions:notify-trial-expiry';

    protected $description = 'Send in-app logged reminders at H-7, H-3, H-1 for trialing tenants';

    public function handle(): int
    {
        $thresholds = [7, 3, 1];
        $sent = 0;

        foreach ($thresholds as $days) {
            Subscription::query()
                ->where('status', Subscription::STATUS_TRIALING)
                ->whereNotNull('trial_ends_at')
                ->with('tenant.owner')
                ->get()
                ->filter(function (Subscription $subscription) use ($days): bool {
                    $remaining = (int) now()->startOfDay()->diffInDays(
                        $subscription->trial_ends_at->copy()->startOfDay(),
                        false
                    );

                    return $remaining === $days;
                })
                ->each(function (Subscription $subscription) use ($days, &$sent): void {
                    $exists = TrialNotification::query()
                        ->where('tenant_id', $subscription->tenant_id)
                        ->where('days_remaining', $days)
                        ->where('channel', 'log')
                        ->exists();

                    if ($exists) {
                        return;
                    }

                    TrialNotification::create([
                        'tenant_id' => $subscription->tenant_id,
                        'days_remaining' => $days,
                        'channel' => 'log',
                        'sent_at' => now(),
                    ]);

                    Log::info('Trial expiry reminder', [
                        'tenant_id' => $subscription->tenant_id,
                        'days_remaining' => $days,
                        'email' => $subscription->tenant?->owner?->email,
                    ]);

                    $sent++;
                });
        }

        $this->info("Recorded {$sent} trial notifications.");

        return self::SUCCESS;
    }
}
