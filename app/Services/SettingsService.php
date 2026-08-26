<?php
namespace App\Services;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
class SettingsService { public function all(): array { return Cache::remember('site.settings', 3600, fn (): array => Setting::pluck('value','key')->all()); } public function forget(): void { Cache::forget('site.settings'); } }
