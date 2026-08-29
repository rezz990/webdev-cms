@extends('layouts.admin')

@section('title', $post->exists ? 'Edit tulisan' : 'Tulisan baru')

@section('content')
    <form class="studio-form" enctype="multipart/form-data" method="post" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" data-content-form>
        @csrf
        @if ($post->exists) @method('put') @endif

        <header class="studio-header">
            <div>
                <a class="back-link" href="{{ route('admin.posts.index') }}">← Kembali ke tulisan</a>
                <span class="eyebrow">Writing studio</span>
                <h1>{{ $post->exists ? 'Edit tulisan' : 'Tulis cerita baru' }}</h1>
                <p>Susun ide, lihat preview, lalu terbitkan saat sudah siap.</p>
            </div>
            <div class="studio-header-actions">
                @if ($post->exists)
                    <a class="button ghost" href="{{ route('admin.posts.preview', $post) }}" target="_blank">Preview penuh ↗</a>
                @endif
                <button class="button ghost" type="submit">Simpan sesuai status</button>
                <button class="button" type="submit" data-submit-status="published">Terbitkan sekarang</button>
            </div>
        </header>

        <div class="studio-layout">
            <section class="studio-main">
                <div class="studio-panel title-panel">
                    <label class="field-label" for="title">Judul tulisan</label>
                    <input class="title-input" id="title" name="title" required maxlength="255" value="{{ old('title', $post->title) }}" placeholder="Judul yang membuat orang ingin membaca…" data-title-input>
                    @error('title')<small class="error">{{ $message }}</small>@enderror
                    <div class="slug-row"><span>{{ url('/blog') }}/</span><input id="slug" name="slug" required value="{{ old('slug', $post->slug) }}" data-slug-input><button type="button" data-regenerate-slug>Buat ulang</button></div>
                    @error('slug')<small class="error">{{ $message }}</small>@enderror
                </div>

                <div class="studio-panel editor-panel">
                    <div class="editor-tabs" role="tablist">
                        <button class="active" type="button" role="tab" aria-selected="true" data-editor-tab="write">✎ Tulis</button>
                        <button type="button" role="tab" aria-selected="false" data-editor-tab="preview">◉ Preview</button>
                        <span class="word-count" data-word-count>0 kata · 1 menit baca</span>
                    </div>
                    <div class="markdown-toolbar" aria-label="Alat pemformatan">
                        <button type="button" data-markdown="heading" title="Heading">H2</button>
                        <button type="button" data-markdown="bold" title="Tebal"><strong>B</strong></button>
                        <button type="button" data-markdown="italic" title="Miring"><em>I</em></button>
                        <button type="button" data-markdown="link" title="Tautan">↗ Link</button>
                        <button type="button" data-markdown="quote" title="Kutipan">❝</button>
                        <button type="button" data-markdown="list" title="Daftar">☷ List</button>
                        <button type="button" data-markdown="code" title="Kode">&lt;/&gt;</button>
                        <button type="button" data-markdown="image" title="Gambar">▧ Image</button>
                    </div>
                    <textarea class="content-editor" id="content" name="content" rows="24" required data-markdown-editor placeholder="# Mulai ceritanya di sini…">{{ old('content', $post->content) }}</textarea>
                    <div class="markdown-preview prose" data-markdown-preview hidden></div>
                    @error('content')<small class="error">{{ $message }}</small>@enderror
                </div>

                <div class="studio-panel">
                    <label class="field-label" for="excerpt">Ringkasan</label>
                    <p class="field-help">Ditampilkan pada kartu tulisan dan hasil pencarian.</p>
                    <textarea id="excerpt" name="excerpt" rows="4" required maxlength="1000" data-character-input>{{ old('excerpt', $post->excerpt) }}</textarea>
                    <small class="character-count"><span data-character-count>0</span>/1000 karakter</small>
                </div>

                <details class="studio-panel seo-panel" @if(old('seo_title', $post->seo_title)) open @endif>
                    <summary><span>SEO & tampilan Google</span><small>Opsional, tetapi direkomendasikan</small></summary>
                    <div class="seo-fields">
                        <label>SEO title<input name="seo_title" maxlength="70" value="{{ old('seo_title', $post->seo_title) }}"></label>
                        <label>Meta description<textarea name="seo_description" maxlength="160" rows="3">{{ old('seo_description', $post->seo_description) }}</textarea></label>
                    </div>
                </details>
            </section>

            <aside class="studio-sidebar">
                <section class="studio-panel publish-panel">
                    <div class="panel-title"><h2>Publikasi</h2><span class="status-dot"></span></div>
                    <div class="status-options">
                        @foreach ([
                            'draft' => ['Draft', 'Hanya terlihat oleh admin'],
                            'published' => ['Published', 'Langsung tampil di website'],
                            'scheduled' => ['Scheduled', 'Tayang sesuai jadwal'],
                            'archived' => ['Archived', 'Disimpan, tidak publik'],
                        ] as $value => [$label, $help])
                            <label><input type="radio" name="status" value="{{ $value }}" @checked(old('status', $post->status?->value ?? 'draft') === $value)><span><strong>{{ $label }}</strong><small>{{ $help }}</small></span></label>
                        @endforeach
                    </div>
                    <label>Waktu publikasi<input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"></label>
                    <p class="publish-hint">Jika memilih Published dan waktu dikosongkan, tulisan akan tampil segera setelah disimpan.</p>
                    <label class="check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))> Jadikan tulisan unggulan</label>
                </section>

                <section class="studio-panel">
                    <h2>Organisasi</h2>
                    <label>Kategori<select name="category_id"><option value="">Tanpa kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></label><label>Kategori baru (opsional)<input name="new_category" value="{{ old('new_category') }}" placeholder="Contoh: Tutorial Laravel"></label>
                    <fieldset class="option-fieldset"><legend>Tag</legend><div class="option-chips">@forelse ($tags as $tag)<label><input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', $post->exists ? $post->tags->pluck('id')->all() : [])))><span>#{{ $tag->name }}</span></label>@empty<small>Belum ada tag. Tambahkan melalui database/seeder.</small>@endforelse</div><label>Tag baru, pisahkan dengan koma<input name="new_tags" value="{{ old('new_tags') }}" placeholder="performance, cPanel, belajar"></label></fieldset>
                </section>

                <section class="studio-panel cover-panel">
                    <h2>Cover tulisan</h2>
                    @if ($post->cover_image)<img src="{{ asset('storage/'.$post->cover_image) }}" alt="Cover saat ini" data-cover-preview>@else<div class="cover-placeholder" data-cover-placeholder><span>▧</span><strong>Belum ada cover</strong></div><img alt="Preview cover" data-cover-preview hidden>@endif
                    <label class="file-button">Pilih gambar<input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" data-cover-input></label>
                    <small>JPG, PNG, atau WebP · maksimal 4 MB.</small>
                </section>
            </aside>
        </div>
    </form>
@endsection
