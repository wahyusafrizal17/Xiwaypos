@extends('layouts.admin')

@section('title', 'Langganan')

@section('page_header')
    <div>
        <h1>Langganan</h1>
        <p>Paket, status trial, dan upgrade langganan toko Anda.</p>
    </div>
@endsection

@section('content')
    @include('partials.billing-styles')

    @php
        $currentPlanId = $subscription?->plan_id;
        $statusLabels = [
            'trialing' => 'Trial',
            'active' => 'Aktif',
            'past_due' => 'Tertunda',
            'grace' => 'Tenggang',
            'expired' => 'Berakhir',
            'cancelled' => 'Batal',
            'suspended' => 'Suspend',
        ];
        $statusPillClass = match ($subscription?->status) {
            'trialing', 'active' => 'is-trial',
            'grace', 'past_due' => 'is-warning',
            'expired', 'suspended' => 'is-danger',
            default => 'is-neutral',
        };
        $trialTotalDays = 14;
        $trialProgress = $trialDaysRemaining !== null
            ? max(0, min(100, (($trialTotalDays - $trialDaysRemaining) / $trialTotalDays) * 100))
            : 0;
        $plansPayload = $plans->map(fn ($plan) => [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'price_monthly_idr' => $plan->price_monthly_idr,
            'price_yearly_idr' => $plan->price_yearly_idr,
            'full_year_idr' => $plan->fullYearMonthlyTotal(),
            'yearly_savings_idr' => $plan->yearlySavings(),
            'yearly_discount_percent' => $plan->yearlyDiscountPercent(),
            'yearly_equivalent_monthly_idr' => $plan->yearlyEquivalentMonthly(),
        ])->values();
        $yearlyFreeMonthsLabel = \App\Support\PlanPricing::freeMonthsLabel();
        $hasPending = $pendingPaymentRequest !== null;
    @endphp

    <div
        class="billing-page"
        x-data="billingPage({{ \Illuminate\Support\Js::from([
            'plans' => $plansPayload,
            'bank' => $billingBank,
            'hasPending' => $hasPending,
            'openPlanId' => old('plan_id') ? (int) old('plan_id') : null,
        ]) }})"
    >
        @if (session('success'))
            <div class="billing-alert is-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="billing-alert">{{ session('error') }}</div>
        @endif

        @if ($pendingPaymentRequest)
            <div class="billing-alert is-pending">
                Pengajuan langganan paket <strong>{{ $pendingPaymentRequest->plan?->name }}</strong>
                ({{ $pendingPaymentRequest->billingCycleLabel() }}) sedang <strong>menunggu verifikasi admin</strong>.
                Diajukan {{ $pendingPaymentRequest->created_at->diffForHumans() }}.
            </div>
        @endif

        <div class="billing-status">
            <div class="billing-status-grid">
                <div>
                    <p class="billing-status-eyebrow">Langganan saat ini</p>
                    <h2 class="billing-status-title">{{ $tenant?->displayName() }}</h2>

                    @if ($subscription)
                        <div class="billing-status-meta">
                            <span>Paket <strong>{{ $subscription->plan?->name ?? '—' }}</strong></span>
                            <span class="billing-status-pill {{ $statusPillClass }}">
                                {{ $statusLabels[$subscription->status] ?? strtoupper($subscription->status) }}
                            </span>
                        </div>

                        @if ($statusMessage)
                            <p @class(['billing-status-note', 'is-alert' => in_array($subscription->status, ['expired', 'grace', 'suspended'], true)])>
                                {{ $statusMessage }}
                            </p>
                        @elseif ($subscription->status === 'active')
                            <p class="billing-status-note">Langganan aktif. Anda dapat upgrade kapan saja ke paket yang lebih tinggi.</p>
                        @endif
                    @else
                        <p class="billing-status-note">Belum ada data langganan. Pilih paket di bawah untuk memulai.</p>
                    @endif
                </div>

                @if ($trialDaysRemaining !== null)
                    <div class="billing-trial-card">
                        <div class="billing-trial-top">
                            <div>
                                <div class="billing-trial-days">{{ $trialDaysRemaining }}<span>hari</span></div>
                                <div class="billing-trial-label">Sisa masa trial</div>
                            </div>
                        </div>
                        <div class="billing-trial-bar" aria-hidden="true">
                            <div class="billing-trial-bar-fill" style="width: {{ $trialProgress }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div id="paket" class="billing-section">
            <p class="billing-section-label">Paket langganan</p>
            <h2 class="billing-section-title">Pilih paket yang sesuai</h2>
            <p class="billing-section-desc">Bayar tahunan lebih hemat — {{ $yearlyFreeMonthsLabel }}. Pilih paket, transfer, lalu upload bukti pembayaran.</p>
        </div>

        <div class="billing-plans">
            @foreach ($plans as $plan)
                @php
                    $isCurrent = $currentPlanId === $plan->id;
                    $isFeatured = ($plan->slug ?? '') === 'business';
                    $isCurrentActive = $isCurrent && $subscription?->status === 'active';
                    $featureLines = \App\Support\PlanMarketing::featureLines($plan);
                    $monthlyFormatted = number_format($plan->price_monthly_idr, 0, ',', '.');
                    $yearlyFormatted = number_format($plan->price_yearly_idr ?? 0, 0, ',', '.');
                    $planPayload = $plansPayload->firstWhere('id', $plan->id);
                @endphp
                <article @class([
                    'billing-plan',
                    'is-current' => $isCurrent,
                    'is-featured' => $isFeatured && ! $isCurrent,
                ])>
                    @if ($isCurrent)
                        <span class="billing-plan-badge is-current">Paket Anda</span>
                    @elseif ($isFeatured)
                        <span class="billing-plan-badge is-popular">Paling populer</span>
                    @endif

                    <p class="billing-plan-name">{{ $plan->name }}</p>
                    <p class="billing-plan-price">
                        <small>Rp </small>{{ $monthlyFormatted }}<small>/bln</small>
                    </p>
                    @if ($plan->price_yearly_idr)
                        @if ($plan->hasYearlyDiscount())
                            <p class="billing-plan-save">
                                Hemat {{ format_rupiah($plan->yearlySavings()) }}
                                <span>(-{{ $plan->yearlyDiscountPercent() }}%)</span>
                            </p>
                        @endif
                        <p class="billing-plan-period">
                            <span class="billing-plan-period-full">{{ format_rupiah($plan->fullYearMonthlyTotal()) }}</span>
                            {{ format_rupiah($plan->price_yearly_idr) }}/tahun
                            · setara {{ format_rupiah($plan->yearlyEquivalentMonthly()) }}/bln
                        </p>
                    @endif

                    <div class="billing-plan-divider"></div>

                    <ul class="billing-plan-features">
                        @foreach ($featureLines as $line)
                            <li>
                                <span class="billing-plan-check" aria-hidden="true">
                                    <svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg>
                                </span>
                                <span>{{ $line }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="billing-plan-cta">
                        @if ($isCurrentActive)
                            <span class="vx-btn vx-btn-soft pointer-events-none">Paket saat ini</span>
                        @elseif ($hasPending)
                            <span class="vx-btn vx-btn-ghost pointer-events-none opacity-60">Menunggu verifikasi</span>
                        @else
                            <button
                                type="button"
                                @class(['vx-btn', 'vx-btn-primary' => $isFeatured, 'vx-btn-ghost' => ! $isFeatured])
                                x-on:click="openModal(@js($planPayload))"
                            >
                                Langganan Sekarang
                            </button>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <p class="billing-footnote">
            <strong>Setelah upload bukti,</strong> tim admin akan verifikasi pembayaran dan mengaktifkan langganan dalam 1×24 jam kerja.
        </p>

        @include('billing._subscribe-modal')
    </div>
@endsection
