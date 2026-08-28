<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#17231d">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', $siteSettings['seo_title'] ?? 'Webdev Reza — Developer & pembuat project')</title>
    <meta name="description" content="@yield('description', $siteSettings['seo_description'] ?? 'Portfolio, tulisan, dan perjalanan project teknologi Reza.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Webdev Reza')">
    <meta property="og:description" content="@yield('description', 'Portfolio dan tulisan Webdev Reza')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta name="twitter:card" content="summary_large_image">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <a class="skip" href="#content">Lewati ke konten</a>
    <div class="cursor-glow" aria-hidden="true"></div>

    <header class="site-header">
        <nav class="container nav" aria-label="Navigasi utama">
            <a class="brand" href="{{ route('home') }}" aria-label="Webdev Reza, kembali ke beranda">
                <span class="brand-mark" aria-hidden="true">R</span>
                <span class="brand-copy">{{ $siteSettings['site_name'] ?? 'Webdev Reza' }}<small lang="ja">ウェブ開発者</small></span>
            </a>
            <button class="menu" type="button" aria-expanded="false" aria-controls="navlinks">
                <span class="menu-lines" aria-hidden="true"></span><span>Menu</span>
            </button>
            <div id="navlinks">
                <a @class(['active' => request()->routeIs('home')]) href="{{ route('home') }}"><span>Beranda<small lang="ja">ホーム</small></span></a>
                <a @class(['active' => request()->routeIs('projects.*')]) href="{{ route('projects.index') }}"><span>Project<small lang="ja">作品</small></span></a>
                <a @class(['active' => request()->routeIs('blog.*')]) href="{{ route('blog.index') }}"><span>Tulisan<small lang="ja">日誌</small></span></a>
                <a @class(['active' => request()->routeIs('about')]) href="{{ route('about') }}"><span>Tentang<small lang="ja">自己紹介</small></span></a>
                <a class="nav-cta" href="{{ route('contact') }}"><span>Kontak<small lang="ja">連絡</small></span> <b aria-hidden="true">↗</b></a>
            </div>
        </nav>
    </header>

    <main id="content">
        @if (session('success'))
            <div class="container notice" role="status">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    <footer>
        <div class="container footer">
            <div class="footer-brand">
                <span class="brand-mark" aria-hidden="true">R</span>
                <div><strong>{{ $siteSettings['site_name'] ?? 'Webdev Reza' }}</strong><p>Membuat teknologi dari kebutuhan nyata.</p></div>
            </div>
            <div class="footer-links">
                <a href="{{ $siteSettings['github'] ?? 'https://github.com/rezafikkri' }}" rel="noopener noreferrer" target="_blank">GitHub ↗</a>
                <a href="https://wa.me/{{ $siteSettings['whatsapp'] ?? '62895358302211' }}" rel="noopener noreferrer" target="_blank">WhatsApp ↗</a>
                <a href="{{ route('admin.login') }}">Admin</a>
            </div>
            <small>© {{ date('Y') }} Reza · Built with Laravel, curiosity, and too much coffee.</small>
        </div>
    </footer>
</body>
</html>
