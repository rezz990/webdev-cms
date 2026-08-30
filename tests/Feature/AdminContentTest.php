

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
