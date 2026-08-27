@extends('layouts.public')

@section('content')
    <section class="hero manga-dots">
        <div class="speed-lines" aria-hidden="true"></div>
        <div class="container hero-grid">
            <div class="hero-copy" data-reveal>
                <span class="status-pill"><i aria-hidden="true"></i> {{ ($siteSettings['accepting_freelance'] ?? '1') === '1' ? 'Open for freelance' : 'Sedang fokus berkarya' }}</span>
                <p class="kicker">こんにちは — Halo, saya Reza</p>
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

            <div class="hero-visual tilt-card" data-tilt data-reveal aria-label="Ilustrasi developer bergaya anime">
                <div class="speech-bubble">Let’s build<br><strong>something cool!</strong></div>
                <svg class="anime-avatar" viewBox="0 0 520 520" role="img" aria-labelledby="avatar-title avatar-desc">
                    <title id="avatar-title">Ilustrasi Reza sedang membuat website</title>
                    <desc id="avatar-desc">Karakter developer bergaya komik dengan laptop dan elemen kode.</desc>
                    <path class="avatar-bg" d="M72 81C129 21 230 8 323 35c94 27 158 94 144 188-14 94-80 200-180 230-100 31-222-10-248-104C13 255 15 141 72 81Z"/>
                    <path class="avatar-hair" d="M177 186c-8-80 45-133 123-125 72 7 111 63 98 136l-35-45-12 31-31-47-35 46-29-43-32 52-47-5Z"/>
                    <path class="avatar-face" d="M192 177c6-56 43-84 99-83 61 1 96 40 91 104-5 71-46 119-100 115-56-3-97-59-90-136Z"/>
                    <path class="avatar-eye" d="M228 218c13-12 27-12 40 0M315 218c13-12 27-12 40 0"/>
                    <path class="avatar-smile" d="M272 261c17 13 35 13 52-1"/>
                    <path class="avatar-shirt" d="M163 424c8-78 51-120 124-120 79 0 124 40 138 120H163Z"/>
                    <rect class="avatar-laptop" x="105" y="326" width="305" height="137" rx="14"/>
                    <path class="avatar-code" d="m224 379-23 18 23 18m67-36 23 18-23 18m-37 8 22-53"/>
                    <circle class="spark lime" cx="84" cy="243" r="18"/><path class="spark orange" d="m424 89 12 25 27 4-20 19 5 27-24-13-24 13 5-27-20-19 27-4 12-25Z"/>
                </svg>
                <span class="floating-tag tag-one">&lt;code/&gt;</span>
                <span class="floating-tag tag-two">Laravel</span>
                <span class="panel-number">#01</span>
            </div>
        </div>
        <div class="scroll-cue" aria-hidden="true"><span></span> Scroll untuk eksplorasi</div>
    </section>

    <section class="section container" data-reveal>
        <div class="heading">
            <div><span class="eyebrow">Selected missions</span><h2>Project unggulan</h2></div>
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
            <div><span class="eyebrow">Origin story</span><h2>Teknologi seharusnya membantu, bukan menambah rumit.</h2></div>
            <div><p>Saya Reza, developer yang senang mengubah masalah nyata menjadi website dan aplikasi yang ringkas. Setiap project adalah episode baru untuk belajar, bereksperimen, dan membuat sesuatu yang berarti.</p><a class="text-link" href="{{ route('about') }}">Baca perjalanan saya <span>→</span></a></div>
        </div>
    </section>

    <section class="section container" data-reveal>
        <div class="heading"><div><span class="eyebrow">Dev notes</span><h2>Catatan terbaru</h2></div><a class="text-link" href="{{ route('blog.index') }}">Semua tulisan <span>→</span></a></div>
        <div class="grid">
            @forelse ($posts as $post)
                @include('public.blog.card', ['post' => $post])
            @empty
                <p class="empty">Belum ada tulisan yang diterbitkan.</p>
            @endforelse
        </div>
    </section>

    <section class="section tech-marquee" aria-label="Teknologi utama">
        <div class="container"><span class="eyebrow">My loadout</span><h2>Teknologi yang saya pakai</h2></div>
        <div class="marquee-track"><div class="chips tech-chips">
            @foreach ($technologies as $technology)<span><i>✦</i> {{ $technology->name }}</span>@endforeach
            @foreach ($technologies as $technology)<span aria-hidden="true"><i>✦</i> {{ $technology->name }}</span>@endforeach
        </div></div>
    </section>

    <section class="cta" data-reveal>
        <div class="cta-sun" aria-hidden="true"></div>
        <div class="container split"><div><span class="eyebrow light-text">Next episode?</span><h2>Punya ide yang layak jadi kenyataan?</h2><p>Ceritakan idenya. Kita mulai dari percakapan santai, tanpa jargon berlebihan.</p></div><a class="button light button-burst" href="{{ route('contact') }}">Mulai kolaborasi <span>↗</span></a></div>
    </section>
@endsection
