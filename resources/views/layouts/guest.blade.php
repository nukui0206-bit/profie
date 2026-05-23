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
