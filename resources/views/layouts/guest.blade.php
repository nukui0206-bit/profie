<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Profie') }}</title>
        <meta name="robots" content="noindex, nofollow, noarchive">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100 justify-content-center align-items-center py-5"
          style="background:
              radial-gradient(ellipse 700px 400px at 15% 10%, rgba(168, 85, 247, 0.10), transparent 60%),
              radial-gradient(ellipse 600px 400px at 85% 90%, rgba(236, 72, 153, 0.10), transparent 60%),
              var(--pt-bg);">
        <div class="mb-4">
            <a href="/" class="text-decoration-none">
                <x-application-logo />
            </a>
        </div>

        <div class="pt-auth-card w-100" style="max-width: 28rem;">
            {{ $slot }}
        </div>

        <div class="mt-4 small text-muted">
            <a href="{{ route('terms') }}" class="text-decoration-none me-3">利用規約</a>
            <a href="{{ route('privacy') }}" class="text-decoration-none me-3">プライバシー</a>
            <a href="{{ route('contact') }}" class="text-decoration-none">お問い合わせ</a>
        </div>
    </body>
</html>
