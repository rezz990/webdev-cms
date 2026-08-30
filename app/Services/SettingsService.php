<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\QueryException;\nuse Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SettingsService
{
    /** @return array<string, string|null> */
    public function all(): array
    {
        try {
            if (! Schema::hasTable('settings')) {
                return [];
            }

            return Cache::remember(
                'site.settings',
                now()->addHour(),
                fn (): array => Setting::query()->pluck('value', 'key')->all(),
            );
        } catch (QueryException) {
            return [];
        }
    }

    public function forget(): void
    {
        Cache::forget('site.settings');
    }
}
