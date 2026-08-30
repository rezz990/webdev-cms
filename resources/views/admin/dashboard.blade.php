@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <section class="admin-hero">
        <div><span class="eyebrow">Ringkasan workspace</span><h1>Kelola website dari satu dashboard.</h1><p>Pantau konten, pesan, jadwal publikasi, dan perubahan terbaru.</p></div>
        <div class="actions"><a class="button" href="{{ route('admin.posts.create') }}"><x-icon name="plus" size="17"/> Tulisan baru</a><a class="button ghost" href="{{ route('admin.projects.create') }}"><x-icon name="plus" size="17"/> Project baru</a></div>
    </section>

    <section class="dashboard-stats" aria-label="Statistik website">
        <article class="stat-card"><div><small>Total project</small><strong>{{ $projectCount }}</strong><a href="{{ route('admin.projects.index') }}">Kelola project</a></div><x-icon name="project"/></article>
        <article class="stat-card"><div><small>Tulisan terbit</small><strong>{{ $publishedCount }}</strong><a href="{{ route('admin.posts.index', ['status' => 'published']) }}">Lihat tulisan</a></div><x-icon name="post"/></article>
        <article class="stat-card"><div><small>Draft tersimpan</small><strong>{{ $draftCount }}</strong><a href="{{ route('admin.posts.index', ['status' => 'draft']) }}">Lanjut menulis</a></div><x-icon name="clock"/></article>
        <article class="stat-card"><div><small>Pesan belum dibaca</small><strong>{{ $unreadCount }}</strong><a href="{{ route('admin.messages.index', ['status' => 'unread']) }}">Buka inbox</a></div><x-icon name="message"/></article>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-panel">
            <header class="dashboard-panel-head"><div><span class="eyebrow">Aktivitas konten</span><h2>Terakhir diperbarui</h2></div></header>
            <div class="activity-list">
                @forelse ($recentPosts as $post)
                    <a class="activity-item" href="{{ route('admin.posts.edit', $post) }}"><span><strong>{{ $post->title }}</strong><small>Tulisan · {{ $post->updated_at->diffForHumans() }}</small></span><span class="status-badge {{ $post->status->value }}">{{ ucfirst($post->status->value) }}</span></a>
                @empty
                    <p class="empty-state">Belum ada tulisan.</p>
                @endforelse
                @foreach ($recentProjects as $project)
                    <a class="activity-item" href="{{ route('admin.projects.edit', $project) }}"><span><strong>{{ $project->name }}</strong><small>Project · {{ $project->updated_at->diffForHumans() }}</small></span><span class="status-badge {{ $project->status->value }}">{{ ucfirst($project->status->value) }}</span></a>
                @endforeach
            </div>
        </div>

        <div class="dashboard-panel">
            <header class="dashboard-panel-head"><div><span class="eyebrow">Akses cepat</span><h2>Buat dan kelola</h2></div></header>
            <div class="quick-actions">
                <a href="{{ route('admin.posts.create') }}"><x-icon name="post"/><span>Buat tulisan</span></a>
                <a href="{{ route('admin.projects.create') }}"><x-icon name="project"/><span>Tambah project</span></a>
                <a href="{{ route('admin.media.index') }}"><x-icon name="media"/><span>Media ({{ $mediaCount }})</span></a>
                <a href="{{ route('admin.settings.edit') }}"><x-icon name="settings"/><span>Pengaturan</span></a>
            </div>
        </div>

        <div class="dashboard-panel">
            <header class="dashboard-panel-head"><div><span class="eyebrow">Publikasi</span><h2>Jadwal mendatang</h2></div><a href="{{ route('admin.posts.index', ['status' => 'scheduled']) }}">Lihat semua</a></header>
            <div class="activity-list">
                @forelse ($scheduledPosts as $post)
                    <a class="activity-item" href="{{ route('admin.posts.edit', $post) }}"><span><strong>{{ $post->title }}</strong><small>{{ $post->published_at->translatedFormat('d M Y H:i') }}</small></span><x-icon name="clock" size="18"/></a>
                @empty
                    <p class="empty-state">Belum ada tulisan terjadwal.</p>
                @endforelse
            </div>
        </div>

        <div class="dashboard-panel">
            <header class="dashboard-panel-head"><div><span class="eyebrow">Inbox</span><h2>Pesan terbaru</h2></div><a href="{{ route('admin.messages.index') }}">Buka inbox</a></header>
            <div class="activity-list">
                @forelse ($unreadMessages as $message)
                    <a class="activity-item" href="{{ route('admin.messages.show', $message) }}"><span><strong>{{ $message->subject ?: 'Pesan dari '.$message->name }}</strong><small>{{ $message->name }} · {{ $message->created_at->diffForHumans() }}</small></span></a>
                @empty
                    <p class="empty-state">Tidak ada pesan baru.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
