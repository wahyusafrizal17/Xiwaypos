@extends('layouts.admin')

@section('title', 'Dashboard Platform')

@section('breadcrumbs')
    <a href="{{ route('platform.dashboard') }}">Beranda</a>
    <span class="vx-sep">/</span>
    <span class="vx-current">Dashboard</span>
@endsection

@section('page_header')
    <div>
        <h1>Selamat datang, {{ auth()->user()->name }} 👋</h1>
        <p>Ringkasan tenant, langganan, dan verifikasi pembayaran.</p>
    </div>
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-primary">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Total tenant</p>
                <p class="vx-stat-value">{{ $totalTenants }}</p>
            </div>
        </div>

        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-info">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Sedang trial</p>
                <p class="vx-stat-value">{{ $trialingTenants }}</p>
            </div>
        </div>

        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-success">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Langganan aktif</p>
                <p class="vx-stat-value">{{ $activeTenants }}</p>
            </div>
        </div>

        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-warning">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3M3.75 6.75h16.5a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5v-9a1.5 1.5 0 0 1 1.5-1.5Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Menunggu verifikasi</p>
                <p class="vx-stat-value">{{ $pendingPayments }}</p>
            </div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="vx-card vx-card-pad">
            <div class="vx-card-head mb-4">
                <div>
                    <h2>Tenant terbaru</h2>
                    <p>Daftar bisnis yang baru mendaftar</p>
                </div>
                <a href="{{ route('platform.tenants.index') }}" class="text-sm font-semibold text-indigo-600 hover:underline">Lihat semua</a>
            </div>
            @if ($recentTenants->isEmpty())
                <p class="text-sm text-slate-500">Belum ada tenant terdaftar.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($recentTenants as $tenant)
                        <div class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="font-semibold text-[var(--vx-text)] truncate">{{ $tenant->displayName() }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $tenant->owner?->email ?? '—' }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="vx-badge is-neutral text-xs">{{ strtoupper($tenant->subscription?->status ?? '—') }}</span>
                                <a href="{{ route('platform.tenants.show', $tenant) }}" class="mt-1 block text-xs font-semibold text-indigo-600 hover:underline">Detail</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="vx-card vx-card-pad">
            <div class="vx-card-head mb-4">
                <div>
                    <h2>Pembayaran perlu verifikasi</h2>
                    <p>Pengajuan langganan dari client</p>
                </div>
                <a href="{{ route('platform.payment-requests.index') }}" class="text-sm font-semibold text-indigo-600 hover:underline">Lihat semua</a>
            </div>
            @if ($recentPaymentRequests->isEmpty())
                <p class="text-sm text-slate-500">Tidak ada pengajuan menunggu verifikasi.</p>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($recentPaymentRequests as $request)
                        <div class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="font-semibold text-[var(--vx-text)] truncate">{{ $request->tenant?->displayName() }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $request->plan?->name }} · {{ $request->billingCycleLabel() }}
                                </p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-sm font-semibold">{{ format_rupiah($request->amount_idr) }}</p>
                                <p class="text-xs text-slate-500">{{ $request->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
