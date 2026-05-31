@extends('layouts.onboarding')

@section('content')
    <div style="text-align: center;">
        <div class="ob-complete-icon">🎉</div>
        <h2 class="ob-title">Toko Siap Digunakan!</h2>
        <p class="ob-subtitle">
            <strong>{{ $tenant?->displayName() }}</strong> sudah memiliki
            {{ $categoryCount }} kategori dan {{ $productCount }} produk.
        </p>

        <form method="POST" action="{{ route('onboarding.finish') }}">
            @csrf
            <button type="submit" class="ob-btn ob-btn--success">Mulai Transaksi Pertama →</button>
        </form>

        <p class="ob-complete-note">Trial 14 hari aktif. Anda bisa tambah produk lagi nanti di menu Admin.</p>
    </div>
@endsection
