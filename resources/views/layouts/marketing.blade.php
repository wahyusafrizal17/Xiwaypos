<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('xiway.seo.default_title', config('app.name').' — Aplikasi POS Kasir'))</title>
    <meta name="description" content="@yield('meta_description', config('xiway.seo.default_description'))">
    <meta property="og:title" content="@yield('og_title', config('xiway.seo.default_title'))">
    <meta property="og:description" content="@yield('og_description', config('xiway.seo.default_description'))">
    <meta property="og:type" content="website">
    @include('partials.marketing-seo')
    @include('partials.marketing-styles')
    @stack('head')
</head>
<body class="marketing-page">
    @yield('body')
    @stack('scripts')
</body>
</html>
