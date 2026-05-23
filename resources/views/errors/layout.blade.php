@php
    $code = $code ?? 'Error';
    $title = $title ?? 'エラーが発生しました';
    $message = $message ?? '申し訳ありません。';
    $subMessage = $subMessage ?? null;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $code }} {{ $title }} — {{ config('app.name', 'Profim') }}</title>
        <meta name="robots" content="noindex, nofollow">
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#6366f1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <x-google-analytics />
    </head>
    <body class="d-flex flex-column min-vh-100 pt-lp-bg">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <x-application-logo />
                </a>
            </div>
        </nav>

        <main class="flex-grow-1 d-flex align-items-center py-5">
            <div class="container text-center" style="max-width: 36rem;">
                <div class="fw-bold mb-3 pt-gradient-text" style="font-size: 6rem; line-height: 1;">{{ $code }}</div>
                <h1 class="h4 fw-bold mb-3">{{ $title }}</h1>
                <p class="text-muted mb-4">
                    {{ $message }}
                    @if ($subMessage)
                        <br>{{ $subMessage }}
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="/" class="btn btn-primary px-4"><i class="bi bi-house"></i> トップへ戻る</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary px-4"><i class="bi bi-envelope"></i> お問い合わせ</a>
                </div>
            </div>
        </main>

        <footer class="border-top py-4 mt-auto" style="background: rgba(255,255,255,0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
            <div class="container text-center small text-muted">
                © {{ date('Y') }} {{ config('app.name', 'Profim') }}
            </div>
        </footer>
    </body>
</html>
