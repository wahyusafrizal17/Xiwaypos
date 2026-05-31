@extends('layouts.onboarding')

@section('content')
    <h2 class="ob-title">Tambah Produk</h2>
    <p class="ob-subtitle">Edit harga jika perlu. Minimal 1 produk untuk mulai jualan.</p>

    <form method="POST" action="{{ route('onboarding.products.save') }}">
        @csrf

        @foreach ($presets as $i => $preset)
            <div class="ob-product-card">
                <input type="hidden" name="products[{{ $i }}][kategori]" value="{{ $preset['kategori'] }}">
                <div class="ob-field">
                    <label class="ob-label" for="product_name_{{ $i }}">{{ $preset['kategori'] }}</label>
                    <input id="product_name_{{ $i }}" type="text" name="products[{{ $i }}][nama_produk]" class="ob-input"
                        value="{{ old("products.{$i}.nama_produk", $preset['nama_produk']) }}" required>
                </div>
                <div class="ob-field" style="margin-bottom: 0;">
                    <label class="ob-label" for="product_price_{{ $i }}">Harga (Rp)</label>
                    <input id="product_price_{{ $i }}" type="number" name="products[{{ $i }}][harga]" class="ob-input"
                        value="{{ old("products.{$i}.harga", $preset['harga']) }}" min="0" required>
                </div>
            </div>
        @endforeach

        @error('products')<p class="ob-error">{{ $message }}</p>@enderror

        <button type="submit" class="ob-btn">Lanjut →</button>
    </form>
@endsection
