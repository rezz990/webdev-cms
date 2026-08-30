<?php

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


it('publishes a newly created post immediately when no date is supplied', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.posts.store'), [
        'title' => 'Tulisan langsung tayang',
        'slug' => 'tulisan-langsung-tayang',
        'excerpt' => 'Ringkasan tulisan yang jelas.',
        'content' => 'Isi tulisan yang siap dibaca.',
        'status' => 'published',
        'new_category' => 'Catatan Backend',
        'new_tags' => 'Laravel, production',
    ]);

    $post = Post::query()->where('slug', 'tulisan-langsung-tayang')->firstOrFail();
    $response->assertRedirect(route('admin.posts.edit', $post))->assertSessionHas('public_url');
    expect($post->published_at)->not->toBeNull();
    expect($post->category?->name)->toBe('Catatan Backend')
        ->and($post->tags()->count())->toBe(2);
    $this->get(route('blog.index'))->assertSee($post->title);
    $this->get(route('blog.show', $post))->assertOk()->assertSee($post->title);
});

it('updates a post and exposes an admin-only preview for drafts', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $post = Post::factory()->create();

    $this->actingAs($admin)->put(route('admin.posts.update', $post), [
        'title' => 'Tulisan diperbarui',
        'slug' => $post->slug,
        'excerpt' => 'Ringkasan tulisan yang jelas.',
        'content' => 'Isi tulisan baru',
        'status' => 'draft',
    ])->assertRedirect(route('admin.posts.edit', $post));

    $this->actingAs($admin)->get(route('admin.posts.preview', $post))->assertOk()->assertSee('Mode preview admin');
    $this->get(route('admin.posts.preview', $post))->assertRedirect(route('admin.login'));
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Tulisan diperbarui']);
});

it('publishes a new project immediately and shows it publicly', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
        'name' => 'Project langsung tayang',
        'slug' => 'project-langsung-tayang',
        'summary' => 'Ringkasan project uji.',
        'content' => '## Masalah\n\nStudi kasus lengkap.',
        'status' => 'published',
        'project_status' => 'Selesai',
        'year' => 2026,
        'new_category' => 'Eksperimen Web',
        'new_technologies' => 'Laravel, Alpine.js',
    ]);

    $project = Project::query()->where('slug', 'project-langsung-tayang')->firstOrFail();
    $response->assertRedirect(route('admin.projects.edit', $project))->assertSessionHas('public_url');
    expect($project->published_at)->not->toBeNull();
    expect($project->category?->name)->toBe('Eksperimen Web')
        ->and($project->technologies()->count())->toBe(2);
    $this->get(route('projects.index'))->assertSee($project->name);
    $this->get(route('projects.show', $project))->assertOk()->assertSee('Studi kasus lengkap');
});

it('rejects an invalid uploaded cover', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $file = UploadedFile::fake()->create('payload.php', 10, 'application/x-php');

    $this->actingAs($admin)->post(route('admin.posts.store'), [
        'title' => 'Upload uji',
        'slug' => 'upload-uji',
        'excerpt' => 'Ringkasan',
        'content' => 'Konten',
        'status' => 'draft',
        'cover_image' => $file,
    ])->assertSessionHasErrors('cover_image');

    Storage::disk('public')->assertMissing('posts/'.$file->hashName());
});


it('publishes a scheduled project when its publication time arrives', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->post(route('admin.projects.store'), [
        'name' => 'Project terjadwal',
        'slug' => 'project-terjadwal',
        'summary' => 'Ringkasan project terjadwal.',
        'content' => 'Studi kasus project terjadwal.',
        'status' => 'scheduled',
        'published_at' => now()->addDay()->format('Y-m-d H:i:s'),
        'project_status' => 'Selesai',
        'year' => 2026,
    ])->assertRedirect();

    $project = Project::query()->where('slug', 'project-terjadwal')->firstOrFail();
    $this->get(route('projects.show', $project))->assertNotFound();

    $this->travel(2)->days();
    $this->get(route('projects.show', $project))->assertOk()->assertSee($project->name);
});
