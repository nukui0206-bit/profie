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
    <body class="d-flex flex-column min-vh-100">
        @include('layouts.navigation')

        @isset($header)
            <header class="pt-page-heading">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-grow-1 py-4">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        <footer class="border-top py-4 mt-auto" style="background: var(--pt-surface);">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-muted">
                <span>© {{ date('Y') }} Profie</span>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('terms') }}" class="text-muted text-decoration-none">利用規約</a>
                    <a href="{{ route('privacy') }}" class="text-muted text-decoration-none">プライバシー</a>
                    <a href="{{ route('contact') }}" class="text-muted text-decoration-none">お問い合わせ</a>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
