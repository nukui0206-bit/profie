<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Profie') }} — あなたのすべてを、ひとつのページに。</title>
        <meta name="robots" content="noindex, nofollow, noarchive">
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#6366f1">
        <meta name="description" content="ニックネーム・自己紹介・好きなもの・推し・SNSリンクを1ページにまとめて公開できる、あたらしい自己紹介ページサービス。">

        {{-- OGP / Twitter Card --}}
        <meta property="og:type" content="website">
        <meta property="og:title" content="Profie — あなたのすべてを、ひとつのページに。">
        <meta property="og:description" content="ニックネーム・自己紹介・好きなもの・推し・SNS リンクを 1 ページにまとめて公開できる、あたらしい自己紹介ページサービス。">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:image" content="{{ url('/og-image.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:site_name" content="Profie">
        <meta property="og:locale" content="ja_JP">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Profie — あなたのすべてを、ひとつのページに。">
        <meta name="twitter:description" content="ニックネーム・自己紹介・好きなもの・推し・SNS リンクを 1 ページにまとめて公開できる。">
        <meta name="twitter:image" content="{{ url('/og-image.png') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100 pt-lp-bg">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <x-application-logo />
                </a>
                <ul class="navbar-nav ms-auto align-items-lg-center flex-row gap-2">
                    @auth
                        <li class="nav-item"><a class="btn btn-outline-secondary" href="{{ url('/dashboard') }}">マイページ</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('login') }}">ログイン</a></li>
                        <li class="nav-item"><a class="btn btn-primary" href="{{ route('register') }}">無料ではじめる</a></li>
                    @endauth
                </ul>
            </div>
        </nav>

        <main class="flex-grow-1">
            {{-- Hero --}}
            <section class="pt-hero">
                <div class="container text-center" style="max-width: 56rem;">
                    <span class="badge rounded-pill px-3 py-2 mb-3" style="background: rgba(99,102,241,0.08); color: var(--pt-primary); font-weight:600;">
                        ✦ あたらしい自己紹介ページ、はじめました
                    </span>
                    <h1 class="fw-bold">
                        あなたのすべてを、<br>
                        <span class="pt-gradient-text">ひとつのページに。</span>
                    </h1>
                    <p class="lead mt-3">
                        ニックネーム・自己紹介・好きなもの・推し・SNS リンクを 1 ページにまとめて、URL ひとつで世界に届ける。<br>
                        基本機能はずっと無料で使えます。
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center mt-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">無料ではじめる</a>
                        <a href="#features" class="btn btn-outline-secondary btn-lg px-4">機能を見る</a>
                    </div>
                    <p class="small text-muted mt-3 mb-0">登録 30 秒で完了</p>
                </div>
            </section>

            {{-- Features --}}
            <section id="features" class="py-5">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold mb-2">あなただけの一枚にする、3 つの機能</h2>
                        <p class="text-muted mb-0">作って 3 分、シェアして無限。</p>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="pt-feature-card">
                                <div class="pt-feature-icon"><i class="bi bi-question-circle-fill"></i></div>
                                <h3 class="h5 fw-bold">質問テンプレで深掘り</h3>
                                <p class="text-muted small mb-0">
                                    「最近ハマってるもの」「無人島に1つ持ってくなら」など、答えるだけであなたらしさが伝わる質問を多数用意。
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pt-feature-card">
                                <div class="pt-feature-icon"><i class="bi bi-heart-fill"></i></div>
                                <h3 class="h5 fw-bold">好き・推し・SNS をまとめる</h3>
                                <p class="text-muted small mb-0">
                                    好きなものリストや SNS リンクを並べて、Lit.link のように。シェアしやすい URL もすぐ取得。
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pt-feature-card">
                                <div class="pt-feature-icon"><i class="bi bi-palette-fill"></i></div>
                                <h3 class="h5 fw-bold">テーマで世界観を切替</h3>
                                <p class="text-muted small mb-0">
                                    パステル・ネオン・モノトーン・平成レトロ。ワンタップでページの雰囲気がまるごと変わる。
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Bottom CTA --}}
            <section class="py-5">
                <div class="container">
                    <div class="rounded-4 text-center p-5" style="background: var(--pt-gradient);">
                        <h2 class="text-white fw-bold mb-2">あなたの「すき」を、世界へ。</h2>
                        <p class="text-white opacity-75 mb-4">30 秒で、あなただけの URL を。<br>基本機能はずっと無料で使えます。</p>
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 fw-semibold">無料ではじめる</a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-top py-4 mt-auto" style="background: rgba(255,255,255,0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-muted">
                <span>© {{ date('Y') }} Profie</span>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('terms') }}" class="text-muted text-decoration-none">利用規約</a>
                    <a href="{{ route('privacy') }}" class="text-muted text-decoration-none">プライバシー</a>
                    <a href="{{ route('contact') }}" class="text-muted text-decoration-none">お問い合わせ</a>
                </div>
            </div>
        </footer>
    </body>
</html>
