@extends('layouts.marketing')

@section('title', 'Xiway POS — Sistem Kasir untuk Cafe & Restoran')

@section('body')
@php
    $registerUrl = \App\Support\AppUrl::register();
    $loginUrl = \App\Support\AppUrl::login();
    $waUrl = \App\Support\WhatsAppLink::supportUrl('Halo, saya mau tanya tentang Xiway POS.');

    $wahyuPhoto = asset('images/wahyu.png');
    $wahyuPath = public_path('images/wahyu.png');
    if (is_file($wahyuPath)) {
        $wahyuPhoto .= '?v=' . filemtime($wahyuPath);
    }

    $testimonials = [
        ['text' => 'Setup-nya cepat banget. Dalam 10 menit sudah bisa terima pesanan, dan laporan hariannya membantu saya pantau omzet setiap malam.', 'name' => 'Rina Kusuma', 'role' => 'Pemilik · Kopi Sore Cafe, Bandung', 'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&q=80'],
        ['text' => 'Fitur tagihan sementara sangat membantu restoran kami. Kasir bisa update pesanan tanpa salah hitung.', 'name' => 'Budi Santoso', 'role' => 'Manager · Warung Bahari, Surabaya', 'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&q=80'],
        ['text' => 'Saya pakai dari HP saja, tanpa mesin kasir besar. Cocok buat food truck. Pembayaran QRIS lancar, struk langsung keluar.', 'name' => 'Sari Dewi', 'role' => 'Pemilik · Sari Food Truck, Jakarta', 'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&q=80'],
        ['text' => 'Harganya masuk akal untuk fitur yang lengkap. Tim support juga responsif saat saya baru mulai.', 'name' => 'Dimas Pratama', 'role' => 'Pemilik · Kedai Kita, Yogyakarta', 'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&q=80'],
        ['text' => 'Admin bisa pantau dari rumah, kasir kerja di toko. Semua data sinkron otomatis tanpa repot.', 'name' => 'Maya Hendra', 'role' => 'Pemilik · Boba Queen, Medan', 'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=100&q=80'],
    ];

    $faqs = [
        ['q' => 'Apakah trial 14 hari benar-benar gratis?', 'a' => 'Ya. Anda bisa pakai semua fitur selama 14 hari tanpa kartu kredit. Setelah trial, pilih paket atau hubungi kami lewat WhatsApp.'],
        ['q' => 'Berapa lama setup tokonya?', 'a' => 'Biasanya kurang dari 10 menit. Setelah daftar, ada panduan langkah demi langkah untuk isi profil toko, kategori, dan produk pertama.'],
        ['q' => 'Bisa dipakai di HP atau tablet?', 'a' => 'Bisa. Xiway POS bisa dibuka lewat browser di HP, tablet, atau laptop. Di HP Android/iOS juga bisa disimpan seperti aplikasi.'],
        ['q' => 'Bagaimana cara berlangganan setelah trial?', 'a' => 'Hubungi kami lewat WhatsApp, transfer sesuai paket, lalu tim kami akan aktifkan langganan Anda dalam 1×24 jam.'],
        ['q' => 'Apakah data bisnis saya aman?', 'a' => 'Ya. Setiap bisnis punya data terpisah. Data toko Anda tidak tercampur dengan bisnis lain.'],
    ];

    $logoBrands = ['Kopi Kenangan', 'Kedai Kita', 'Warung Nusantara', 'Boba House', 'Restoran Bahari', 'Dapur Ibu', 'Cafe 88'];

    $marketingAsset = function (string $path): string {
        $url = asset($path);
        $full = public_path($path);

        if (is_file($full)) {
            $url .= '?v=' . filemtime($full);
        }

        return $url;
    };

    $heroSlides = [
        [
            'bg' => $marketingAsset('images/marketing/hero-slide-hardware.png'),
            'title' => 'Sistem kasir praktis',
            'highlight' => 'Cafe & Restoran',
            'desc' => 'Catat penjualan, kelola menu, dan pantau omzet dari satu tempat. Daftar dan mulai jualan dalam hitungan menit.',
            'cta_secondary_label' => 'Lihat Fitur',
            'cta_secondary_url' => '#fitur',
            'cta_secondary_external' => false,
        ],
        [
            'bg' => $marketingAsset('images/marketing/hero-slide-pos.png'),
            'badge' => 'Bisa dipakai di HP, tablet, atau laptop',
            'title' => 'Satu aplikasi untuk',
            'highlight' => 'Kelola Usaha F&B Anda',
            'desc' => 'Terima pembayaran QRIS, tunai, atau transfer. Kelola pesanan meja dan laporan harian — langsung siap dipakai di outlet Anda.',
            'cta_secondary_label' => 'Chat WhatsApp',
            'cta_secondary_url' => $waUrl,
            'cta_secondary_external' => true,
        ],
    ];
