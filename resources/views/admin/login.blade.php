@extends('layouts.public')

@section('title', 'Masuk Dashboard — Webdev Reza')

@section('content')
    <section class="login-scene manga-dots">
        <div class="speed-lines" aria-hidden="true"></div>
        <div class="container login-grid">
            <div class="login-intro" data-reveal>
                <span class="eyebrow">Reza CMS · Secure area</span>
                <h1>Kendalikan seluruh <em>cerita</em> dari satu tempat.</h1>
                <p>Masuk untuk mengelola project, tulisan, pesan, media, dan profil publik Anda.</p>
                <div class="login-help">
                    <span class="help-number">?</span>
                    <div><strong>Belum punya akun admin?</strong><p>Di terminal project jalankan:</p><code>php artisan admin:create</code><small>Ikuti pertanyaan nama, email, dan password. Lalu kembali ke halaman ini.</small></div>
                </div>
            </div>
            <div class="login-panel" data-reveal>
                <div class="panel-top"><span>AUTH_GATE</span><span class="panel-lights"><i></i><i></i><i></i></span></div>
                <div class="login-panel-body">
                    <span class="brand-mark large" aria-hidden="true">R</span>
                    <h2>Selamat datang kembali!</h2>
                    <p>Gunakan email dan password admin Anda.</p>
                    <form class="form" method="post" action="{{ route('admin.login.store') }}">
                        @csrf
                        <label>Email admin
                            <span class="input-wrap"><span aria-hidden="true">@</span><input type="email" name="email" value="{{ old('email') }}" placeholder="admin@domain.com" required autofocus autocomplete="username"></span>
                        </label>
                        @error('email')<small class="error">{{ $message }}</small>@enderror
                        <label>Kata sandi
                            <span class="input-wrap"><span aria-hidden="true">●</span><input id="admin-password" type="password" name="password" required autocomplete="current-password"><button class="password-toggle" type="button" data-password-toggle aria-controls="admin-password">Lihat</button></span>
                        </label>
                        <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
                        <button class="button button-burst full" type="submit">Masuk dashboard <span aria-hidden="true">→</span></button>
                    </form>
                    <p class="secure-note">🔒 Dilindungi session Laravel dan rate limiting.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
