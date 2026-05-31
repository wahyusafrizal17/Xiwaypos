@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page_header')
    <div>
        <h1>Selamat datang, {{ auth()->user()->name }} 👋</h1>
        <p>Ringkasan singkat penjualan dan performa toko hari ini.</p>
    </div>
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-success">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .896-3 2s1.343 2 3 2 3 .896 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Penjualan hari ini</p>
                <p class="vx-stat-value">{{ format_rupiah($todayTotal) }}</p>
            </div>
        </div>

        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-primary">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M3.75 6.75A1.5 1.5 0 0 1 5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v10.5a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V6.75ZM7.5 11.25h.008v.008H7.5v-.008Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Transaksi hari ini</p>
                <p class="vx-stat-value">{{ $todayCount }}</p>
            </div>
        </div>

        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-info">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5 9 7.5l3.75 3.75L21 4.5M21 4.5h-4.5M21 4.5V9m0 10.5H3"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Pendapatan bulan ini</p>
                <p class="vx-stat-value">{{ format_rupiah($monthlyTotal) }}</p>
            </div>
        </div>
    </div>

    <div class="vx-card vx-chart-card">
        <div class="vx-card-head">
            <div>
                <h2>Tren penjualan</h2>
                <p>7 hari terakhir · {{ format_rupiah($salesChart['period_total']) }} · {{ number_format($salesChart['period_count'], 0, ',', '.') }} transaksi</p>
            </div>
            @if (($planHasFeature ?? fn () => true)('reports_basic'))
                <a href="{{ route('admin.reports.index') }}" class="vx-btn vx-btn-ghost vx-btn-sm">
                    Lihat laporan
                </a>
            @endif
        </div>

        @if ($salesChart['has_data'])
            <div class="vx-chart-wrap px-5 pb-5">
                <canvas id="dashboardSalesChart" aria-label="Grafik penjualan 7 hari terakhir" role="img"></canvas>
            </div>
            <div class="vx-chart-legend px-5 pb-5">
                <span class="vx-chart-legend-item">
                    <span class="vx-chart-legend-dot is-primary"></span>
                    Penjualan (Rp)
                </span>
                <span class="vx-chart-legend-item">
                    <span class="vx-chart-legend-dot is-muted"></span>
                    Jumlah transaksi
                </span>
            </div>
        @else
            <div class="vx-chart-empty px-5 pb-5">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5 9 7.5l3.75 3.75L21 4.5M21 4.5h-4.5M21 4.5V9m0 10.5H3"/></svg>
                <p>Belum ada transaksi dalam 7 hari terakhir.<br>Data grafik akan muncul setelah penjualan pertama.</p>
            </div>
        @endif
    </div>

    <div class="mt-6">
        <div class="vx-card">
            <div class="vx-card-head">
                <div>
                    <h2>Produk terlaris</h2>
                    <p>Berdasarkan jumlah unit terjual sepanjang waktu.</p>
                </div>
                @if (($planHasFeature ?? fn () => true)('products'))
                    <a href="{{ route('admin.products.index') }}" class="vx-btn vx-btn-ghost vx-btn-sm">
                        Lihat produk
                    </a>
                @endif
            </div>
            <ul class="divide-y divide-[var(--vx-border-soft)]">
                @forelse ($topProducts as $row)
                    <li class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="vx-thumb-placeholder" aria-hidden="true">
                                {{ \Illuminate\Support\Str::of($row->product->nama_produk ?? '—')->substr(0, 1)->upper() }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900">
                                    {{ $row->product->nama_produk ?? '—' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $row->product->category->nama_kategori ?? '—' }}
                                </p>
                            </div>
                        </div>
                        <span class="vx-badge vx-badge-primary">{{ $row->qty_sold }} terjual</span>
                    </li>
                @empty
                    <li class="px-5 py-12 text-center text-sm text-slate-500">Belum ada data penjualan.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection

@if ($salesChart['has_data'])
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" crossorigin="anonymous"></script>
        <script>
            (function () {
                const canvas = document.getElementById('dashboardSalesChart');
                if (!canvas || typeof Chart === 'undefined') {
                    return;
                }

                const labels = @json($salesChart['labels']);
                const revenue = @json($salesChart['revenue']);
                const transactions = @json($salesChart['transactions']);

                const fmtRp = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

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
                                label: 'Penjualan',
                                data: revenue,
                                yAxisID: 'yRevenue',
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
                                label: 'Transaksi',
                                data: transactions,
                                yAxisID: 'yCount',
                                backgroundColor: 'rgba(148, 163, 184, 0.35)',
                                hoverBackgroundColor: 'rgba(148, 163, 184, 0.55)',
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
                                displayColors: true,
                                callbacks: {
                                    label(context) {
                                        if (context.dataset.yAxisID === 'yRevenue') {
                                            return ' Penjualan: ' + fmtRp(context.parsed.y);
                                        }

                                        return ' Transaksi: ' + context.parsed.y;
                                    },
                                },
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' },
                                    maxRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 7,
                                },
                            },
                            yRevenue: {
                                type: 'linear',
                                position: 'left',
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.15)',
                                    drawTicks: false,
                                },
                                border: { display: false },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { family: 'Plus Jakarta Sans', size: 11 },
                                    maxTicksLimit: 5,
                                    callback(value) {
                                        if (value >= 1000000) {
                                            return (value / 1000000).toFixed(1) + ' jt';
                                        }
                                        if (value >= 1000) {
                                            return (value / 1000).toFixed(0) + ' rb';
                                        }

                                        return value;
                                    },
                                },
                            },
                            yCount: {
                                type: 'linear',
                                position: 'right',
                                beginAtZero: true,
                                grid: { display: false },
                                border: { display: false },
                                ticks: {
                                    color: '#cbd5e1',
                                    font: { family: 'Plus Jakarta Sans', size: 11 },
                                    precision: 0,
                                    maxTicksLimit: 5,
                                },
                            },
                        },
                    },
                });
            })();
        </script>
    @endpush
@endif
