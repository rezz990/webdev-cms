@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <section class="admin-hero">
        <div><span class="eyebrow">Control room</span><h1>Apa yang ingin kita buat hari ini?</h1><p>Semua konten, karya, dan percakapan website Anda ada di sini.</p></div>
        <div class="actions"><a class="button" href="{{ route('admin.posts.create') }}">＋ Tulisan baru</a><a class="button ghost" href="{{ route('admin.projects.create') }}">＋ Project baru</a></div>
    </section>

    <section class="dashboard-stats" aria-label="Statistik website">
        <article class="stat-card orange"><span class="stat-icon">◇</span><div><small>Total project</small><strong data-count="{{ $projectCount }}">{{ $projectCount }}</strong><a href="{{ route('admin.projects.index') }}">Kelola project →</a></div></article>
        <article class="stat-card lime"><span class="stat-icon">✓</span><div><small>Tulisan terbit</small><strong data-count="{{ $publishedCount }}">{{ $publishedCount }}</strong><a href="{{ route('admin.posts.index') }}">Lihat tulisan →</a></div></article>
        <article class="stat-card cream"><span class="stat-icon">✎</span><div><small>Draft tersimpan</small><strong data-count="{{ $draftCount }}">{{ $draftCount }}</strong><a href="{{ route('admin.posts.index') }}">Lanjut menulis →</a></div></article>
        <article class="stat-card dark"><span class="stat-icon">✉</span><div><small>Pesan baru</small><strong data-count="{{ $unreadCount }}">{{ $unreadCount }}</strong><a href="{{ route('admin.messages.index') }}">Buka inbox →</a></div></article>
    </section>

    <section class="dashboard-grid">
        <article class="mission-panel">
            <div class="panel-heading"><div><span class="eyebrow">Quick launch</span><h2>Mulai lebih cepat</h2></div><span class="panel-code">01</span></div>
            <div class="quick-grid">
                <a href="{{ route('admin.posts.create') }}"><span>✎</span><strong>Tulis artikel</strong><small>Bagikan catatan baru</small></a>
                <a href="{{ route('admin.projects.create') }}"><span>◇</span><strong>Tambah project</strong><small>Pamerkan karya terbaru</small></a>
                <a href="{{ route('admin.media.index') }}"><span>▧</span><strong>Upload media</strong><small>Kelola gambar website</small></a>
                <a href="{{ route('admin.settings.edit') }}"><span>⚙</span><strong>Edit profil</strong><small>Perbarui identitas publik</small></a>
            </div>
        </article>
        <article class="schedule-panel">
            <div class="panel-heading"><div><span class="eyebrow">Next release</span><h2>Jadwal terdekat</h2></div><span class="panel-code">02</span></div>
            @if ($scheduled)
                <div class="schedule-item"><span class="schedule-date"><strong>{{ $scheduled->published_at->format('d') }}</strong>{{ $scheduled->published_at->translatedFormat('M') }}</span><div><strong>{{ $scheduled->title }}</strong><p>Akan tayang {{ $scheduled->published_at->diffForHumans() }}</p></div></div>
            @else
                <div class="empty-illustration"><span>☾</span><strong>Belum ada tulisan terjadwal</strong><p>Siapkan cerita berikutnya saat inspirasi datang.</p><a href="{{ route('admin.posts.create') }}">Jadwalkan tulisan →</a></div>
            @endif
        </article>
    </section>
@endsection
