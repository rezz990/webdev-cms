@extends('layouts.admin')

@section('title', 'Media')

@section('content')
    <div class="heading"><h1>Media library</h1></div>

    @error('media')<div class="error">{{ $message }}</div>@enderror
    <form class="form media-upload" method="post" enctype="multipart/form-data" action="{{ route('admin.media.store') }}">
        @csrf
        <label>Gambar JPG, PNG, atau WebP (maks. 4 MB)
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
        </label>
        <label>Alt text
            <input name="alt_text" value="{{ old('alt_text') }}" maxlength="255">
        </label>
        <button class="button">Unggah gambar</button>
    </form>

    <div class="media-grid">
        @forelse ($media as $medium)
            <article class="media-card">
                <img src="{{ Storage::disk($medium->disk)->url($medium->path) }}" alt="{{ $medium->alt_text ?: '' }}" loading="lazy">
                <div>
                    <strong>{{ $medium->original_name }}</strong>
                    <small>{{ Number::fileSize($medium->size) }} · {{ $medium->created_at->translatedFormat('d M Y') }}</small>
                    <code>{{ $medium->path }}</code>
                    <form method="post" action="{{ route('admin.media.destroy', $medium) }}" onsubmit="return confirm('Hapus gambar ini?')">
                        @csrf @method('delete')
                        <button>Hapus</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="empty">Belum ada media.</p>
        @endforelse
    </div>
    {{ $media->links() }}
@endsection
