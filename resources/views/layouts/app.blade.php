<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Profie') }}</title>
        <meta name="robots" content="noindex, nofollow, noarchive">
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#6366f1">

        {{-- OGP / Twitter Card（共通デフォルト）--}}
        <meta property="og:type" content="website">
        <meta property="og:title" content="Profie — あなたのすべてを、ひとつのページに。">
        <meta property="og:description" content="ニックネーム・自己紹介・好きなもの・推し・SNS リンクを 1 ページにまとめて公開できる、あたらしい自己紹介ページサービス。">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:image" content="{{ url('/og-image.png') }}">
        <meta property="og:site_name" content="Profie">
        <meta property="og:locale" content="ja_JP">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ url('/og-image.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <x-google-analytics />
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
