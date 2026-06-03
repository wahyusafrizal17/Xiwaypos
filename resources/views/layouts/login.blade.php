<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Masuk') — {{ config('app.name', 'Xiway POS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @include('partials.pwa-head')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @php
            $loginIllustration = asset('images/login.png');
            $loginIllustrationPath = public_path('images/login.png');
            if (is_file($loginIllustrationPath)) {
                $loginIllustration .= '?v=' . filemtime($loginIllustrationPath);
            }
        @endphp
        <style>
            .login-shell * { box-sizing: border-box; }
            .login-shell {
                --login-red: #E01010;
                --login-red-dark: #C40D0D;
                --login-black: #111111;
                font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
                -webkit-font-smoothing: antialiased;
                min-height: 100dvh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow-x: hidden;
                overflow-y: auto;
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

            .login-bg-decor {
                position: absolute;
                inset: 0;
                pointer-events: none;
                overflow: hidden;
            }
            .login-bg-decor::before {
                content: '';
                position: absolute;
                inset: 0;
                background: repeating-linear-gradient(
                    -42deg,
                    transparent,
                    transparent 72px,
                    rgba(255, 255, 255, 0.04) 72px,
                    rgba(255, 255, 255, 0.04) 73px
                );
            }
            .login-bg-circle {
                position: absolute;
                border-radius: 50%;
                border: 1px solid rgba(255, 255, 255, 0.14);
                background: rgba(255, 255, 255, 0.06);
            }
            .login-bg-circle--1 {
                width: min(280px, 40vw);
                height: min(280px, 40vw);
                top: -8%;
                right: 8%;
            }
            .login-bg-circle--2 {
                width: min(120px, 18vw);
                height: min(120px, 18vw);
                bottom: 18%;
                left: 6%;
                background: rgba(255, 255, 255, 0.1);
            }
            .login-bg-circle--3 {
                width: min(64px, 10vw);
                height: min(64px, 10vw);
                top: 22%;
                left: 12%;
                background: rgba(255, 255, 255, 0.08);
            }
            .login-bg-wave {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 180px;
                color: rgba(255, 255, 255, 0.1);
            }
            .login-bg-wave svg {
                width: 100%;
                height: 100%;
                display: block;
            }

            .login-card {
                position: relative;
                z-index: 1;
                width: min(100%, 960px);
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                background: #fff;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 24px 64px -12px rgba(0, 0, 0, 0.28);
                margin-block: auto;
            }
            .login-card--register {
                width: min(100%, 1020px);
            }

            .login-col-visual {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: clamp(1.5rem, 3vw, 2.5rem);
                background: linear-gradient(160deg, #fafafa 0%, #f3f4f6 100%);
                border-right: 1px solid #f0f0f0;
            }
            .login-col-visual img {
                width: 100%;
                max-width: 420px;
                height: auto;
                max-height: min(420px, 52vh);
                object-fit: contain;
                display: block;
            }

            .login-col-form {
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: clamp(2rem, 4vw, 2.75rem) clamp(1.75rem, 3.5vw, 2.5rem);
            }
            .login-card--register .login-col-form {
                padding: clamp(1.5rem, 3vw, 2rem) clamp(1.5rem, 3vw, 2rem);
            }

            .auth-heading {
                text-align: center;
                margin-bottom: 1.25rem;
            }
            .auth-heading h1 {
                font-size: 1.25rem;
                font-weight: 700;
                color: var(--login-black);
                letter-spacing: -0.02em;
            }
            .auth-heading p {
                margin-top: 0.4rem;
                font-size: 0.875rem;
                color: #6b7280;
                line-height: 1.45;
            }

            .login-logo-row {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                margin-bottom: 1rem;
            }

            .register-lead {
                text-align: center;
                font-size: 0.9rem;
                color: #4b5563;
                line-height: 1.5;
                margin: 0 0 1rem;
            }

            .register-trust {
                margin-bottom: 1.25rem;
                padding: 14px 16px;
                border-radius: 12px;
                background: #fafafa;
                border: 1px solid #f0f0f0;
            }
            .register-trust-title {
                margin: 0 0 10px;
                font-size: 0.8rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                color: var(--login-red);
                text-align: center;
            }
            .register-trust-list {
                margin: 0;
                padding: 0;
                list-style: none;
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                justify-content: center;
            }
            .register-trust-list li {
                font-size: 0.75rem;
                font-weight: 500;
                color: #374151;
                background: #fff;
                border: 1px solid #e5e7eb;
                padding: 6px 10px;
                border-radius: 999px;
            }
            .login-logo-row .login-logo-img {
                height: 36px;
                width: auto;
                object-fit: contain;
                flex-shrink: 0;
            }
            .login-logo-text {
                font-size: 26px;
                font-weight: 700;
                color: var(--login-black);
                letter-spacing: -0.03em;
                line-height: 1;
            }
            .login-logo-text em {
                color: var(--login-red);
                font-style: normal;
            }

            .login-alert {
                margin-bottom: 1rem;
                padding: 11px 14px;
                border-radius: 10px;
                font-size: 13px;
                font-weight: 500;
                background: #ecfdf5;
                color: #047857;
                border: 1px solid #a7f3d0;
            }

            .login-field {
                position: relative;
                margin-bottom: 14px;
            }
            .login-field input {
                width: 100%;
                padding: 14px 16px 14px 44px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                font-size: 15px;
                font-family: inherit;
                color: #111827;
                background: #f9fafb;
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            }
            .login-field--labeled input {
                padding: 12px 14px;
            }
            .login-field label.auth-label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 6px;
            }
            .login-card--register .login-field {
                margin-bottom: 12px;
            }
            .login-field input::placeholder { color: #9ca3af; }
            .login-field input:hover { border-color: #d1d5db; }
            .login-field input:focus {
                border-color: var(--login-red);
                background: #fff;
                box-shadow: 0 0 0 3px rgba(224, 16, 16, 0.1);
            }

            .login-field--password input {
                padding-right: 44px;
            }

            .login-fi-l {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                display: flex;
                pointer-events: none;
                transition: color 0.2s;
            }
            .login-field:focus-within .login-fi-l { color: var(--login-red); }

            .login-fi-r {
                position: absolute;
                right: 8px;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                border: none;
                border-radius: 8px;
                background: transparent;
                padding: 0;
                transition: color 0.2s, background 0.2s;
            }
            .login-fi-r:hover {
                color: var(--login-red);
                background: rgba(224, 16, 16, 0.06);
            }

            .login-field svg.field-svg {
                width: 18px;
                height: 18px;
                stroke: currentColor;
                fill: none;
                stroke-width: 2;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .login-remember {
                display: flex;
                align-items: flex-start;
                gap: 8px;
                margin: 6px 0 20px;
            }
            .login-remember input[type="checkbox"] {
                accent-color: var(--login-red);
                width: 16px;
                height: 16px;
                margin-top: 2px;
                cursor: pointer;
                flex-shrink: 0;
            }
            .login-remember label {
                font-size: 13px;
                color: #6b7280;
                cursor: pointer;
                font-weight: 500;
                line-height: 1.45;
            }

            .login-btn {
                width: 100%;
                padding: 14px 20px;
                background: var(--login-red);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 15px;
                font-weight: 700;
                font-family: inherit;
                cursor: pointer;
                transition: background 0.2s, transform 0.15s;
            }
            .login-btn:hover {
                background: var(--login-red-dark);
                transform: translateY(-1px);
            }
            .login-btn:active {
                transform: translateY(0);
            }
            .login-btn--auto {
                width: auto;
                flex-shrink: 0;
                white-space: nowrap;
            }

            .login-actions {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-top: 1rem;
            }
            .login-actions a {
                font-size: 13px;
                color: #6b7280;
                text-decoration: none;
                font-weight: 500;
            }
            .login-actions a:hover {
                color: var(--login-red);
            }

            .login-footer {
                text-align: center;
                font-size: 12px;
                color: #9ca3af;
                margin-top: 1.25rem;
                font-weight: 500;
            }

            .login-error {
                font-size: 13px;
                color: #b91c1c;
                margin-top: 6px;
                margin-bottom: 0;
                font-weight: 500;
            }

            @media (max-width: 820px) {
                .login-card,
                .login-card--register {
                    grid-template-columns: 1fr;
                    max-width: 440px;
                }

                .login-col-visual {
                    order: 2;
                    border-right: none;
                    border-top: 1px solid #f0f0f0;
                    padding: 1.25rem 1.5rem 1.5rem;
                }

                .login-col-visual img {
                    max-height: 200px;
                }

                .login-col-form {
                    order: 1;
                    padding: 1.75rem 1.5rem 1.5rem;
                }

                .login-actions {
                    flex-direction: column-reverse;
                    align-items: stretch;
                }
                .login-btn--auto {
                    width: 100%;
                }
            }

            @media (max-width: 480px) {
                .login-col-form {
                    padding: 1.5rem 1.25rem 1.25rem;
                }

                .login-logo-row {
                    margin-bottom: 1.5rem;
                }

                .login-logo-row .login-logo-img {
                    height: 32px;
                }

                .login-logo-text {
                    font-size: 23px;
                }
            }

            [x-cloak] { display: none !important; }
            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }
        </style>
    </head>
    <body class="login-shell">
        <div class="login-bg-decor" aria-hidden="true">
            <span class="login-bg-circle login-bg-circle--1"></span>
            <span class="login-bg-circle login-bg-circle--2"></span>
            <span class="login-bg-circle login-bg-circle--3"></span>
            <div class="login-bg-wave">
                <svg viewBox="0 0 1440 180" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill="currentColor" d="M0,96L48,101.3C96,107,192,117,288,122.7C384,128,480,128,576,117.3C672,107,768,85,864,85.3C960,85,1056,107,1152,112C1248,117,1344,107,1392,101.3L1440,96L1440,180L0,180Z"/>
                </svg>
            </div>
        </div>

        <div class="login-card @yield('card-class')">
            <div class="login-col-visual">
                <img
                    src="{{ $loginIllustration }}"
                    width="420"
                    height="236"
                    alt="Xiway POS — sistem kasir cafe dan restoran"
                    decoding="async"
                />
            </div>

            <div class="login-col-form">
                @yield('content')
            </div>
        </div>

        @include('partials.toast')
    </body>
</html>
