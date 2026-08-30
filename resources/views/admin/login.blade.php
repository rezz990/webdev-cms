@extends('layouts.auth')

@section('title', 'Masuk Dashboard')

@section('content')
    <section class="auth-page">
        <div class="container auth-grid">
            <div class="auth-copy">
                <a class="auth-brand" href="{{ route('home') }}"><span class="brand-mark">R</span><span>Webdev Reza</span></a>
                <span class="eyebrow">Content management system</span>
                <h1>Kelola semua karya dari <span>satu tempat.</span></h1>
                <p>Dashboard khusus untuk mengelola project, tulisan, pesan masuk, media, dan identitas website.</p>
                <div class="auth-features"><span>Laravel session</span><span>Rate limited</span><span>Admin only</span></div>
            </div>
            <div class="auth-card">
                <div class="auth-card-head"><span>Area admin</span><h2>Selamat datang kembali</h2><p>Masukkan kredensial admin untuk melanjutkan.</p></div>
                <form class="auth-form" method="post" action="{{ route('admin.login.store') }}">
                    @csrf
                    <label>Email admin
                        <span class="auth-input"><x-icon name="message" size="18"/><input type="email" name="email" value="{{ old('email') }}" placeholder="admin@domain.com" required autofocus autocomplete="username"></span>
                    </label>
                    @error('email')<small class="error">{{ $message }}</small>@enderror
                    <label>Kata sandi
                        <span class="auth-input"><x-icon name="settings" size="18"/><input id="admin-password" type="password" name="password" placeholder="Masukkan kata sandi" required autocomplete="current-password"><button type="button" data-password-toggle aria-controls="admin-password">Lihat</button></span>
                    </label>
                    <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya di perangkat ini</label>
                    <button class="auth-submit" type="submit">Masuk dashboard</button>
                </form>
                <p class="auth-note">Akses dilindungi session Laravel dan pembatasan percobaan login.</p>
            </div>
        </div>
    </section>
@endsection
