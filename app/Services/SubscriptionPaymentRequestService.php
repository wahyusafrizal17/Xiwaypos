<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionPaymentRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubscriptionPaymentRequestService
{
    public function __construct(
        protected SubscriptionActivator $activator
    ) {}

    public function pendingForTenant(Tenant $tenant): ?SubscriptionPaymentRequest
    {
        return SubscriptionPaymentRequest::query()
            ->where('tenant_id', $tenant->id)
            ->where('status', SubscriptionPaymentRequest::STATUS_PENDING)
            ->latest()
            ->with('plan')
            ->first();
    }

    /**
     * @param  array{plan_id: int, billing_cycle: string, reference_number?: ?string, note?: ?string}  $data
     */
    public function submit(
        Tenant $tenant,
        User $requester,
        Subscription $subscription,
        Plan $plan,
        array $data,
        UploadedFile $proof
    ): SubscriptionPaymentRequest {
        if ($this->pendingForTenant($tenant) !== null) {
            throw ValidationException::withMessages([
                'proof' => 'Masih ada pengajuan langganan yang menunggu verifikasi admin.',
            ]);
        }

        $billingCycle = $data['billing_cycle'];
        $amount = $billingCycle === SubscriptionPaymentRequest::CYCLE_YEARLY
            ? (int) $plan->price_yearly_idr
            : (int) $plan->price_monthly_idr;

        if ($amount <= 0) {
            throw ValidationException::withMessages([
                'billing_cycle' => 'Paket ini belum memiliki harga untuk siklus yang dipilih.',
            ]);
        }

        $path = $proof->store(TenantStorage::billingProofPath(), 'uploads');

        return SubscriptionPaymentRequest::create([
            'tenant_id' => $tenant->id,
            'subscription_id' => $subscription->id,
            'requested_by_user_id' => $requester->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $billingCycle,
            'amount_idr' => $amount,
            'proof_path' => $path,
            'reference_number' => $data['reference_number'] ?? null,
            'note' => $data['note'] ?? null,
            'status' => SubscriptionPaymentRequest::STATUS_PENDING,
        ]);
    }

    public function approve(SubscriptionPaymentRequest $paymentRequest, User $reviewer, ?string $note = null): SubscriptionPaymentRequest
    {
        if (! $paymentRequest->isPending()) {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan ini sudah diproses.',
            ]);
        }

        return DB::transaction(function () use ($paymentRequest, $reviewer, $note) {
            $paymentRequest->load(['tenant.subscription', 'plan']);
            $subscription = $paymentRequest->subscription ?? $paymentRequest->tenant?->subscription;

            if ($subscription === null) {
                throw ValidationException::withMessages([
                    'subscription' => 'Langganan tenant tidak ditemukan.',
                ]);
            }

            $months = $paymentRequest->billing_cycle === SubscriptionPaymentRequest::CYCLE_YEARLY ? 12 : 1;

            $this->activator->activate(
                $subscription,
                $paymentRequest->plan,
                $reviewer,
                now()->addMonths($months),
                $note ?? "Pembayaran langganan #{$paymentRequest->id} disetujui"
            );

            $paymentRequest->update([
                'status' => SubscriptionPaymentRequest::STATUS_APPROVED,
                'reviewed_by_user_id' => $reviewer->id,
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);

            return $paymentRequest->fresh(['tenant', 'plan', 'requester', 'reviewer']);
        });
    }

    public function reject(SubscriptionPaymentRequest $paymentRequest, User $reviewer, string $reason): SubscriptionPaymentRequest
    {
        if (! $paymentRequest->isPending()) {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan ini sudah diproses.',
            ]);
        }

        $paymentRequest->update([
            'status' => SubscriptionPaymentRequest::STATUS_REJECTED,
            'reviewed_by_user_id' => $reviewer->id,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return $paymentRequest->fresh(['tenant', 'plan', 'requester', 'reviewer']);
    }
}