@endphp

<nav class="site-nav" id="siteNav">
    <div class="nav-bar">
        <a href="{{ route('marketing.home') }}" class="nav-logo">
            <x-xiway-logo />
            <span>xiway<em>pos</em></span>
        </a>
        <button
            type="button"
            class="nav-toggle"
            id="navToggle"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="navMenu"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    <div class="nav-menu" id="navMenu">
        <ul class="nav-links">
            <li><a href="#fitur">Fitur</a></li>
            <li><a href="#cara-kerja">Demo</a></li>
            <li><a href="#harga">Harga</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="#hubungi-kami">Hubungi Kami</a></li>
        </ul>
        <div class="nav-cta">
            <a href="{{ $loginUrl }}" class="btn-ghost">Masuk</a>
            <a href="{{ $registerUrl }}" class="btn-red">Coba Gratis →</a>
        </div>
    </div>
</nav>

<section class="hero hero-full" id="heroSection">
    <div class="hero-carousel" id="heroSlider">
        <div class="hero-carousel-viewport">
            <div class="hero-carousel-track" id="heroCarouselTrack">
                @foreach ($heroSlides as $index => $slide)
                    <div class="hero-carousel-slide" data-hero-slide="{{ $index }}">
                        <div class="hero-slide-bg" style="background-image: url('{{ $slide['bg'] }}')"></div>
                        <div class="hero-slide-overlay"></div>
                        <div class="hero-slide-inner">
                            <div class="hero-content">
                                @if (! empty($slide['badge']))
                                    <div class="hero-badge"><span class="hero-badge-dot"></span>{{ $slide['badge'] }}</div>
                                @endif
                                <h1>{{ $slide['title'] }}<br><span>{{ $slide['highlight'] }}</span></h1>
                                <p class="hero-desc">{{ $slide['desc'] }}</p>
                                <div class="hero-btns">
                                    <a href="{{ $registerUrl }}" class="btn-red-lg">Coba Gratis 14 Hari →</a>
                                    <a
                                        href="{{ $slide['cta_secondary_url'] }}"
                                        @if($slide['cta_secondary_external']) target="_blank" rel="noopener" @endif
                                        class="btn-outline-lg btn-outline-light"
                                    >{{ $slide['cta_secondary_label'] }}</a>
                                </div>
                                <div class="hero-stats">
                                    <div>
                                        <div class="hero-stat-num">2.000<span>+</span></div>
                                        <div class="hero-stat-label">Bisnis aktif</div>
                                    </div>
                                    <div class="hero-stat-sep"></div>
                                    <div>
                                        <div class="hero-stat-num">99<span>%</span></div>
                                        <div class="hero-stat-label">Layanan stabil</div>
                                    </div>
                                    <div class="hero-stat-sep"></div>
                                    <div>
                                        <div class="hero-stat-num">4.9<span>★</span></div>
                                        <div class="hero-stat-label">Rating pengguna</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="hero-carousel-nav">
            <button type="button" class="hero-nav-arrow hero-nav-arrow-light" id="heroPrev" aria-label="Slide sebelumnya">&#8592;</button>
            <div class="hero-carousel-dots" id="heroDots">
                @foreach ($heroSlides as $index => $slide)
                    <button
                        type="button"
                        @class(['hero-carousel-dot', 'hero-carousel-dot-light', 'active' => $index === 0])
                        data-hero-dot="{{ $index }}"
                        aria-label="Slide {{ $index + 1 }}"
                    ></button>
                @endforeach
            </div>
            <button type="button" class="hero-nav-arrow hero-nav-arrow-light" id="heroNext" aria-label="Slide berikutnya">&#8594;</button>
        </div>
    </div>
</section>

<div class="logo-strip">
    <span class="logo-strip-label">Dipercaya oleh</span>
    <div style="overflow:hidden;flex:1">
        <div class="logo-strip-track">
            @foreach (array_merge($logoBrands, $logoBrands) as $brand)
                <span class="logo-brand">{{ $brand }}</span>
            @endforeach
        </div>
    </div>
