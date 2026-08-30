<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080a14">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <title>@yield('title') — Reza CMS</title>
    @vite(['resources/css/app.css', 'resources/css/admin-dark.css', 'resources/js/app.js'])
</head>
<body class="admin-shell admin-dark">
    <a class="skip" href="#admin-content">Lewati ke konten</a>
    <aside class="admin-sidebar" id="admin-sidebar">
        <a class="brand admin-brand" href="{{ route('admin.dashboard') }}">
            <span class="brand-mark">R</span><span>Reza <small>CMS</small></span>
        </a>
        <nav aria-label="Navigasi dashboard">
            <p class="nav-label">Workspace</p>
            <a @class(['active' => request()->routeIs('admin.dashboard')]) href="{{ route('admin.dashboard') }}"><x-icon name="dashboard"/> <span>Ringkasan</span></a>
            <a @class(['active' => request()->routeIs('admin.posts.*')]) href="{{ route('admin.posts.index') }}"><x-icon name="post"/> <span>Tulisan</span></a>
            <a @class(['active' => request()->routeIs('admin.projects.*')]) href="{{ route('admin.projects.index') }}"><x-icon name="project"/> <span>Project</span></a>
            <a @class(['active' => request()->routeIs('admin.messages.*')]) href="{{ route('admin.messages.index') }}"><x-icon name="message"/> <span>Pesan</span></a>
            <a @class(['active' => request()->routeIs('admin.media.*')]) href="{{ route('admin.media.index') }}"><x-icon name="media"/> <span>Media</span></a>
            <p class="nav-label">Sistem</p>
            <a @class(['active' => request()->routeIs('admin.settings.*')]) href="{{ route('admin.settings.edit') }}"><x-icon name="settings"/> <span>Pengaturan</span></a>
        </nav>
        <div class="sidebar-footer">
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"><x-icon name="external"/> <span>Lihat website</span></a>
            <form method="post" action="{{ route('admin.logout') }}">@csrf<button type="submit"><x-icon name="logout"/> <span>Keluar</span></button></form>
        </div>
    </aside>

    <div class="admin-workspace">
        <header class="admin-topbar">
            <button class="admin-menu" type="button" aria-expanded="false" aria-controls="admin-sidebar" aria-label="Buka navigasi"><x-icon name="menu"/><span>Menu</span></button>
            <div class="admin-welcome"><p>Selamat datang kembali</p><strong>{{ auth()->user()->name }}</strong></div>
            <button class="command-trigger" type="button" data-command-open><x-icon name="search"/><span>Cari menu</span><kbd>Ctrl K</kbd></button>
            <span class="admin-avatar" aria-label="Akun {{ auth()->user()->name }}">{{ auth()->user()->initials() }}</span>
        </header>
        <main id="admin-content" class="admin-content">
            @if (session('success'))
                <div class="notice" role="status">
                    <x-icon name="check"/><span>{{ session('success') }}</span>
                    @if (session('public_url'))
                        <a href="{{ session('public_url') }}" target="_blank" rel="noopener noreferrer">Lihat hasil <x-icon name="external" size="16"/></a>
                    @endif
                </div>
            @endif
            @if ($errors->any())
                <div class="error-box" role="alert"><strong>Ada yang perlu diperiksa.</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </main>
    </div>

    <dialog class="command-palette" data-command-dialog>
        <div class="command-head"><x-icon name="search"/><input type="search" placeholder="Cari menu dashboard" aria-label="Cari menu dashboard" data-command-input><button type="button" data-command-close>Tutup</button></div>
        <div class="command-list">
            <a href="{{ route('admin.posts.create') }}"><x-icon name="post"/><span><strong>Buat tulisan</strong><small>Mulai draft baru</small></span></a>
            <a href="{{ route('admin.projects.create') }}"><x-icon name="project"/><span><strong>Buat project</strong><small>Tambahkan karya baru</small></span></a>
            <a href="{{ route('admin.messages.index') }}"><x-icon name="message"/><span><strong>Buka pesan</strong><small>Lihat kontak masuk</small></span></a>
            <a href="{{ route('admin.settings.edit') }}"><x-icon name="settings"/><span><strong>Pengaturan</strong><small>Edit profil publik</small></span></a>
        </div>
    </dialog>
    <button class="sidebar-backdrop" type="button" aria-label="Tutup navigasi" data-sidebar-close hidden></button>
</body>
</html>
