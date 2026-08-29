@extends('layouts.public')

@section('content')
    <section class="volume-cover" aria-labelledby="cover-title">
        <div class="cover-speed-lines" aria-hidden="true"></div>
        <div class="container cover-grid">
            <div class="cover-copy" data-reveal>
                <div class="volume-kicker"><span>WEBDEV REZA</span><strong>VOL. 01</strong></div>
                <span class="availability"><i aria-hidden="true"></i>{{ ($siteSettings['accepting_freelance'] ?? '1') === '1' ? 'AVAILABLE FOR FREELANCE' : 'CURRENTLY BUILDING' }}</span>
                <h1 id="cover-title">Membangun web.<br><em>Menyelesaikan masalah.</em></h1>
                <p class="cover-deck">{{ $siteSettings['short_bio'] ?? 'Halo, saya Reza. Saya membuat website, aplikasi, dan project teknologi yang berangkat dari kebutuhan nyata.' }}</p>
                <div class="actions">
                    <a class="manga-button primary" href="{{ route('projects.index') }}">VIEW PROJECTS <span>→</span></a>
                    <a class="manga-button" href="{{ route('about') }}">READ MY STORY</a>
                </div>
                <div class="issue-details"><span>STORY & CODE<br><strong>REZA</strong></span><span>BUILT WITH<br><strong>LARAVEL</strong></span><span>EDITION<br><strong>{{ date('Y') }}</strong></span></div>
            </div>
            <div class="cover-art" data-reveal>
                <x-anime-character />
                <div class="narrator-bubble">“Setiap project punya cerita. Ini volume pertamanya.”</div>
            </div>
        </div>
        <div class="cover-footer" aria-hidden="true"><span>SCROLL TO OPEN</span><i></i><span>読み始める</span></div>
    </section>

    <section class="manga-chapter chapter-about" id="chapter-01" data-reveal>
        <div class="container">
            <header class="chapter-header"><div class="chapter-number"><small>CHAPTER</small><strong>01</strong></div><div><span class="chapter-jp">自己紹介</span><h2>Who Am I?</h2><p>Tokoh utama, motivasi, dan cara saya bekerja.</p></div></header>
            <div class="about-panels">
                <article class="manga-panel clean-panel"><span class="panel-index">01-A</span><h3>Developer yang mulai dari masalah</h3><p>Saya Reza. Saya suka mengurai kebutuhan yang rumit menjadi produk web yang jelas, cepat, dan nyaman digunakan.</p><a class="ink-link" href="{{ route('about') }}">BACA CERITA LENGKAP →</a></article>
                <aside class="speech-panel"><div class="speech-bubble-manga">Teknologi seharusnya membantu manusia—bukan membuat hidup tambah rumit.</div><div class="narrator-caption">NARRATOR NOTE · Prinsip kerja Reza</div></aside>
                <article class="manga-panel stats-panel"><span class="panel-index">01-B</span><div><strong>{{ $projects->count() }}</strong><small>Featured projects</small></div><div><strong>{{ $posts->count() }}</strong><small>Latest dev logs</small></div><div><strong>∞</strong><small>Curiosity level</small></div></article>
            </div>
        </div>
    </section>

    <div class="ink-divider" aria-hidden="true"><span></span><b>TO BE CONTINUED</b><span></span></div>

    <section class="manga-chapter chapter-projects screentone" id="chapter-02" data-reveal>
        <div class="container">
            <header class="chapter-header inverse"><div class="chapter-number"><small>CHAPTER</small><strong>02</strong></div><div><span class="chapter-jp">作品集</span><h2>The Projects</h2><p>Project nyata, keputusan teknis, dan pelajaran di baliknya.</p></div><a class="manga-button light" href="{{ route('projects.index') }}">ALL PROJECTS →</a></header>
            <div class="grid project-grid manga-panel-grid">
                @forelse ($projects as $project)
                    @include('public.projects.card', ['project' => $project])
                @empty
                    <p class="empty">Panel project sedang digambar. Nantikan chapter berikutnya.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="manga-chapter chapter-logs" id="chapter-03" data-reveal>
        <div class="container">
            <header class="chapter-header"><div class="chapter-number"><small>CHAPTER</small><strong>03</strong></div><div><span class="chapter-jp">開発日誌</span><h2>Dev Logs</h2><p>Catatan proses, kegagalan kecil, dan hal yang layak dibagikan.</p></div><a class="manga-button" href="{{ route('blog.index') }}">ALL LOGS →</a></header>
            <div class="log-layout">
                <div class="grid log-grid">@forelse ($posts as $post) @include('public.blog.card', ['post' => $post]) @empty <p class="empty">Belum ada log yang diterbitkan.</p> @endforelse</div>
                <aside class="tools-panel"><span class="panel-index">LOADOUT</span><h3>Tools of the trade</h3><div class="ink-chips">@foreach ($technologies as $technology)<span>{{ $technology->name }}</span>@endforeach</div><p>Dipilih sesuai kebutuhan—bukan sekadar mengikuti tren.</p></aside>
            </div>
        </div>
    </section>

    <section class="manga-chapter chapter-contact" id="chapter-04" data-reveal>
        <div class="contact-speed-lines" aria-hidden="true"></div>
        <div class="container contact-panel"><div class="chapter-number light-number"><small>CHAPTER</small><strong>04</strong></div><div><span class="chapter-jp">連絡</span><h2>Let’s Build the Next Chapter.</h2><p>Punya ide, project freelance, atau ingin berkolaborasi? Ceritakan. Kita mulai dari percakapan yang sederhana.</p></div><div class="contact-action"><div class="speech-bubble-manga white-bubble">Siap bikin sesuatu yang bikin orang bilang, “bagus, bisa gitu ya?”</div><a class="manga-button accent" href="{{ route('contact') }}">START A CONVERSATION →</a></div></div>
    </section>
@endsection
