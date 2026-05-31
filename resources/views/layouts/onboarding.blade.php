<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Setup Toko — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @include('partials.pwa-head')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .onboarding-shell * { box-sizing: border-box; }
        .onboarding-shell {
            --ob-red: #E01010;
            --ob-red-dark: #C40D0D;
            --ob-black: #111111;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100dvh;
            position: relative;
            overflow-x: hidden;
            padding:
                max(1.25rem, env(safe-area-inset-top))
                max(1rem, env(safe-area-inset-right))
                max(1.25rem, env(safe-area-inset-bottom))
                max(1rem, env(safe-area-inset-left));
            background:
                radial-gradient(ellipse 80% 60% at 15% 20%, rgba(255, 255, 255, 0.12), transparent 55%),
                radial-gradient(ellipse 70% 55% at 85% 75%, rgba(0, 0, 0, 0.18), transparent 50%),
                linear-gradient(145deg, #f01818 0%, #e01010 45%, #c40d0d 100%);
        }
        .onboarding-bg-decor {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .onboarding-bg-circle {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.06);
        }
        .onboarding-bg-circle--1 {
            width: min(240px, 36vw);
            height: min(240px, 36vw);
            top: -6%;
            right: 6%;
        }
        .onboarding-bg-circle--2 {
            width: min(96px, 14vw);
            height: min(96px, 14vw);
            bottom: 14%;
            left: 5%;
            background: rgba(255, 255, 255, 0.1);
        }
        .onboarding-wrap {
            position: relative;
            z-index: 1;
            max-width: 560px;
            margin: 0 auto;
        }
        .onboarding-header {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .onboarding-logo-row {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 0.75rem;
        }
        .onboarding-logo-row img {
            height: 34px;
            width: auto;
        }
        .onboarding-logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.03em;
        }
        .onboarding-logo-text em {
            color: #fff;
            font-style: normal;
            opacity: 0.92;
        }
        .onboarding-header h1 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }
        .onboarding-header p {
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.82);
            margin: 0.35rem 0 0;
        }
        .onboarding-steps {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 1rem;
        }
        .onboarding-steps span {
            height: 5px;
            width: 48px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.25);
        }
        .onboarding-steps span.is-active {
            background: #fff;
        }
        .onboarding-card {
            background: #fff;
            border-radius: 20px;
            padding: clamp(1.5rem, 3vw, 2rem);
            box-shadow: 0 24px 64px -12px rgba(0, 0, 0, 0.28);
        }
        .ob-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--ob-black);
            margin: 0 0 0.25rem;
        }
        .ob-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0 0 1.25rem;
            line-height: 1.45;
        }
        .ob-field { margin-bottom: 14px; }
        .ob-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .ob-label-muted { color: #9ca3af; font-weight: 500; }
        .ob-input,
        .ob-textarea,
        .ob-file {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            color: #111827;
            background: #f9fafb;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .ob-textarea { resize: vertical; min-height: 72px; }
        .ob-input:hover,
        .ob-textarea:hover { border-color: #d1d5db; }
        .ob-input:focus,
        .ob-textarea:focus {
            border-color: var(--ob-red);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(224, 16, 16, 0.1);
        }
        .ob-error {
            font-size: 13px;
            color: #b91c1c;
            margin-top: 6px;
        }
        .ob-btn {
            width: 100%;
            padding: 14px 20px;
            background: var(--ob-red);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }
        .ob-btn:hover {
            background: var(--ob-red-dark);
            transform: translateY(-1px);
        }
        .ob-btn--success {
            background: #059669;
        }
        .ob-btn--success:hover {
            background: #047857;
        }
        .ob-check-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        .ob-check-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            background: #f9fafb;
            transition: border-color 0.2s, background 0.2s;
        }
        .ob-check-card:has(input:checked) {
            border-color: var(--ob-red);
            background: #fff5f5;
        }
        .ob-check-card input {
            accent-color: var(--ob-red);
        }
        .ob-check-card span {
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
        }
        .ob-product-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
            background: #fafafa;
        }
        .ob-product-card .ob-label {
            font-size: 12px;
            color: #6b7280;
        }
        .ob-credentials {
            margin-bottom: 1.25rem;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #fcd34d;
            background: #fffbeb;
            font-size: 13px;
            color: #78350f;
        }
        .ob-credentials h3 {
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 0.35rem;
            color: #92400e;
        }
        .ob-credentials p {
            margin: 0 0 0.75rem;
            line-height: 1.45;
            color: #a16207;
        }
        .ob-credentials dl { margin: 0; }
        .ob-credentials dt {
            font-weight: 600;
            color: #92400e;
            margin-top: 0.5rem;
        }
        .ob-credentials dt:first-child { margin-top: 0; }
        .ob-credentials dd {
            margin: 0.15rem 0 0;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 12px;
            word-break: break-all;
            color: #78350f;
        }
        .ob-complete-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
        .ob-complete-note {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 1rem;
            text-align: center;
        }
        .ob-alert-success {
            margin-bottom: 1rem;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        @media (max-width: 480px) {
            .ob-check-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="onboarding-shell">
    <div class="onboarding-bg-decor" aria-hidden="true">
        <span class="onboarding-bg-circle onboarding-bg-circle--1"></span>
        <span class="onboarding-bg-circle onboarding-bg-circle--2"></span>
    </div>

    <div class="onboarding-wrap">
        <header class="onboarding-header">
            <div class="onboarding-logo-row">
                <x-xiway-logo />
                <span class="onboarding-logo-text">xiway<em>pos</em></span>
            </div>
            <h1>Setup Xiway POS</h1>
            <p>Langkah {{ $step ?? 1 }} dari 4</p>
            <div class="onboarding-steps" aria-hidden="true">
                @for ($i = 1; $i <= 4; $i++)
                    <span @class(['is-active' => ($step ?? 1) >= $i])></span>
                @endfor
            </div>
        </header>

        @if (session('success'))
            <div class="ob-alert-success">{{ session('success') }}</div>
        @endif

        <div class="onboarding-card">
            @include('partials.onboarding-credentials')
            @yield('content')
        </div>
    </div>

    @include('components.help-fab')
    @include('partials.toast')
</body>
</html>
