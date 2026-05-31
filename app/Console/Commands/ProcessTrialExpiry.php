<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class ProcessTrialExpiry extends Command
{
    protected $signature = 'subscriptions:process-expiry';

    protected $description = 'Expire trials and grace periods that have ended';

    public function handle(): int
    {
        $count = 0;

        Subscription::query()
            ->where('status', Subscription::STATUS_TRIALING)
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->each(function (Subscription $subscription) use (&$count): void {
                if (config('xiway.trial_skip_grace', true)) {
                    $subscription->update(['status' => Subscription::STATUS_EXPIRED]);
                } else {
                    $subscription->update([
                        'status' => Subscription::STATUS_GRACE,
                        'grace_ends_at' => now()->addDays((int) config('xiway.grace_days_after_trial', 3)),
                    ]);
                }
                $count++;
            });

        Subscription::query()
            ->where('status', Subscription::STATUS_GRACE)
            ->whereNotNull('grace_ends_at')
            ->where('grace_ends_at', '<', now())
            ->each(function (Subscription $subscription) use (&$count): void {
                $subscription->update(['status' => Subscription::STATUS_EXPIRED]);
                $count++;
            });

        $this->info("Processed {$count} subscription state changes.");

        return self::SUCCESS;
    }
}
