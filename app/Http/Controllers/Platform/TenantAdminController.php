<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\SubscriptionActivator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantAdminController extends Controller
{
    public function __construct(
        protected SubscriptionActivator $activator
    ) {}

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $query = Tenant::query()
            ->with(['owner', 'subscription.plan'])
            ->latest();

        if ($status !== '') {
            $query->whereHas('subscription', fn ($q) => $q->where('status', $status));
        }

        $tenants = $query->paginate(20)->withQueryString();

        return view('platform.tenants.index', compact('tenants', 'status'));
    }

    public function show(Tenant $tenant): View
    {
        $tenant->load(['owner', 'subscription.plan', 'users']);

        $plans = Plan::query()->where('is_active', true)->orderBy('sort_order')->get();

        return view('platform.tenants.show', compact('tenant', 'plans'));
    }

    public function activate(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $request->validate([
            'plan_slug' => ['required', 'exists:plans,slug'],
            'months' => ['required', 'integer', 'min:1', 'max:24'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $subscription = $tenant->subscription;
        if ($subscription === null) {
            return back()->with('error', 'Subscription tidak ditemukan.');
        }

        $plan = Plan::query()->where('slug', $data['plan_slug'])->firstOrFail();

        $this->activator->activate(
            $subscription,
            $plan,
            $request->user(),
            now()->addMonths((int) $data['months']),
            $data['note'] ?? null
        );

        return back()->with('success', "Langganan {$tenant->displayName()} diaktifkan ({$plan->name}).");
    }

    public function extendTrial(Request $request, Tenant $tenant): RedirectResponse
    {
        $data = $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:90'],
        ]);

        $subscription = $tenant->subscription;
        if ($subscription === null) {
            return back()->with('error', 'Subscription tidak ditemukan.');
        }

        $this->activator->extendTrial($subscription, $request->user(), (int) $data['days']);

        return back()->with('success', "Trial {$tenant->displayName()} diperpanjang {$data['days']} hari.");
    }

    public function suspend(Request $request, Tenant $tenant): RedirectResponse
    {
        $subscription = $tenant->subscription;
        if ($subscription === null) {
            return back()->with('error', 'Subscription tidak ditemukan.');
        }

        $this->activator->suspend($subscription, $request->user(), $request->string('note')->toString() ?: null);

        return back()->with('success', "Tenant {$tenant->displayName()} ditangguhkan.");
    }
}
