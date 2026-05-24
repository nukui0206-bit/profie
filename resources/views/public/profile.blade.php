<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $profile->nickname }} — {{ config('app.name', 'Profim') }}</title>
        @if (! $profile->is_published)
            <meta name="robots" content="noindex, nofollow, noarchive">
        @endif
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#6366f1">

        @php
            $description = \Illuminate\Support\Str::limit($profile->bio ?? "{$profile->nickname} のプロフィールページ", 80);
            $ogImage = $profile->avatar_path ? url(\Illuminate\Support\Facades\Storage::url($profile->avatar_path)) : null;
            $shareText = "{$profile->nickname} のプロフィール";
        @endphp

        <meta name="description" content="{{ $description }}">
        <link rel="canonical" href="{{ $profile->public_url }}">

        <meta property="og:type" content="profile">
        <meta property="og:title" content="{{ $profile->nickname }} のプロフィール">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ $profile->public_url }}">
        <meta property="og:site_name" content="{{ config('app.name', 'Profim') }}">
        <meta property="og:locale" content="ja_JP">
        @if ($ogImage)
            <meta property="og:image" content="{{ $ogImage }}">
            <meta property="og:image:alt" content="{{ $profile->nickname }} のアイコン">
        @endif

        <meta name="twitter:card" content="{{ $ogImage ? 'summary' : 'summary' }}">
        <meta name="twitter:title" content="{{ $profile->nickname }} のプロフィール">
        <meta name="twitter:description" content="{{ $description }}">
        @if ($ogImage)
            <meta name="twitter:image" content="{{ $ogImage }}">
        @endif

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <x-google-analytics />
    </head>
    @php
        $themeKey = $profile->theme?->key ?? 'modern';
        $tc = $profile->theme_color;
        $accentStyle = $tc
            ? "--pt-public-accent: {$tc};"
                . "--pt-public-avatar-grad: linear-gradient(135deg, {$tc} 0%, color-mix(in srgb, {$tc} 55%, #fff) 100%);"
                . "--pt-public-card-border: color-mix(in srgb, {$tc} 35%, transparent);"
            : '';
    @endphp
    <body class="d-flex flex-column min-vh-100 profile-public-page theme-{{ $themeKey }}"
          @if($accentStyle) style="{{ $accentStyle }}" @endif>
        <main class="flex-grow-1 py-4 py-md-5">
            {{-- 公式デモプロフィール限定：テーマ切替バー（DB は変更せず ?theme=xxx で表示のみ差し替え） --}}
            @if (isset($availableThemes) && $availableThemes->isNotEmpty())
                <div class="container px-3 mb-3" style="max-width: 36rem;">
                    <div class="rounded-3 p-3 text-center"
                         style="background: rgba(255,255,255,0.94); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); box-shadow: 0 4px 16px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); color: #1f2937;">
                        <p class="small fw-semibold mb-2" style="color: #4b5563;">
                            <i class="bi bi-palette-fill" aria-hidden="true"></i> テーマを試す
                        </p>
                        <div class="d-inline-flex flex-wrap gap-1 justify-content-center">
                            @foreach ($availableThemes as $t)
                                <a href="?theme={{ $t->key }}"
                                   class="btn btn-sm {{ $activeThemeKey === $t->key ? 'btn-dark' : 'btn-outline-dark' }}"
                                   style="border-radius: 999px; padding: 0.25rem 0.75rem; font-size: 0.8rem;">
                                    {{ $t->name }}
                                </a>
                            @endforeach
                        </div>
                        <p class="small mb-0 mt-2" style="font-size: 0.7rem; color: #6b7280;">
                            ※ デモ限定。自分のページでも登録後にテーマを選べます
                        </p>
                    </div>
                </div>
            @endif

            <div class="container px-3" style="max-width: 36rem;">
                <div class="card border-0 shadow-sm pt-public-card">
                    <div class="card-body p-4 p-md-5 text-center">
                        @if ($profile->avatar_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($profile->avatar_path) }}"
                                 alt="{{ $profile->nickname }} のアイコン"
                                 loading="eager"
                                 class="rounded-circle mx-auto mb-3 d-block"
                                 style="width: 88px; height: 88px; object-fit: cover; border: 1px solid var(--pt-public-card-border);">
                        @elseif ($profile->slug === 'demo')
                            {{-- デモ専用：Profim ブランドカラーのグラデーションアバター（テーマに依存しない） --}}
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                                 aria-label="{{ $profile->nickname }} のアイコン"
                                 style="width: 88px; height: 88px; font-size: 2rem; background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); box-shadow: 0 6px 20px rgba(99,102,241,0.35); border: 3px solid rgba(255,255,255,0.95);">
                                プ
                            </div>
                        @else
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold pt-public-avatar"
                                 aria-label="{{ $profile->nickname }} のアイコン"
                                 style="width: 88px; height: 88px; font-size: 2rem;">
                                {{ mb_substr($profile->nickname, 0, 1) }}
                            </div>
                        @endif

                        <h1 class="h3 fw-bold mb-1">{{ $profile->nickname }}</h1>
                        <p class="small pt-public-muted mb-3">{{ '@' . $profile->slug }}</p>

                        @if ($profile->bio)
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $profile->bio }}</p>
                        @else
                            <p class="small pt-public-muted fst-italic mb-0">まだ自己紹介が登録されていません。</p>
                        @endif

                        <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 gap-sm-3 small mt-4 pt-3 border-top" style="border-color: var(--pt-public-card-border) !important;">
                            <span class="pt-public-muted"><i class="bi bi-eye" aria-hidden="true"></i> {{ number_format($profile->view_count) }}</span>

                            @auth
                                @if (auth()->id() === $profile->user_id)
                                    <span class="pt-public-muted"><i class="bi bi-heart" aria-hidden="true"></i> <span id="like-count">{{ number_format($profile->like_count) }}</span></span>
                                @else
                                    <button type="button" id="like-btn"
                                            data-url="{{ route('public.profile.like', ['slug' => $profile->slug]) }}"
                                            data-liked="{{ $isLiked ? '1' : '0' }}"
                                            aria-label="{{ $isLiked ? 'いいねを取り消す' : 'いいねする' }}"
                                            class="btn btn-sm d-inline-flex align-items-center gap-1"
                                            style="border: 1px solid var(--pt-public-accent); color: {{ $isLiked ? '#fff' : 'var(--pt-public-accent)' }}; background: {{ $isLiked ? 'var(--pt-public-accent)' : 'transparent' }}; transition: background 0.15s, color 0.15s;">
                                        <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }}" id="like-icon" aria-hidden="true"></i>
                                        <span id="like-count">{{ number_format($profile->like_count) }}</span>
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="pt-public-muted text-decoration-none">
                                    <i class="bi bi-heart" aria-hidden="true"></i> ログインしていいね（<span id="like-count">{{ number_format($profile->like_count) }}</span>）
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- シェアボタン --}}
                <div class="d-flex flex-wrap gap-2 justify-content-center mt-3">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($profile->public_url) }}&text={{ urlencode($shareText) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="btn btn-sm pt-public-link-btn d-inline-flex align-items-center gap-2">
                        <i class="bi bi-twitter-x" aria-hidden="true"></i>
                        X でシェア
                    </a>
                    <button type="button" id="share-copy-btn"
                            data-url="{{ $profile->public_url }}"
                            class="btn btn-sm pt-public-link-btn d-inline-flex align-items-center gap-2">
                        <i class="bi bi-clipboard" aria-hidden="true"></i>
                        <span id="share-copy-label">URL をコピー</span>
                    </button>
                </div>

                @if ($profile->socialLinks->isNotEmpty())
                    <div class="card border-0 shadow-sm mt-4 pt-public-card">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h6 fw-bold pt-public-muted mb-3 text-center" style="letter-spacing: 0.1em;">LINKS</h2>
                            <div class="d-grid gap-2">
                                @foreach ($profile->socialLinks as $link)
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                       class="btn text-start d-inline-flex align-items-center gap-3 py-2 pt-public-link-btn">
                                        <i class="bi {{ $link->iconClass() }} fs-5" aria-hidden="true"></i>
                                        <span class="fw-semibold flex-grow-1 text-truncate">{{ $link->displayLabel() }}</span>
                                        <i class="bi bi-arrow-up-right small pt-public-muted" aria-hidden="true"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($profile->favorites->isNotEmpty())
                    <div class="card border-0 shadow-sm mt-4 pt-public-card">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h6 fw-bold pt-public-muted mb-3 text-center" style="letter-spacing: 0.1em;">TAGS</h2>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                @foreach ($profile->favorites as $fav)
                                    <span class="badge rounded-pill px-3 py-2 pt-public-tag" style="font-weight: 600; font-size: 0.9rem;">
                                        <span class="opacity-75">#</span>{{ $fav->label }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @php
                    // 表示する回答：運営質問（owner_user_id=null）または このプロフ所有者が作ったカスタム質問。
                    // 他人のカスタム質問が混ざらないよう所有者チェックする。
                    $answers = $profile->answers->filter(function ($a) use ($profile) {
                        if (! $a->question || ! $a->question->is_active) return false;
                        $owner = $a->question->owner_user_id;
                        return $owner === null || $owner === $profile->user_id;
                    })->sortBy(fn ($a) => $a->question->sort_order ?? 999);
                @endphp

                @if ($answers->isNotEmpty())
                    <div class="card border-0 shadow-sm mt-4 pt-public-card">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h6 fw-bold pt-public-muted mb-4 text-center" style="letter-spacing: 0.1em;">Q &amp; A</h2>
                            @foreach ($answers as $answer)
                                <div class="mb-3 pb-3 {{ $loop->last ? 'mb-0 pb-0' : 'border-bottom' }}" style="border-color: var(--pt-public-card-border) !important;">
                                    <div class="small fw-semibold mb-1 pt-public-q">
                                        Q. {{ $answer->question->body }}
                                    </div>
                                    <div style="white-space: pre-wrap;">{{ $answer->body }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- デモ限定 CTA バナー --}}
                @if ($profile->slug === 'demo')
                    <div class="rounded-4 p-4 p-md-5 text-center mt-4"
                         style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%); color: white; box-shadow: 0 10px 30px rgba(99,102,241,0.25);">
                        <h2 class="h5 fw-bold mb-2" style="color: #fff;">あなたも自分のページを作ってみる？</h2>
                        <p class="small mb-3" style="color: rgba(255,255,255,0.92);">
                            30 秒で完了。基本機能はずっと無料で使えます
                        </p>
                        <a href="{{ route('register') }}"
                           class="btn btn-light btn-lg px-4 fw-semibold"
                           style="color: #4f46e5; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                            <i class="bi bi-stars" aria-hidden="true"></i> 無料ではじめる
                        </a>
                    </div>
                @endif

                <p class="text-center mt-4 mb-2 small pt-public-muted">
                    <a href="/" class="text-decoration-none pt-public-muted">{{ config('app.name', 'Profim') }}</a> で作られたプロフィール
                </p>

                <p class="text-center mb-0 small pt-public-muted">
                    <a href="{{ route('report.create', ['target_type' => 'profile', 'target_id' => $profile->id]) }}"
                       class="text-decoration-none pt-public-muted" style="font-size: 0.75rem;">
                        <i class="bi bi-flag" aria-hidden="true"></i> このプロフィールを通報
                    </a>
                </p>
            </div>
        </main>

        <script>
            (function () {
                // URL をコピー
                const copyBtn = document.getElementById('share-copy-btn');
                const copyLabel = document.getElementById('share-copy-label');
                if (copyBtn) {
                    copyBtn.addEventListener('click', async () => {
                        try {
                            await navigator.clipboard.writeText(copyBtn.dataset.url);
                            const original = copyLabel.textContent;
                            copyLabel.textContent = 'コピー完了';
                            setTimeout(() => { copyLabel.textContent = original; }, 1800);
                        } catch (e) {
                            // フォールバック：fallback として prompt を出す
                            window.prompt('URL をコピーしてください', copyBtn.dataset.url);
                        }
                    });
                }

                @auth
                    @if (auth()->id() !== $profile->user_id)
                        // いいねトグル
                        const btn = document.getElementById('like-btn');
                        if (btn) {
                            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
                            const icon = document.getElementById('like-icon');
                            const count = document.getElementById('like-count');

                            btn.addEventListener('click', async () => {
                                if (btn.disabled) return;
                                btn.disabled = true;
                                try {
                                    const res = await fetch(btn.dataset.url, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrf,
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json',
                                        },
                                    });
                                    if (!res.ok) throw new Error('failed');
                                    const data = await res.json();
                                    count.textContent = data.count.toLocaleString();
                                    icon.className = 'bi bi-heart' + (data.liked ? '-fill' : '');
                                    btn.dataset.liked = data.liked ? '1' : '0';
                                    btn.style.background = data.liked ? 'var(--pt-public-accent)' : 'transparent';
                                    btn.style.color = data.liked ? '#fff' : 'var(--pt-public-accent)';
                                    btn.setAttribute('aria-label', data.liked ? 'いいねを取り消す' : 'いいねする');
                                } catch (e) {
                                    alert('いいねに失敗しました。再度お試しください。');
                                } finally {
                                    btn.disabled = false;
                                }
                            });
                        }
                    @endif
                @endauth
            })();
        </script>
    </body>
</html>
