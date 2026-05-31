@extends('layouts.onboarding')

@section('content')
    <h2 class="ob-title">Kategori Produk</h2>
    <p class="ob-subtitle">Pilih kategori yang sesuai dengan menu Anda.</p>

    <form method="POST" action="{{ route('onboarding.categories.save') }}">
        @csrf

        <div class="ob-check-grid">
            @foreach ($presets as $preset)
                <label class="ob-check-card">
                    <input type="checkbox" name="categories[]" value="{{ $preset }}"
                        {{ in_array($preset, old('categories', ['Minuman', 'Makanan']), true) ? 'checked' : '' }}>
                    <span>{{ $preset }}</span>
                </label>
            @endforeach
        </div>
        @error('categories')<p class="ob-error">{{ $message }}</p>@enderror

        <button type="submit" class="ob-btn" style="margin-top: 1rem;">Lanjut ke Produk →</button>
    </form>
@endsection
