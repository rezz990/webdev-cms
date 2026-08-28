<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadMediaRequest;
use App\Models\MediaAsset;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(private ImageUploadService $images) {}

    public function index(): View
    {
        return view('admin.media.index', [
            'media' => MediaAsset::query()->latest()->paginate(24),
        ]);
    }

    public function store(UploadMediaRequest $request): RedirectResponse
    {
        $image = $request->file('image');
        $path = $this->images->store($image, 'media');

        MediaAsset::query()->create([
            'uploaded_by' => $request->user()->id,
            'path' => $path,
            'original_name' => $image->getClientOriginalName(),
            'mime_type' => $image->getMimeType(),
            'size' => $image->getSize(),
            'alt_text' => $request->string('alt_text')->toString(),
        ]);

        return back()->with('success', 'Gambar berhasil diunggah.');
    }

    public function destroy(MediaAsset $medium): RedirectResponse
    {
        $isUsed = Post::query()->where('cover_image', $medium->path)->exists()
            || Project::query()->where('cover_image', $medium->path)->exists()
            || ProjectImage::query()->where('path', $medium->path)->exists();

        if ($isUsed) {
            return back()->withErrors(['media' => 'Gambar masih digunakan dan tidak dapat dihapus.']);
        }

        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();

        return back()->with('success', 'Gambar dihapus.');
    }
}
