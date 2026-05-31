<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\SubscriptionGuard;
use App\Services\SubscriptionPaymentRequestService;
use App\Support\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        protected SubscriptionGuard $subscriptionGuard,
        protected SubscriptionPaymentRequestService $paymentRequests
    ) {}

    public function index(): View
    {
        $tenant = TenantContext::get();
        $subscription = $this->subscriptionGuard->current();
        $plans = Plan::query()->where('is_active', true)->orderBy('sort_order')->get();
        $pendingPaymentRequest = $tenant
            ? $this->paymentRequests->pendingForTenant($tenant)
            : null;

        return view('billing.index', [
            'tenant' => $tenant,
            'subscription' => $subscription,
            'plans' => $plans,
            'statusMessage' => $this->subscriptionGuard->statusMessage(),
            'trialDaysRemaining' => $this->subscriptionGuard->trialDaysRemaining(),
            'pendingPaymentRequest' => $pendingPaymentRequest,
            'billingBank' => config('xiway.billing_bank'),
        ]);
    }

    public function storePaymentProof(Request $request): RedirectResponse
    {
        $tenant = TenantContext::get();
        $subscription = $this->subscriptionGuard->current();

        if ($tenant === null || $subscription === null) {
            return back()->with('error', 'Data langganan tidak ditemukan.');
        }

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')->where('is_active', true)],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $plan = Plan::query()->findOrFail($validated['plan_id']);

        $this->paymentRequests->submit(
            $tenant,
            $request->user(),
            $subscription,
            $plan,
            $validated,
            $request->file('proof')
        );

        return redirect()
            ->route('billing.index')
            ->with('success', 'Bukti pembayaran terkirim. Menunggu verifikasi admin.');
    }
}
