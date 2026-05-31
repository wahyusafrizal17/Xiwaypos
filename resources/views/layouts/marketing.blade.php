<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name').' — Sistem Kasir untuk Cafe & Restoran')</title>
    <meta name="description" content="@yield('meta_description', 'Xiway POS — sistem kasir online untuk cafe, coffee shop, restoran, dan UMKM makan & minum. Coba gratis 14 hari.')">
    <meta property="og:title" content="@yield('og_title', config('app.name').' — Sistem Kasir Cafe & Restoran')">
    <meta property="og:description" content="@yield('og_description', 'Kelola penjualan, menu, dan laporan keuangan dalam satu aplikasi. Setup cepat, langsung bisa dipakai.')">
    <meta property="og:type" content="website">
    @include('partials.marketing-styles')
    @stack('head')
</head>
<body class="marketing-page">
    @yield('body')
    @stack('scripts')
</body>
</html>
