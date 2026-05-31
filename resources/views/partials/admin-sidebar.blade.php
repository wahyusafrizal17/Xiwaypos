@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
    $isPlatformAdmin = $user->isPlatformAdmin();
    $canFeature = $planHasFeature ?? fn (): bool => true;
@endphp

<aside
    class="vx-sidebar flex shrink-0 flex-col self-stretch lg:static lg:translate-x-0"
    :class="{ 'is-open': sidebarOpen }"
>
    <div class="vx-sidebar-brand">
        <x-xiway-brand white />
    </div>

    <div class="min-h-0 flex-1 overflow-y-auto">
        @if ($isAdmin)
            <p class="vx-sidebar-section">Ringkasan</p>
            <nav class="vx-sidebar-nav">
                <a href="{{ route('dashboard') }}" class="vx-sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 3l8.25 6.75v9a1.5 1.5 0 0 1-1.5 1.5h-4.5v-6h-4.5v6h-4.5a1.5 1.5 0 0 1-1.5-1.5v-9Z"/></svg>
                    Dashboard
                </a>
            </nav>
        @endif

        <p class="vx-sidebar-section">Operasional</p>
        <nav class="vx-sidebar-nav">
            @if ($canFeature('cashier'))
                <a href="{{ route('cashier.index') }}" class="vx-sidebar-link {{ request()->routeIs('cashier.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.5l1.05 4.2M6 16.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm12 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm-12-3h12.36a1.5 1.5 0 0 0 1.47-1.2L21.75 6H4.8"/></svg>
                    Kasir
                </a>
            @endif
            @if ($isAdmin)
                @if ($canFeature('products'))
                    <a href="{{ route('admin.products.index') }}" class="vx-sidebar-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-8.25 4.5-8.25-4.5M12 12v9.75M20.25 7.5v9l-8.25 4.5L3.75 16.5v-9L12 3l8.25 4.5Z"/></svg>
                        Produk
                    </a>
                @endif
                @if ($canFeature('categories'))
                    <a href="{{ route('admin.categories.index') }}" class="vx-sidebar-link {{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5A1.5 1.5 0 0 1 4.5 6h3.879c.265 0 .52.105.707.293L10.5 7.5h9A1.5 1.5 0 0 1 21 9v9a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18V7.5Z"/></svg>
                        Kategori
                    </a>
                @endif
                @if ($canFeature('order_addons'))
                    <a href="{{ route('admin.order-addons.index') }}" class="vx-sidebar-link {{ request()->routeIs('admin.order-addons.*') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15M6 12h.008v.008H6V12Zm3 0h.008v.008H9V12Zm3 0h.008v.008H12V12Zm3 0h.008v.008H15V12Z"/></svg>
                        Opsi Ekstra
                    </a>
                @endif
            @endif
        </nav>

        @if ($isAdmin && ($canFeature('reports_basic') || $canFeature('expenses') || $canFeature('assets')))
            <p class="vx-sidebar-section">Keuangan</p>
            <nav class="vx-sidebar-nav">
                @if ($canFeature('reports_basic'))
                    <a href="{{ route('admin.reports.index') }}" class="vx-sidebar-link {{ request()->routeIs('admin.reports.index') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5 9 7.5l3.75 3.75L21 4.5M21 4.5h-4.5M21 4.5V9m0 10.5H3"/></svg>
                        Laporan penjualan
                    </a>
                @endif
                @if ($canFeature('expenses'))
                    <a href="{{ route('admin.reports.profit-loss') }}" class="vx-sidebar-link {{ request()->routeIs('admin.reports.profit-loss') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.5 4.5L21.75 7.5M21.75 7.5H16.5M21.75 7.5V12.75"/></svg>
                        Laporan laba rugi
                    </a>
                    <a href="{{ route('admin.expenses.index') }}" class="vx-sidebar-link {{ request()->routeIs('admin.expenses.*') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3M3.75 6.75h16.5a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5v-9a1.5 1.5 0 0 1 1.5-1.5Z"/></svg>
                        Pengeluaran
                    </a>
                @endif
                @if ($canFeature('assets'))
                    <a href="{{ route('admin.assets.index') }}" class="vx-sidebar-link {{ request()->routeIs('admin.assets.*') ? 'is-active' : '' }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                        Aset & Peralatan
                    </a>
                @endif
            </nav>
        @endif

        @if ($isAdmin)
            <p class="vx-sidebar-section">Manajemen</p>
            <nav class="vx-sidebar-nav">
                <a href="{{ route('admin.users.index') }}" class="vx-sidebar-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0v.75H4.5v-.75Z"/></svg>
                    Pengguna
                </a>
            </nav>
        @endif

        @if ($isPlatformAdmin)
            <p class="vx-sidebar-section">Platform</p>
            <nav class="vx-sidebar-nav">
                <a href="{{ route('platform.tenants.index') }}" class="vx-sidebar-link {{ request()->routeIs('platform.tenants.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                    Kelola Tenant
                </a>
                <a href="{{ route('platform.payment-requests.index') }}" class="vx-sidebar-link {{ request()->routeIs('platform.payment-requests.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    Verifikasi Langganan
                </a>
            </nav>
        @endif

        <p class="vx-sidebar-section">Akun</p>
        <nav class="vx-sidebar-nav">
            @if ($isAdmin)
                <a href="{{ route('billing.index') }}" class="vx-sidebar-link {{ request()->routeIs('billing.*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3M3.75 6.75h16.5a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5v-9a1.5 1.5 0 0 1 1.5-1.5Z"/></svg>
                    Langganan
                </a>
            @endif
            <a href="{{ route('profile.edit') }}" class="vx-sidebar-link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.094c.55 0 1.02.398 1.11.94l.213 1.28a7.46 7.46 0 0 1 1.624.948l1.214-.46a1.125 1.125 0 0 1 1.37.488l.547.948a1.125 1.125 0 0 1-.26 1.43l-1.003.827a7.541 7.541 0 0 1 0 1.875l1.003.827a1.125 1.125 0 0 1 .26 1.43l-.547.948a1.125 1.125 0 0 1-1.37.49l-1.214-.461a7.461 7.461 0 0 1-1.624.948l-.213 1.281c-.09.543-.56.94-1.11.94h-1.094c-.55 0-1.02-.397-1.11-.94l-.213-1.281a7.46 7.46 0 0 1-1.624-.948l-1.214.46a1.125 1.125 0 0 1-1.37-.488l-.547-.948a1.125 1.125 0 0 1 .26-1.43l1.003-.827a7.541 7.541 0 0 1 0-1.875l-1.003-.827a1.125 1.125 0 0 1-.26-1.43l.547-.948a1.125 1.125 0 0 1 1.37-.49l1.214.461a7.461 7.461 0 0 1 1.624-.948l.213-1.28ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z"/></svg>
                Profil
            </a>
        </nav>
    </div>
</aside>
