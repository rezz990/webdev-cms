<?php

use App\Models\ContactMessage;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(LazilyRefreshDatabase::class);

it('lets an admin update public settings without accepting sensitive keys', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'site_name' => 'Webdev Reza',
        'display_name' => 'Reza',
        'headline' => 'Developer web',
        'short_bio' => 'Membuat solusi web dari kebutuhan nyata.',
        'long_bio' => 'Cerita perjalanan Reza.',
        'accepting_freelance' => true,
        'whatsapp' => '62895358302211',
        'public_email' => 'halo@example.com',
        'github' => 'https://github.com/reza',
        'seo_title' => 'Webdev Reza',
        'seo_description' => 'Portfolio dan tulisan Reza.',
        'APP_KEY' => 'must-not-be-stored',
    ]);

    $response->assertRedirect()->assertSessionHas('success');
    $this->assertDatabaseHas('settings', ['key' => 'whatsapp', 'value' => '62895358302211']);
    $this->assertDatabaseMissing('settings', ['key' => 'APP_KEY']);
    $this->assertDatabaseHas('activity_logs', ['action' => 'settings.updated', 'user_id' => $admin->id]);
});

it('uploads safe media and prevents deleting media in use', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->post(route('admin.media.store'), [
        'image' => UploadedFile::fake()->image('cover.jpg', 800, 500),
        'alt_text' => 'Cover project',
    ])->assertRedirect()->assertSessionHas('success');

    $medium = MediaAsset::query()->firstOrFail();
    Storage::disk('public')->assertExists($medium->path);

    \App\Models\Project::factory()->create(['cover_image' => $medium->path]);
    $this->actingAs($admin)->delete(route('admin.media.destroy', $medium))->assertSessionHasErrors('media');
    Storage::disk('public')->assertExists($medium->path);
});

it('marks a contact message as read when an admin opens it', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $message = ContactMessage::query()->create([
        'name' => 'Ayu',
        'email' => 'ayu@example.com',
        'message' => 'Saya ingin mendiskusikan project.',
    ]);

    $this->actingAs($admin)->get(route('admin.messages.show', $message))->assertOk()->assertSeeText($message->message);

    expect($message->fresh()->read_at)->not->toBeNull();
});
