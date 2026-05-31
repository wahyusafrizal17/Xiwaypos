@php
    $credentials = session('registration_credentials');
@endphp

@if ($credentials)
    <div
        class="vx-cred-modal-overlay"
        x-data="{
            open: true,
            closing: false,
            async dismiss() {
                if (this.closing) return;
                this.closing = true;
                this.open = false;
                try {
                    await fetch(@js(route('registration-credentials.dismiss')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                } catch (_) {}
            },
        }"
        x-show="open"
        x-cloak
        x-transition.opacity
        x-on:keydown.escape.window="dismiss()"
    >
        <div
            class="vx-cred-modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="reg-credentials-title"
            x-on:click.stop
            x-transition
        >
            <div class="vx-cred-modal-head">
                <div>
                    <h3 id="reg-credentials-title">Akun admin &amp; kasir sudah dibuat</h3>
                    <p>Simpan data login berikut. Password sama untuk admin dan kasir.</p>
                </div>
                <button type="button" class="vx-cred-modal-close" x-on:click="dismiss()" aria-label="Tutup">&times;</button>
            </div>

            <div class="vx-cred-modal-body">
                <dl class="vx-cred-grid">
                    <div>
                        <dt>Admin</dt>
                        <dd>{{ $credentials['admin']['email'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>Kasir</dt>
                        <dd>{{ $credentials['kasir']['email'] ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>Password</dt>
                        <dd>{{ $credentials['password'] ?? '—' }}</dd>
                    </div>
                </dl>
                <p class="vx-cred-note">Password juga tersimpan di menu <strong>Pengguna</strong> sampai Anda mengubahnya.</p>
            </div>

            <div class="vx-cred-modal-actions">
                <button type="button" class="vx-btn vx-btn-primary w-full sm:w-auto" x-on:click="dismiss()">
                    Saya sudah menyimpan
                </button>
            </div>
        </div>
    </div>
@endif
