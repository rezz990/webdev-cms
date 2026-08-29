<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePostRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private ImageUploadService $images) {}

    public function index(Request $request): View
    {
        $posts = Post::query()
            ->with('category')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return $this->formView(new Post);
    }

    public function store(SavePostRequest $request): RedirectResponse
    {
        $post = DB::transaction(function () use ($request): Post {
            $data = $this->postData($request);
            if ($request->filled('new_category')) {
                $category = Category::query()->firstOrCreate(
                    ['slug' => Str::slug($request->string('new_category')->toString()).'-post'],
                    ['name' => $request->string('new_category')->toString(), 'type' => 'post'],
                );
                $data['category_id'] = $category->id;
            }
            $data['user_id'] = $request->user()->id;
            $post = Post::query()->create($data);
            $post->tags()->sync($this->tagIds($request));

            ActivityLog::query()->create([
                'user_id' => $request->user()->id,
                'action' => 'post.created',
                'subject_type' => Post::class,
                'subject_id' => $post->id,
            ]);

            return $post;
        });

        return $this->savedResponse($post, 'Tulisan berhasil dibuat dan status publikasinya sudah diterapkan.');
    }

    public function edit(Post $post): View
    {
        return $this->formView($post->load('tags'));
    }

    public function update(SavePostRequest $request, Post $post): RedirectResponse
    {
        DB::transaction(function () use ($request, $post): void {
            $data = $this->postData($request);
            if ($request->filled('new_category')) {
                $category = Category::query()->firstOrCreate(
                    ['slug' => Str::slug($request->string('new_category')->toString()).'-post'],
                    ['name' => $request->string('new_category')->toString(), 'type' => 'post'],
                );
                $data['category_id'] = $category->id;
            }
            $post->update($data);
            $post->tags()->sync($this->tagIds($request));

            ActivityLog::query()->create([
                'user_id' => $request->user()->id,
                'action' => 'post.updated',
                'subject_type' => Post::class,
                'subject_id' => $post->id,
            ]);
        });

        return $this->savedResponse($post->fresh(), 'Tulisan berhasil diperbarui.');
    }

    public function preview(Post $post): View
    {
        $post->load(['author', 'category', 'tags']);

        return view('public.blog.show', [
            'post' => $post,
            'related' => collect(),
            'isPreview' => true,
        ]);
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return back()->with('success', 'Tulisan dipindahkan ke sampah.');
    }

    private function formView(Post $post): View
    {
        return view('admin.posts.form', [
            'post' => $post,
            'categories' => Category::query()->where('type', 'post')->orderBy('name')->get(),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    /** @return array<string, mixed> */
    private function postData(SavePostRequest $request): array
    {
        $data = $request->safe()->except(['cover_image', 'tags', 'new_tags', 'new_category']);

        if ($data['status'] === ContentStatus::Published->value && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->images->store($request->file('cover_image'), 'posts');
        }

        return $data;
    }

    /** @return array<int, int> */
    private function tagIds(SavePostRequest $request): array
    {
        $tagIds = collect($request->input('tags', []));
        $names = Str::of($request->string('new_tags')->toString())->explode(',')->map->trim()->filter();

        foreach ($names as $name) {
            $tagIds->push(Tag::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            )->id);
        }

        return $tagIds->unique()->values()->all();
    }

    private function savedResponse(Post $post, string $message): RedirectResponse
    {
        $isPublic = in_array($post->status, [ContentStatus::Published, ContentStatus::Scheduled], true)
            && $post->published_at?->isPast();

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', $message)
            ->with('public_url', $isPublic ? route('blog.show', $post) : null);
    }
}
