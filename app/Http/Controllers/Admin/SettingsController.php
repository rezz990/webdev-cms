<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveSettingsRequest;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settings) {}

    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => $this->settings->all()]);
    }

    public function update(SaveSettingsRequest $request): RedirectResponse
    {
        $values = $request->validated();
        $values['accepting_freelance'] = $request->boolean('accepting_freelance') ? '1' : '0';

        foreach ($values as $key => $value) {
            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'profile'],
            );
        }

        $this->settings->forget();
        ActivityLog::query()->create([
            'user_id' => $request->user()->id,
            'action' => 'settings.updated',
        ]);

        return back()->with('success', 'Profil dan pengaturan diperbarui.');
    }
}
