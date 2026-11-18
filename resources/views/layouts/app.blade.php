<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <title>@yield('title', 'Login') — {{ setting('nama_situs_short', 'Bappedalitbang') }} Kabupaten Karo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="auth-glass-body">
    <div class="auth-glass-bg" aria-hidden="true">
        <span class="auth-blob" style="top:12%;left:10%"></span>
        <span class="auth-blob" style="top:70%;right:8%"></span>
        <span class="auth-blob" style="bottom:6%;left:45%"></span>
    </div>

    <div class="auth-wrapper">
        <a href="{{ url('/') }}" class="auth-brand">
            <div class="auth-logo">
                <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19.5 3.5L18 2l-1.5 1.5L15 2l-1.5 1.5L12 2l-1.5 1.5L9 2 7.5 3.5 6 2 4.5 3.5 3 2v20l1.5-1.5L6 22l1.5-1.5L9 22l1.5-1.5L12 22l1.5-1.5L15 22l1.5-1.5L18 22l1.5-1.5L21 22V2l-1.5 1.5zM19 19.09H5V4.91h14v14.18z"/>
                    <path d="M7 7h10v2H7zm0 4h10v2H7zm0 4h7v2H7z"/>
                </svg>
            </div>
            <div class="auth-brand-text">
                <div class="auth-brand-title">{{ setting('nama_situs_short', 'Bappedalitbang') }}</div>
                <div class="auth-brand-sub">{{ setting('nama_daerah', 'Kabupaten Karo') }}</div>
            </div>
        </a>

        <div class="auth-card">
            @yield('content')
        </div>
    </div>
    @stack('scripts')
</body>
</html>