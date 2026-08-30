@extends('layouts.admin')

@section('title', $project->exists ? 'Edit project' : 'Project baru')

@section('content')
    <form class="studio-form" enctype="multipart/form-data" method="post" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" data-content-form>
        @csrf
        @if ($project->exists) @method('put') @endif

        <header class="studio-header">
            <div><a class="back-link" href="{{ route('admin.projects.index') }}"> Kembali ke project</a><span class="eyebrow">Project forge</span><h1>{{ $project->exists ? 'Edit project' : 'Tempa project baru' }}</h1><p>Rakit studi kasus yang jelas dari masalah sampai hasil.</p></div>
            <div class="studio-header-actions">
                @if ($project->exists)<a class="button ghost" href="{{ route('admin.projects.preview', $project) }}" target="_blank">Preview penuh </a>@endif
                <button class="button ghost" type="submit">Simpan sesuai status</button>
                <button class="button" type="submit" data-submit-status="published">Terbitkan project</button>
            </div>
        </header>

        <div class="studio-layout">
            <section class="studio-main">
                <div class="studio-panel title-panel">
                    <label class="field-label" for="name">Nama project</label>
                    <input class="title-input" id="name" name="name" required value="{{ old('name', $project->name) }}" placeholder="Nama karya terbaikmu…" data-title-input>
                    <div class="slug-row"><span>{{ url('/projects') }}/</span><input name="slug" required value="{{ old('slug', $project->slug) }}" data-slug-input><button type="button" data-regenerate-slug>Buat ulang</button></div>
                </div>

                <div class="studio-panel"><label class="field-label" for="summary">Ringkasan project</label><p class="field-help">Jelaskan nilai utama project dalam 1–3 kalimat.</p><textarea id="summary" name="summary" rows="4" required maxlength="1200" data-character-input>{{ old('summary', $project->summary) }}</textarea><small class="character-count"><span data-character-count>0</span>/1200 karakter</small></div>

                <div class="studio-panel editor-panel">
                    <div class="editor-tabs" role="tablist"><button class="active" type="button" role="tab" aria-selected="true" data-editor-tab="write"> Tulis studi kasus</button><button type="button" role="tab" aria-selected="false" data-editor-tab="preview"> Preview</button><span class="word-count" data-word-count>0 kata</span></div>
                    <div class="markdown-toolbar"><button type="button" data-markdown="heading">H2</button><button type="button" data-markdown="bold"><strong>B</strong></button><button type="button" data-markdown="italic"><em>I</em></button><button type="button" data-markdown="link"> Link</button><button type="button" data-markdown="quote"></button><button type="button" data-markdown="list"> List</button><button type="button" data-markdown="code">&lt;/&gt;</button></div>
                    <textarea class="content-editor" name="content" rows="26" required data-markdown-editor>{{ old('content', $project->content ?: "## Masalah yang ingin diselesaikan\n\nJelaskan konteks dan kebutuhan awal.\n\n## Solusi yang dibuat\n\nJelaskan pendekatan dan keputusan utama.\n\n## Fitur utama\n\n- Fitur pertama\n- Fitur kedua\n\n## Tantangan pengembangan\n\nCeritakan tantangan dan bagaimana kamu menyelesaikannya.\n\n## Hasil dan pelajaran\n\nApa dampak dan pelajaran dari project ini?") }}</textarea>
                    <div class="markdown-preview prose" data-markdown-preview hidden></div>
                </div>

                <details class="studio-panel seo-panel"><summary><span>SEO & social preview</span><small>Opsional</small></summary><div class="seo-fields"><label>SEO title<input name="seo_title" maxlength="70" value="{{ old('seo_title', $project->seo_title) }}"></label><label>Meta description<textarea name="seo_description" maxlength="160" rows="3">{{ old('seo_description', $project->seo_description) }}</textarea></label></div></details>
            </section>

            <aside class="studio-sidebar">
                <section class="studio-panel publish-panel">
                    <div class="panel-title"><h2>Publikasi</h2><span class="status-dot"></span></div>
                    <div class="status-options">@foreach (['draft'=>['Draft','Belum tampil di website'],'published'=>['Published','Tampil setelah disimpan'],'scheduled'=>['Scheduled','Tayang sesuai waktu publikasi'],'archived'=>['Archived','Tidak ditampilkan']] as $value => [$label,$help])<label><input type="radio" name="status" value="{{ $value }}" @checked(old('status', $project->status?->value ?? 'draft') === $value)><span><strong>{{ $label }}</strong><small>{{ $help }}</small></span></label>@endforeach</div>
                    <label>Waktu publikasi<input type="datetime-local" name="published_at" value="{{ old('published_at', $project->published_at?->format('Y-m-d\TH:i')) }}"></label>
                    <p class="publish-hint">Published tanpa waktu akan langsung tampil di halaman Project.</p>
                    <label class="check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))> Tampilkan di beranda</label>
                    <label>Urutan tampil<input type="number" name="sort_order" min="0" value="{{ old('sort_order', $project->sort_order ?? 0) }}"></label>
                </section>

                <section class="studio-panel"><h2>Detail project</h2><label>Kategori<select name="category_id"><option value="">Tanpa kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $project->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></label><label>Kategori baru (opsional)<input name="new_category" value="{{ old('new_category') }}" placeholder="Contoh: Open Source"></label><div class="form-grid"><label>Tahun<input type="number" name="year" min="2000" max="2100" required value="{{ old('year', $project->year ?: date('Y')) }}"></label><label>Status pengerjaan<input name="project_status" required value="{{ old('project_status', $project->project_status ?: 'Selesai') }}"></label></div><label>Peran Reza<input name="role" value="{{ old('role', $project->role) }}" placeholder="Full-stack developer"></label><label>URL demo<input type="url" name="demo_url" value="{{ old('demo_url', $project->demo_url) }}" placeholder="https://"></label><label>URL repository<input type="url" name="repository_url" value="{{ old('repository_url', $project->repository_url) }}" placeholder="https://github.com/…"></label></section>

                <section class="studio-panel"><fieldset class="option-fieldset"><legend>Teknologi</legend><div class="option-chips">@foreach ($technologies as $technology)<label><input type="checkbox" name="technologies[]" value="{{ $technology->id }}" @checked(in_array($technology->id, old('technologies', $project->exists ? $project->technologies->pluck('id')->all() : [])))><span>{{ $technology->name }}</span></label>@endforeach</div><label>Teknologi baru, pisahkan dengan koma<input name="new_technologies" value="{{ old('new_technologies') }}" placeholder="Redis, Meilisearch"></label></fieldset></section>

                <section class="studio-panel cover-panel"><h2>Cover project</h2>@if ($project->cover_image)<img src="{{ asset('storage/'.$project->cover_image) }}" alt="Cover saat ini" data-cover-preview>@else<div class="cover-placeholder" data-cover-placeholder><span></span><strong>Belum ada cover</strong></div><img alt="Preview cover" data-cover-preview hidden>@endif<label class="file-button">Pilih gambar<input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" data-cover-input></label><small>Rekomendasi rasio 16:10, maksimal 4 MB.</small></section>
            </aside>
        </div>
    </form>
@endsection
