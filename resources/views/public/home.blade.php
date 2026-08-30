@extends('layouts.public')

@section('content')
    <section class="night-hero" aria-labelledby="hero-title">
        <div class="container">
            <div class="hero-content" data-reveal>
                <span class="availability-modern"><i aria-hidden="true"></i>{{ ($siteSettings['accepting_freelance'] ?? '1') === '1' ? 'Tersedia untuk project freelance' : 'Sedang membangun sesuatu' }}</span>
                <h1 id="hero-title">Membangun produk digital yang <span>benar-benar dipakai.</span></h1>
                <p class="hero-intro">{{ $siteSettings['short_bio'] ?? 'Halo, saya Reza. Saya membangun website dan aplikasi dari kebutuhan nyata—dengan fokus pada pengalaman pengguna, performa, dan solusi yang mudah dirawat.' }}</p>
                <div class="hero-actions">
                    <a class="modern-button primary" href="{{ route('projects.index') }}">Lihat project <span aria-hidden="true"></span></a>
                    <a class="modern-button" href="{{ route('contact') }}">Mulai percakapan</a>
                </div>
                <div class="hero-proof" aria-label="Fokus pengembangan">
                    <span>Web application</span><span>Android experience</span><span>Developer tools</span>
                </div>
            </div>
        </div>
        <span class="scroll-hint" aria-hidden="true">Jelajahi karya</span>
    </section>

    <section class="dark-section" data-reveal>
        <div class="container">
            <header class="section-heading">
                <div><span class="section-label">Project pilihan</span><h2>Dibangun dari masalah nyata.</h2><p>Bukan sekadar eksperimen visual—setiap project berangkat dari kebutuhan, keputusan teknis, dan proses yang bisa dipelajari.</p></div>
                <a class="text-link-modern" href="{{ route('projects.index') }}">Semua project </a>
            </header>
            <div class="grid project-grid">
                @forelse ($projects as $project)
                    @include('public.projects.card', ['project' => $project])
                @empty
                    <p class="empty-dark">Belum ada project yang diterbitkan.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="dark-section alt" data-reveal>
        <div class="container about-modern">
            <div>
                <span class="section-label">Tentang Reza</span>
                <h2>Teknologi harus membuat pekerjaan lebih sederhana.</h2>
                <p class="about-copy">Saya suka mengurai kebutuhan yang rumit menjadi produk yang jelas, cepat, dan nyaman digunakan. Stack dipilih berdasarkan masalah yang diselesaikan—bukan sekadar mengikuti tren.</p>
                <div class="tech-list">@foreach ($technologies as $technology)<span>{{ $technology->name }}</span>@endforeach</div>
                <div class="hero-actions"><a class="modern-button" href="{{ route('about') }}">Selengkapnya tentang saya </a></div>
            </div>
            <div class="about-facts">
                <div><strong>{{ $projects->count() }}</strong><span>Project pilihan</span></div>
                <div><strong>{{ $posts->count() }}</strong><span>Catatan terbaru</span></div>
                <div><strong>Mobile</strong><span>First experience</span></div>
                <div><strong>Real</strong><span>Problem driven</span></div>
            </div>
        </div>
    </section>

    <section class="dark-section" data-reveal>
        <div class="container">
            <header class="section-heading">
                <div><span class="section-label">Dev journal</span><h2>Catatan dari proses membangun.</h2><p>Keputusan, kegagalan kecil, dan temuan teknis yang layak dibagikan.</p></div>
                <a class="text-link-modern" href="{{ route('blog.index') }}">Semua tulisan </a>
            </header>
            <div class="journal-layout">
                @forelse ($posts as $post)
                    @include('public.blog.card', ['post' => $post])
                @empty
                    <p class="empty-dark">Belum ada tulisan yang diterbitkan.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="dark-section" data-reveal>
        <div class="container">
            <div class="contact-modern">
                <span class="section-label">Punya sesuatu untuk dibangun?</span>
                <h2>Mari ubah kebutuhan menjadi produk yang bekerja.</h2>
                <p>Ceritakan project, masalah, atau ide lu. Kita mulai dari percakapan sederhana dan menentukan solusi yang paling masuk akal.</p>
                <div class="hero-actions"><a class="modern-button primary" href="{{ route('contact') }}">Hubungi saya </a></div>
            </div>
        </div>
    </section>
@endsection
