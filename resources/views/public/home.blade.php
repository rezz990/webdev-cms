@extends('layouts.public')

@section('content')
    <section class="hero japanese-anime-hero">
        <div class="sakura-petals" aria-hidden="true">@for ($i = 0; $i < 12; $i++)<i></i>@endfor</div>
        <div class="speed-lines" aria-hidden="true"></div>
        <div class="container hero-grid">
            <div class="hero-copy" data-reveal>
                <span class="status-pill"><i aria-hidden="true"></i> {{ ($siteSettings['accepting_freelance'] ?? '1') === '1' ? 'Open for freelance' : 'Sedang fokus berkarya' }}</span>
                <p class="kicker"><span lang="ja">こんにちは</span> — Halo, saya Reza</p>
                <span class="vertical-jp" lang="ja" aria-hidden="true">創造・技術・物語</span>
                <h1>Saya mengubah <em>ide liar</em> menjadi produk digital yang terasa hidup.</h1>
                <p class="lead">{{ $siteSettings['short_bio'] ?? 'Website, aplikasi, dan eksperimen teknologi yang berangkat dari masalah nyata—dibangun supaya cepat, nyaman, dan benar-benar berguna.' }}</p>
                <div class="actions">
                    <a class="button button-burst" href="{{ route('projects.index') }}">Jelajahi project <span aria-hidden="true">→</span></a>
                    <a class="button ghost" href="{{ route('about') }}">Kenal lebih dekat</a>
                </div>
                <div class="hero-stats" aria-label="Ringkasan karya">
                    <span><strong>{{ $projects->count() }}</strong> project pilihan</span>
                    <span><strong>{{ $posts->count() }}</strong> catatan terbaru</span>
                    <span><strong>∞</strong> rasa penasaran</span>
                </div>
            </div>

            <x-anime-character />
        </div>
        <div class="scroll-cue" aria-hidden="true"><span></span> Scroll untuk eksplorasi</div>
    </section>

    <section class="section container" data-reveal>
        <div class="heading">
            <div><span class="eyebrow">選ばれた任務 · Selected missions</span><h2>Project unggulan</h2></div>
            <a class="text-link" href="{{ route('projects.index') }}">Lihat semua <span>→</span></a>
        </div>
        <div class="grid project-grid">
            @forelse ($projects as $project)
                @include('public.projects.card', ['project' => $project])
            @empty
                <p class="empty">Project sedang disiapkan. Nantikan karya berikutnya!</p>
            @endforelse
        </div>
    </section>

    <section class="section story-section manga-dots" data-reveal>
        <div class="container split">
            <div class="chapter-mark"><span>CH.</span><strong>02</strong></div>
            <div><span class="eyebrow">始まりの物語 · Origin story</span><h2>Teknologi seharusnya membantu, bukan menambah rumit.</h2></div>
            <div><p>Saya Reza, developer yang senang mengubah masalah nyata menjadi website dan aplikasi yang ringkas. Setiap project adalah episode baru untuk belajar, bereksperimen, dan membuat sesuatu yang berarti.</p><a class="text-link" href="{{ route('about') }}">Baca perjalanan saya <span>→</span></a></div>
        </div>
    </section>

    <section class="section container" data-reveal>
        <div class="heading"><div><span class="eyebrow">開発日誌 · Dev notes</span><h2>Catatan terbaru</h2></div><a class="text-link" href="{{ route('blog.index') }}">Semua tulisan <span>→</span></a></div>
        <div class="grid">
            @forelse ($posts as $post)
                @include('public.blog.card', ['post' => $post])
            @empty
                <p class="empty">Belum ada tulisan yang diterbitkan.</p>
            @endforelse
        </div>
    </section>

    <section class="section tech-marquee" aria-label="Teknologi utama">
        <div class="container"><span class="eyebrow">技術装備 · My loadout</span><h2>Teknologi yang saya pakai</h2></div>
        <div class="marquee-track"><div class="chips tech-chips">
            @foreach ($technologies as $technology)<span><i>✦</i> {{ $technology->name }}</span>@endforeach
            @foreach ($technologies as $technology)<span aria-hidden="true"><i>✦</i> {{ $technology->name }}</span>@endforeach
        </div></div>
    </section>

    <section class="cta" data-reveal>
        <div class="cta-sun" aria-hidden="true"></div>
        <div class="container split"><div><span class="eyebrow light-text">次のエピソード · Next episode?</span><h2>Punya ide yang layak jadi kenyataan?</h2><p>Ceritakan idenya. Kita mulai dari percakapan santai, tanpa jargon berlebihan.</p></div><a class="button light button-burst" href="{{ route('contact') }}">Mulai kolaborasi <span>↗</span></a></div>
    </section>
@endsection
