<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#080a14">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <title>@yield('title', 'Masuk') — Reza CMS</title>
    @vite(['resources/css/app.css', 'resources/css/admin-dark.css', 'resources/js/app.js'])
</head>
<body class="auth-shell">
    <a class="skip" href="#auth-content">Lewati ke konten</a>
    <main id="auth-content">@yield('content')</main>
</body>
</html>
