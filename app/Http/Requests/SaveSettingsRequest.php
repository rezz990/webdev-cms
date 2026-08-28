<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:100'],
            'display_name' => ['required', 'string', 'max:100'],
            'headline' => ['required', 'string', 'max:160'],
            'short_bio' => ['required', 'string', 'max:500'],
            'long_bio' => ['nullable', 'string', 'max:5000'],
            'accepting_freelance' => ['boolean'],
            'whatsapp' => ['required', 'regex:/^[1-9][0-9]{7,14}$/'],
            'public_email' => ['required', 'email:rfc', 'max:255'],
            'github' => ['nullable', 'url:http,https'],
            'github_legacy' => ['nullable', 'url:http,https'],
            'instagram' => ['nullable', 'url:http,https'],
            'telegram' => ['nullable', 'url:http,https'],
            'seo_title' => ['required', 'string', 'max:70'],
            'seo_description' => ['required', 'string', 'max:160'],
        ];
    }
}
