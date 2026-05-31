@props(['white' => false])

@php
    $logoName = $white ? 'logo-white.png' : 'logo.png';
    $logoFile = public_path('images/logo/' . $logoName);
    $logoSrc = asset('images/logo/' . $logoName);

    if (is_file($logoFile)) {
        $logoSrc .= '?v=' . filemtime($logoFile);
    }
@endphp
<img
    src="{{ $logoSrc }}"
    alt="{{ config('app.name', 'Xiway POS') }}"
    {{ $attributes->merge(['class' => '']) }}
    decoding="async"
    loading="eager"
/>
