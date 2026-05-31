@extends('layouts.onboarding')

@section('content')
    <h2 class="ob-title">Profil Toko</h2>
    <p class="ob-subtitle">Informasi dasar yang muncul di struk dan laporan.</p>

    <form method="POST" action="{{ route('onboarding.store.save') }}" enctype="multipart/form-data">
        @csrf

        <div class="ob-field">
            <label class="ob-label" for="store_name">Nama Toko</label>
            <input id="store_name" type="text" name="store_name" class="ob-input" value="{{ old('store_name', $tenant?->displayName()) }}" required>
            @error('store_name')<p class="ob-error">{{ $message }}</p>@enderror
        </div>

        <div class="ob-field">
            <label class="ob-label" for="store_address">Alamat <span class="ob-label-muted">(opsional)</span></label>
            <textarea id="store_address" name="store_address" rows="2" class="ob-textarea">{{ old('store_address', $tenant?->setting('store_address')) }}</textarea>
        </div>

        <div class="ob-field">
            <label class="ob-label" for="store_phone">Nomor Telepon Toko</label>
            <input id="store_phone" type="text" name="store_phone" class="ob-input" value="{{ old('store_phone', $tenant?->phone) }}" placeholder="08xxxxxxxxxx">
        </div>

        <div class="ob-field">
            <label class="ob-label" for="store_logo">Logo <span class="ob-label-muted">(opsional)</span></label>
            <input id="store_logo" type="file" name="store_logo" accept="image/*" class="ob-file">
        </div>

        <button type="submit" class="ob-btn">Lanjut ke Kategori →</button>
    </form>
@endsection
