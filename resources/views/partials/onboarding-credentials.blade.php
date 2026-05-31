@if ($credentials = session('registration_credentials'))
    <div class="ob-credentials">
        <h3>Akun Anda sudah dibuat otomatis</h3>
        <p>Simpan data login berikut. Password sama untuk admin dan kasir.</p>
        <dl>
            <dt>Admin</dt>
            <dd>{{ $credentials['admin']['email'] }}</dd>
            <dt>Kasir</dt>
            <dd>{{ $credentials['kasir']['email'] }}</dd>
            <dt>Password</dt>
            <dd>{{ $credentials['password'] }}</dd>
        </dl>
    </div>
@endif
