@php
    use App\Support\MarketingSeo;

    $seoTitle = trim($__env->yieldContent('title', config('xiway.seo.default_title', config('app.name').' — Aplikasi POS Kasir')));
    $seoDescription = trim($__env->yieldContent('meta_description', config('xiway.seo.default_description', '')));
    $seoCanonical = trim($__env->yieldContent('canonical', MarketingSeo::canonical('/')));
    $seoRobots = trim($__env->yieldContent('meta_robots', 'index, follow'));
    $seoKeywords = trim($__env->yieldContent('meta_keywords', MarketingSeo::keywordsString()));
    $ogTitle = trim($__env->yieldContent('og_title', $seoTitle));
    $ogDescription = trim($__env->yieldContent('og_description', $seoDescription));
    $ogImage = MarketingSeo::ogImage();
    $siteName = config('xiway.seo.site_name', 'Xiway POS');
@endphp

<link rel="canonical" href="{{ $seoCanonical }}">
<meta name="robots" content="{{ $seoRobots }}">
@if ($seoKeywords !== '')
    <meta name="keywords" content="{{ $seoKeywords }}">
@endif
<meta name="author" content="{{ $siteName }}">
<meta name="theme-color" content="#E01010">

<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $siteName }} — aplikasi POS kasir untuk cafe dan restoran">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

@if ($verification = config('xiway.seo.google_site_verification'))
    <meta name="google-site-verification" content="{{ $verification }}">
@endif

@stack('structured_data')
