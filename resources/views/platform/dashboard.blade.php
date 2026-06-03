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
        <p>Ringkasan tenant, langganan, kunjungan website, funnel konversi, dan verifikasi pembayaran.</p>
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

    <div class="vx-card vx-chart-card mt-6">
        <div class="vx-card-head">
            <div>
                <h2>Kunjungan website</h2>
                <p>
                    7 hari terakhir ·
                    {{ number_format($trafficChart['period_views'], 0, ',', '.') }} kunjungan ·
                    {{ number_format($trafficChart['period_uniques'], 0, ',', '.') }} pengunjung unik
                </p>
            </div>
            <div class="text-right text-sm">
                <p class="font-semibold text-[var(--vx-text)]">Hari ini</p>
                <p class="text-[var(--vx-text-soft)]">
                    {{ number_format($trafficChart['today_views'], 0, ',', '.') }} kunjungan ·
                    {{ number_format($trafficChart['today_uniques'], 0, ',', '.') }} unik
                </p>
            </div>
        </div>
        <div class="vx-card-pad pt-0">
            @if ($trafficChart['has_data'])
                <div class="vx-chart-wrap">
                    <canvas id="platformTrafficChart" aria-label="Grafik kunjungan website 7 hari terakhir" role="img"></canvas>
                </div>
                <div class="vx-chart-legend">
                    <span class="vx-chart-legend-item">
                        <span class="vx-chart-legend-dot is-primary"></span>
                        Kunjungan halaman
                    </span>
                    <span class="vx-chart-legend-item">
                        <span class="vx-chart-legend-dot is-muted"></span>
                        Pengunjung unik
                    </span>
                </div>
            @else
                <div class="vx-chart-empty">
                    <p>Belum ada data kunjungan.</p>
                    <span>Data terkumpul dari halaman landing, login, dan register.</span>
                </div>
            @endif
        </div>
    </div>

    <div class="vx-card vx-card-pad mt-6">
        <div class="vx-card-head mb-4">
            <div>
                <h2>Funnel konversi</h2>
                <p>{{ $funnelSummary['days'] }} hari terakhir — dari kunjungan landing sampai transaksi pertama</p>
            </div>
        </div>
        @if ($funnelSummary['has_data'])
            @php
                $maxFunnel = max(1, collect($funnelSummary['steps'])->max('count'));
            @endphp
            <div class="space-y-4">
                @foreach ($funnelSummary['steps'] as $index => $step)
                    @php
                        $width = max(8, (int) round(($step['count'] / $maxFunnel) * 100));
                    @endphp
                    <div>
                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-[var(--vx-primary-soft)] text-xs font-bold text-[var(--vx-primary-text)]">{{ $index + 1 }}</span>
                                <span class="font-semibold text-[var(--vx-text)]">{{ $step['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-[var(--vx-text-soft)]">
                                <span class="font-bold text-[var(--vx-text)]">{{ number_format($step['count'], 0, ',', '.') }}</span>
                                @if ($step['rate_from_previous'] !== null)
                                    <span class="vx-badge vx-badge-slate">{{ $step['rate_from_previous'] }}% dari langkah sebelumnya</span>
                                @endif
                                @if ($index > 0 && $step['rate_from_landing'] !== null)
                                    <span class="vx-badge vx-badge-primary">{{ $step['rate_from_landing'] }}% dari landing</span>
                                @endif
                            </div>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-[var(--vx-border-soft)]">
                            <div class="h-full rounded-full bg-[var(--vx-primary)] transition-all" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="mt-4 text-xs text-[var(--vx-text-mute)]">
                Data funnel mulai tercatat setelah fitur ini aktif. Kunjungan landing = pengunjung unik homepage; daftar berhasil = tenant trial baru; transaksi pertama = tenant yang sudah punya penjualan lunas pertama.
            </p>
        @else
            <p class="text-sm text-[var(--vx-text-mute)]">Belum ada data funnel. Kunjungi landing dan halaman daftar untuk mulai mengumpulkan data.</p>
        @endif
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="vx-card vx-card-pad">
            <div class="vx-card-head mb-4">
                <div>
                    <h2>Tenant terbaru</h2>
                    <p>Daftar bisnis yang baru mendaftar</p>
                </div>
                <a href="{{ route('platform.tenants.index') }}" class="text-sm font-semibold text-[var(--vx-primary)] hover:underline">Lihat semua</a>
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
                                <a href="{{ route('platform.tenants.show', $tenant) }}" class="mt-1 block text-xs font-semibold text-[var(--vx-primary)] hover:underline">Detail</a>
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
                <a href="{{ route('platform.payment-requests.index') }}" class="text-sm font-semibold text-[var(--vx-primary)] hover:underline">Lihat semua</a>
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

@if ($trafficChart['has_data'])
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" crossorigin="anonymous"></script>
        <script>
            (function () {
                const canvas = document.getElementById('platformTrafficChart');
                if (!canvas || typeof Chart === 'undefined') {
                    return;
                }

                const labels = @json($trafficChart['labels']);
                const pageViews = @json($trafficChart['page_views']);
                const uniqueVisitors = @json($trafficChart['unique_visitors']);

                const ctx = canvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 320);
                gradient.addColorStop(0, 'rgba(224, 16, 16, 0.22)');
                gradient.addColorStop(1, 'rgba(224, 16, 16, 0.02)');

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [
                            {
                                type: 'line',
                                label: 'Kunjungan',
                                data: pageViews,
                                yAxisID: 'yViews',
                                borderColor: '#E01010',
                                backgroundColor: gradient,
                                borderWidth: 2.5,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#E01010',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                tension: 0.35,
                                fill: true,
                                order: 1,
                            },
                            {
                                type: 'bar',
                                label: 'Pengunjung unik',
                                data: uniqueVisitors,
                                yAxisID: 'yUniques',
                                backgroundColor: 'rgba(17, 17, 17, 0.12)',
                                hoverBackgroundColor: 'rgba(17, 17, 17, 0.22)',
                                borderRadius: 6,
                                borderSkipped: false,
                                maxBarThickness: 28,
                                order: 2,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#111111',
                                titleColor: '#f8fafc',
                                bodyColor: '#e2e8f0',
                                padding: 12,
                                cornerRadius: 10,
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748b', font: { size: 11 } },
                            },
                            yViews: {
                                type: 'linear',
                                position: 'left',
                                beginAtZero: true,
                                grid: { color: 'rgba(148, 163, 184, 0.2)' },
                                ticks: {
                                    color: '#64748b',
                                    precision: 0,
                                },
                            },
                            yUniques: {
                                type: 'linear',
                                position: 'right',
                                beginAtZero: true,
                                grid: { drawOnChartArea: false },
                                ticks: {
                                    color: '#94a3b8',
                                    precision: 0,
                                },
                            },
                        },
                    },
                });
            })();
        </script>
    @endpush
@endif
