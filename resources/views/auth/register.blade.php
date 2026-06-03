@extends('layouts.login')

@section('title', 'Daftar — Coba Gratis 14 Hari')
@section('card-class', 'login-card--register')

@section('content')
    <div class="login-logo-row">
        <x-xiway-logo class="login-logo-img" />
        <span class="login-logo-text">xiway<em>pos</em></span>
    </div>

    <p class="register-lead">Buat akun trial gratis. Langsung bisa kelola menu dan transaksi.</p>

    @include('partials.marketing-register-trust')

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="login-field login-field--labeled">
            <label for="tenant_name" class="auth-label">Nama Usaha / Toko</label>
            <input
                id="tenant_name"
                type="text"
                name="tenant_name"
                value="{{ old('tenant_name') }}"
                required
                autofocus
                placeholder="Contoh: Xiway Stack"
            />
            @error('tenant_name')
                <p class="login-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="login-field login-field--labeled">
            <label for="name" class="auth-label">Nama Pemilik</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                placeholder="Contoh: Wahyu Safrizal"
            />
            @error('name')
                <p class="login-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="login-field login-field--labeled">
            <label for="whatsapp" class="auth-label">No. HP / WhatsApp</label>
            <input
                id="whatsapp"
                type="text"
                name="whatsapp"
                value="{{ old('whatsapp') }}"
                required
                placeholder="08xxxxxxxxxx"
            />
            @error('whatsapp')
                <p class="login-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="login-actions">
            <a href="{{ route('login') }}">Sudah punya akun?</a>
            <button type="submit" class="login-btn login-btn--auto">Coba Gratis 14 Hari</button>
        </div>
    </form>
@endsection
