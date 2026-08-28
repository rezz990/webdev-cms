<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#17231d">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <title>@yield('title') — Reza CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
    <a class="skip" href="#admin-content">Lewati ke konten</a>
    <aside class="admin-sidebar" id="admin-sidebar">
        <a class="brand admin-brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark">R</span><span>Reza <small>CMS</small></span></a>
        <nav aria-label="Navigasi dashboard">
            <p class="nav-label">Workspace</p>
            <a @class(['active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}"><span>⌂</span> Ringkasan</a>
            <a @class(['active' => request()->routeIs('admin.posts.*')]) href="{{ route('admin.posts.index') }}"><span>✎</span> Tulisan</a>
            <a @class(['active' => request()->routeIs('admin.projects.*')]) href="{{ route('admin.projects.index') }}"><span>◇</span> Project</a>
            <a @class(['active' => request()->routeIs('admin.messages.*')]) href="{{ route('admin.messages.index') }}"><span>✉</span> Pesan</a>
            <a @class(['active' => request()->routeIs('admin.media.*')]) href="{{ route('admin.media.index') }}"><span>▧</span> Media</a>
            <p class="nav-label">System</p>
            <a @class(['active' => request()->routeIs('admin.settings.*')]) href="{{ route('admin.settings.edit') }}"><span>⚙</span> Pengaturan</a>
        </nav>
        <div class="sidebar-footer">
            <a href="{{ route('home') }}" target="_blank">Lihat website <span>↗</span></a>
            <form method="post" action="{{ route('admin.logout') }}">@csrf<button type="submit">Keluar</button></form>
        </div>
    </aside>

    <div class="admin-workspace">
        <header class="admin-topbar">
            <button class="admin-menu" type="button" aria-expanded="false" aria-controls="admin-sidebar">☰ <span>Menu</span></button>
            <div><p class="admin-greeting">Selamat datang kembali,</p><strong>{{ auth()->user()->name }}</strong></div>
            <button class="command-trigger" type="button" data-command-open><span>⌕</span> Cari menu <kbd>Ctrl K</kbd></button>
            <span class="admin-avatar" aria-hidden="true">{{ auth()->user()->initials() }}</span>
        </header>
        <main id="admin-content" class="admin-content">
            @if (session('success'))
                <div class="notice" role="status">
                    ✓ {{ session('success') }}
                    @if (session('public_url'))
                        <a href="{{ session('public_url') }}" target="_blank" rel="noopener noreferrer">Lihat hasil di website ↗</a>
                    @endif
                </div>
            @endif
            @if ($errors->any())<div class="error-box" role="alert"><strong>Ada yang perlu diperiksa.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </main>
    </div>

    <dialog class="command-palette" data-command-dialog>
        <div class="command-head"><span>⌕</span><input type="search" placeholder="Ketik untuk mencari menu…" aria-label="Cari menu dashboard" data-command-input><button type="button" data-command-close>Esc</button></div>
        <div class="command-list">
            <a href="{{ route('admin.posts.create') }}"><span>✎</span><span><strong>Buat tulisan</strong><small>Mulai draft baru</small></span></a>
            <a href="{{ route('admin.projects.create') }}"><span>◇</span><span><strong>Buat project</strong><small>Tambahkan karya baru</small></span></a>
            <a href="{{ route('admin.messages.index') }}"><span>✉</span><span><strong>Buka pesan</strong><small>Lihat kontak masuk</small></span></a>
            <a href="{{ route('admin.settings.edit') }}"><span>⚙</span><span><strong>Pengaturan</strong><small>Edit profil publik</small></span></a>
        </div>
    </dialog>
</body>
</html>
