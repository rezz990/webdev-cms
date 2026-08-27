<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SettingsService
{
    /** @return array<string, string|null> */
    public function all(): array
    {
        if (! Schema::hasTable('settings')) {
            return [];
        }

        return Cache::remember(
            'site.settings',
            now()->addHour(),
            fn (): array => Setting::query()->pluck('value', 'key')->all(),
        );
    }

    public function forget(): void
    {
        Cache::forget('site.settings');
    }
}
