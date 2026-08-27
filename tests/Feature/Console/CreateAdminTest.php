<?php

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(LazilyRefreshDatabase::class);

it('creates an administrator through the guided command', function () {
    $this->artisan('admin:create', ['--name' => 'Reza', '--email' => 'admin@example.com'])
        ->expectsQuestion('Kata sandi (minimal 12 karakter)', 'rahasia-kuat-123')
        ->expectsOutputToContain('Admin siap')
        ->assertSuccessful();

    $admin = \App\Models\User::query()->where('email', 'admin@example.com')->firstOrFail();

    expect($admin->is_admin)->toBeTrue()
        ->and(Hash::check('rahasia-kuat-123', $admin->password))->toBeTrue();
});