</div>

<section class="how-section" id="cara-kerja">
    <div class="how-inner">
        <div class="how-img-wrap">
            <img src="{{ $marketingAsset('images/demo.png') }}" alt="Demo Xiway POS" loading="lazy">
            <div class="how-img-overlay"></div>
            <div class="how-step-badge">Demo Gratis</div>
        </div>
        <div class="how-content">
            <div class="section-label">Demo</div>
            <h2 class="section-title">Butuh demo dulu?<br>Kami siap kapan saja</h2>
            <p class="how-demo-desc">Mau lihat langsung fitur kasir, laporan, dan cara setup toko? Tim kami siap bantu — online atau datang ke tempat Anda.</p>
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn-red-lg">Jadwalkan Demo →</a>
        </div>
    </div>
</section>

<section class="features" id="fitur">
    <div class="features-head">
        <div class="section-label">Fitur Utama</div>
        <h2 class="section-title">Yang Anda butuhkan, sudah ada</h2>
        <p class="section-sub">Dibuat khusus untuk cafe, coffee shop, dan restoran.</p>
    </div>
    <div class="features-grid">
        @foreach ([
            ['icon' => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>', 'title' => 'Kasir Praktis', 'desc' => 'Layar kasir rapi dan cepat. Bisa dine-in, takeaway, plus bayar QRIS, tunai, atau transfer sekaligus.'],
            ['icon' => '<path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 3H8a2 2 0 00-2 2v2h12V5a2 2 0 00-2-2z"/>', 'title' => 'Kelola Menu', 'desc' => 'Atur kategori, varian minuman, tambahan pesanan, dan foto produk dengan mudah.'],
            ['icon' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>', 'title' => 'Laporan Harian', 'desc' => 'Pantau penjualan, menu terlaris, dan ringkasan keuangan kapan pun dibutuhkan.'],
            ['icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>', 'title' => 'Banyak Pengguna', 'desc' => 'Admin dan kasir punya akses berbeda. Atur tim sesuai paket langganan Anda.'],
            ['icon' => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/>', 'title' => 'Tagihan Sementara', 'desc' => 'Simpan pesanan belum lunas, ubah kapan saja, baru bayar saat pelanggan selesai.'],
            ['icon' => '<path d="M18 10h-1.26A8 8 0 109 20h9a5 5 0 000-10z"/>', 'title' => 'Aman & Online', 'desc' => 'Data tersimpan aman dan terpisah per bisnis. Bisa diakses dari browser atau HP.'],
        ] as $index => $feature)
            <div class="feat-card marketing-reveal">
                <span class="feat-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="feat-icon"><svg viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg></div>
                <h3>{{ $feature['title'] }}</h3>
                <p>{{ $feature['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="testi-section">
    <div class="testi-head">
        <div class="section-label">Testimoni</div>
        <h2 class="section-title">Apa kata mereka?</h2>
    </div>
    <div class="testi-track-wrap">
        <div class="testi-track" id="testiTrack">
            @foreach ($testimonials as $testi)
                <div class="testi-card marketing-reveal">
                    <div class="testi-stars">★★★★★</div>
                    <div class="testi-text">"{{ $testi['text'] }}"</div>
                    <div class="testi-author">
                        <img class="testi-avatar" src="{{ $testi['avatar'] }}" alt="{{ $testi['name'] }}" loading="lazy">
                        <div>
                            <div class="testi-name">{{ $testi['name'] }}</div>
                            <div class="testi-role">{{ $testi['role'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="testi-nav">
        <button type="button" class="slider-btn" id="testiPrev" aria-label="Testimoni sebelumnya">&#8592;</button>
        <button type="button" class="slider-btn" id="testiNext" aria-label="Testimoni berikutnya">&#8594;</button>
    </div>
</section>

<section class="pricing" id="harga">
    <div class="pricing-head">
        <div class="section-label">Harga Transparan</div>
        <h2 class="section-title">Mulai dari {{ format_rupiah($plans->first()?->price_monthly_idr ?? 129000) }}/bulan</h2>
        <p class="section-sub">Bayar tahunan lebih hemat. Tanpa biaya tersembunyi.</p>
    </div>
    <div class="pricing-grid">
        @foreach ($plans as $index => $plan)
            @php
                $isPopular = ($plan->slug ?? '') === 'business' || $index === 1;
                $isEnterprise = ($plan->slug ?? '') === 'enterprise';
                $monthlyFormatted = number_format($plan->price_monthly_idr, 0, ',', '.');
                $yearlyFormatted = number_format($plan->price_yearly_idr ?? 0, 0, ',', '.');
                $planFeatures = \App\Support\PlanMarketing::featureLines($plan, includeRoadmap: true);
                $yearlySavings = $plan instanceof \App\Models\Plan
                    ? $plan->yearlySavings()
                    : max(0, ($plan->price_monthly_idr * 12) - (int) ($plan->price_yearly_idr ?? 0));
                $yearlyDiscountPercent = $plan instanceof \App\Models\Plan
                    ? $plan->yearlyDiscountPercent()
                    : ($plan->price_monthly_idr > 0
                        ? (int) round(($yearlySavings / ($plan->price_monthly_idr * 12)) * 100)
                        : 0);
                $yearlyEquivalentMonthly = $plan instanceof \App\Models\Plan
                    ? $plan->yearlyEquivalentMonthly()
                    : (int) round(((int) ($plan->price_yearly_idr ?? $plan->price_monthly_idr * 12)) / 12);
                $hasYearlyDiscount = $yearlySavings > 0;
            @endphp
            <div @class(['price-card', 'marketing-reveal', 'popular' => $isPopular])>
                @if ($isPopular)
                    <div class="popular-badge">Paling Populer</div>
                @endif
                <div class="price-tier">{{ $plan->name }}</div>
                <div class="price-amount"><span class="price-currency">Rp </span>{{ $monthlyFormatted }}</div>
                <div class="price-period">/bulan</div>
                @if ($hasYearlyDiscount)
                    <div class="price-yearly-save">Hemat {{ format_rupiah($yearlySavings) }} · {{ $yearlyDiscountPercent }}% jika bayar tahunan</div>
                    <div class="price-yearly">Rp {{ $yearlyFormatted }}/tahun <span>(setara {{ format_rupiah($yearlyEquivalentMonthly) }}/bln)</span></div>
                @else
                    <div class="price-period">atau Rp {{ $yearlyFormatted }}/tahun</div>
                @endif
                <div class="price-divider"></div>
                <ul class="price-features">
                    @foreach ($planFeatures as $feature)
                        <li>
                            <span class="price-check"><svg viewBox="0 0 12 12"><polyline points="2,6 5,9 10,3"/></svg></span>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                @if ($isEnterprise)
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="btn-outline-full">
                        Hubungi kami
                    </a>
                @else
                    <a href="{{ $registerUrl }}" @class(['btn-red-full' => $isPopular, 'btn-outline-full' => ! $isPopular])>
                        Mulai Gratis 14 Hari
                    </a>
                @endif
            </div>
        @endforeach
    </div>
</section>

<section class="support-section" id="hubungi-kami">
    <div class="support-card marketing-reveal">
        <div class="support-visual">
            <div class="support-visual-glow"></div>
            <img
                src="{{ $wahyuPhoto }}"
                alt="Tim support Xiway POS"
                loading="lazy"
            >
        </div>
        <div class="support-content">
            <div class="section-label">Hubungi Kami</div>
            <h2>Pertanyaan?<br><span>Kami siap bantu</span></h2>
            <p>Hubungi kami kapan saja — mulai dari cara daftar, fitur kasir, sampai langganan setelah trial.</p>
            <div class="support-actions">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="support-wa-btn">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.75.75 0 00.914.914l4.458-1.495A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.714 9.714 0 01-4.915-1.332l-.352-.209-2.64.886.886-2.64-.209-.352A9.714 9.714 0 012.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z"/></svg>
                    Tanya lewat WhatsApp
                </a>
                <span class="support-note">Respon cepat · Senin–Sabtu</span>
            </div>
        </div>
    </div>
</section>

<section class="faq" id="faq">
    <div class="faq-head">
        <div class="section-label">FAQ</div>
        <h2 class="section-title">Pertanyaan Umum</h2>
    </div>
    <div class="faq-inner">
        @foreach ($faqs as $faq)
            <div class="faq-item">
                <div class="faq-question">
                    {{ $faq['q'] }}
                    <div class="faq-icon">+</div>
                </div>
                <div class="faq-answer">{{ $faq['a'] }}</div>
            </div>
        @endforeach
    </div>
</section>

<section class="cta-section">
    <div class="cta-bg-img">
        <img src="https://images.unsplash.com/photo-1445116572660-236099ec97a0?w=1400&q=80" alt="" loading="lazy">
    </div>
    <div class="cta-overlay"></div>
    <div class="cta-content">
        <h2>Siap mulai jualan<br><span>hari ini?</span></h2>
        <p>Daftar gratis, setup cepat, langsung bisa terima pesanan pertama Anda.</p>
        <a href="{{ $registerUrl }}" class="btn-red-lg">Coba Gratis 14 Hari →</a>
    </div>
</section>

<footer class="marketing-footer">
    <div class="footer-main">
        <div class="footer-grid">
            <div class="footer-col footer-col-brand">
                <a href="{{ route('marketing.home') }}" class="footer-logo">xiway<em>pos</em></a>
                <p class="footer-tagline">Sistem kasir online untuk cafe, coffee shop, dan restoran.</p>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Layanan</h4>
                <ul class="footer-menu">
                    <li><a href="#fitur">Kasir & Pembayaran</a></li>
                    <li><a href="#fitur">Kelola Menu</a></li>
                    <li><a href="#fitur">Laporan Harian</a></li>
                    <li><a href="#fitur">Tagihan Sementara</a></li>
                    <li><a href="#fitur">Multi Pengguna</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Solusi Bisnis</h4>
                <ul class="footer-menu">
                    <li><a href="#fitur">Kedai Kopi</a></li>
                    <li><a href="#fitur">Restoran</a></li>
                    <li><a href="#fitur">Minuman & Boba</a></li>
                    <li><a href="#fitur">Food Truck</a></li>
                </ul>
                <ul class="footer-menu footer-menu-spaced">
                    <li><a href="#harga">Harga</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4 class="footer-heading">Perusahaan</h4>
                <ul class="footer-menu">
                    <li><a href="{{ $waUrl }}" target="_blank" rel="noopener">Hubungi Kami</a></li>
                    <li><a href="#cara-kerja">Demo</a></li>
                    <li><a href="{{ $registerUrl }}">Daftar Gratis</a></li>
                    <li><a href="{{ $loginUrl }}">Masuk</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-divider"></div>

    <div class="footer-bottom">
        <div class="footer-bottom-left">
            <span class="footer-lang">Indonesia</span>
            <ul class="footer-legal">
                <li><a href="{{ route('marketing.privacy') }}">Kebijakan Privasi</a></li>
                <li><a href="{{ route('marketing.terms') }}">Syarat dan Ketentuan</a></li>
            </ul>
        </div>
        <div class="footer-social">
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="footer-social-link">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.520-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.510-.173-.008-.371-.01-.570-.01-.198 0-.520.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.75.75 0 00.914.914l4.458-1.495A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
            </a>
            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="footer-social-link">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
            </a>
            <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" aria-label="YouTube" class="footer-social-link">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 8.5a3 3 0 00-2.1-2.2C18.5 6 12 6 12 6s-6.5 0-7.9.3A3 3 0 002 8.5 31 31 0 001.7 12a31 31 0 00.3 3.5 3 3 0 002.1 2.2c1.4.3 7.9.3 7.9.3s6.5 0 7.9-.3a3 3 0 002.1-2.2A31 31 0 0022.3 12a31 31 0 00-.3-3.5z"/><path d="M10 9.5v5l4.5-2.5L10 9.5z"/></svg>
            </a>
            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="footer-social-link">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9H2v13h4V9zM4 2a2.5 2.5 0 110 5 2.5 2.5 0 010-5zM22 22h-4v-6.5c0-1.5-.5-2.5-1.9-2.5-1 0-1.6.7-1.9 1.4-.1.2-.1.5-.1.8V22h-4s.1-9 0-13h4v1.8c.5-.8 1.5-2 3.7-2 2.7 0 4.7 1.8 4.7 5.6V22z"/></svg>
            </a>
        </div>
    </div>

    <div class="footer-copy">© {{ date('Y') }} Xiway POS. Hak cipta dilindungi.</div>

    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="footer-fab">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.130-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.520-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.510-.173-.008-.371-.01-.570-.01-.198 0-.520.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.617h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884"/></svg>
        Hubungi kami
    </a>
</footer>

@include('partials.marketing-scripts')
@endsection
