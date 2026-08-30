

it('filters unread contact messages in the admin inbox', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    ContactMessage::factory()->create(['name' => 'Pesan Baru', 'read_at' => null]);
    ContactMessage::factory()->create(['name' => 'Pesan Lama', 'read_at' => now()]);

    $this->actingAs($admin)
        ->get(route('admin.messages.index', ['status' => 'unread']))
        ->assertOk()
        ->assertSeeText('Pesan Baru')
        ->assertDontSeeText('Pesan Lama');
});
