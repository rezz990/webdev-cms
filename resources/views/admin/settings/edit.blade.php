@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
    <div class="heading">
        <div>
            <span class="eyebrow">Profil publik</span>
            <h1>Pengaturan situs</h1>
        </div>
    </div>

    <form class="form" method="post" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('put')

        <div class="form-grid">
            <label>Nama website
                <input name="site_name" required value="{{ old('site_name', $settings['site_name'] ?? 'Webdev Reza') }}">
            </label>
            <label>Nama tampilan
                <input name="display_name" required value="{{ old('display_name', $settings['display_name'] ?? 'Reza') }}">
            </label>
        </div>

        <label>Headline
            <input name="headline" required value="{{ old('headline', $settings['headline'] ?? '') }}">
        </label>
        <label>Bio singkat
            <textarea name="short_bio" required>{{ old('short_bio', $settings['short_bio'] ?? '') }}</textarea>
        </label>
        <label>Bio panjang
            <textarea name="long_bio" rows="8">{{ old('long_bio', $settings['long_bio'] ?? '') }}</textarea>
        </label>
        <label class="check">
            <input type="checkbox" name="accepting_freelance" value="1" @checked(old('accepting_freelance', $settings['accepting_freelance'] ?? '0') === '1')>
            Sedang menerima freelance
        </label>

        <div class="form-grid">
            <label>WhatsApp internasional
                <input name="whatsapp" inputmode="numeric" required value="{{ old('whatsapp', $settings['whatsapp'] ?? '62895358302211') }}">
            </label>
            <label>Email publik
                <input type="email" name="public_email" required value="{{ old('public_email', $settings['public_email'] ?? '') }}">
            </label>
            @foreach (['github' => 'GitHub', 'github_legacy' => 'GitHub lama', 'instagram' => 'Instagram', 'telegram' => 'Telegram'] as $key => $label)
                <label>{{ $label }}
                    <input type="url" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}">
                </label>
            @endforeach
        </div>

        <label>SEO title default
            <input name="seo_title" required value="{{ old('seo_title', $settings['seo_title'] ?? '') }}">
        </label>
        <label>SEO description default
            <textarea name="seo_description" required>{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
        </label>

        <button class="button">Simpan pengaturan</button>
    </form>
@endsection
