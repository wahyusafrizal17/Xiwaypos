<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionEvent;
use App\Models\User;
use Carbon\CarbonInterface;

class SubscriptionActivator
{
    public function activate(
        Subscription $subscription,
        Plan $plan,
        User $actor,
        CarbonInterface $periodEnd,
        ?string $note = null
    ): Subscription {
        $from = $subscription->status;

        $subscription->update([
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_ACTIVE,
            'trial_ends_at' => null,
            'grace_ends_at' => null,
            'cancelled_at' => null,
            'activated_by_user_id' => $actor->id,
            'activated_at' => now(),
            'current_period_start' => now(),
            'current_period_end' => $periodEnd,
        ]);

        SubscriptionEvent::create([
            'subscription_id' => $subscription->id,
            'from_status' => $from,
            'to_status' => Subscription::STATUS_ACTIVE,
            'actor_user_id' => $actor->id,
            'note' => $note ?? 'Activated by platform admin',
        ]);

        return $subscription->fresh();
    }

    public function extendTrial(Subscription $subscription, User $actor, int $days, ?string $note = null): Subscription
    {
        $from = $subscription->status;
        $endsAt = ($subscription->trial_ends_at ?? now())->copy()->addDays($days);

        $subscription->update([
            'status' => Subscription::STATUS_TRIALING,
            'trial_ends_at' => $endsAt,
            'current_period_end' => $endsAt,
            'grace_ends_at' => null,
        ]);

        SubscriptionEvent::create([
            'subscription_id' => $subscription->id,
            'from_status' => $from,
            'to_status' => Subscription::STATUS_TRIALING,
            'actor_user_id' => $actor->id,
            'note' => $note ?? "Trial extended {$days} days",
        ]);

        return $subscription->fresh();
    }

    public function suspend(Subscription $subscription, User $actor, ?string $note = null): Subscription
    {
        $from = $subscription->status;

        $subscription->update(['status' => Subscription::STATUS_SUSPENDED]);

        SubscriptionEvent::create([
            'subscription_id' => $subscription->id,
            'from_status' => $from,
            'to_status' => Subscription::STATUS_SUSPENDED,
            'actor_user_id' => $actor->id,
            'note' => $note ?? 'Suspended by platform admin',
        ]);

        return $subscription->fresh();
    }
}
