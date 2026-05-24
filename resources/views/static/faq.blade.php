<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>よくある質問 — {{ config('app.name', 'Profim') }}</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#6366f1">
        <meta name="description" content="Profim のよくある質問とその回答。登録・プロフィール作成・テーマ切替・安全性などについて。">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <x-google-analytics />

        <style>
            .faq-item {
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
                padding: 1rem 0;
            }
            .faq-item:last-child { border-bottom: none; }
            .faq-item summary {
                cursor: pointer;
                list-style: none;
                font-weight: 600;
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
                padding-right: 1rem;
                position: relative;
            }
            .faq-item summary::-webkit-details-marker { display: none; }
            .faq-item summary::before {
                content: 'Q';
                color: var(--pt-primary, #6366f1);
                font-weight: 800;
                font-size: 1.1rem;
                flex-shrink: 0;
                width: 1.5rem;
            }
            .faq-item summary::after {
                content: '＋';
                position: absolute;
                right: 0;
                top: 0;
                color: rgba(0, 0, 0, 0.4);
                font-weight: 700;
                transition: transform 0.2s ease;
            }
            .faq-item[open] summary::after {
                content: '−';
            }
            .faq-item .answer {
                padding: 0.75rem 0 0 2.25rem;
                color: #4b5563;
                font-size: 0.95rem;
                line-height: 1.7;
            }
            .faq-category {
                margin-top: 2.5rem;
                margin-bottom: 1rem;
                color: #1f2937;
                letter-spacing: 0.02em;
            }
            .faq-category:first-of-type { margin-top: 1rem; }
        </style>
    </head>
    <body class="d-flex flex-column min-vh-100 pt-lp-bg">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <x-application-logo />
                </a>
                <ul class="navbar-nav ms-auto align-items-lg-center flex-row gap-2">
                    @auth
                        <li class="nav-item"><a class="btn btn-outline-secondary btn-sm" href="{{ url('/dashboard') }}">マイページ</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('login') }}">ログイン</a></li>
                        <li class="nav-item"><a class="btn btn-primary btn-sm" href="{{ route('register') }}">無料ではじめる</a></li>
                    @endauth
                </ul>
            </div>
        </nav>

        <main class="flex-grow-1 py-4 py-md-5">
            <div class="container px-3" style="max-width: 760px;">
                <h1 class="h3 fw-bold mb-2">よくある質問</h1>
                <p class="text-muted small mb-4">
                    Profim についてよくいただく質問をまとめました。<br>
                    解決しない場合は <a href="{{ route('contact') }}">お問い合わせフォーム</a> または <a href="mailto:contact@profie.me">contact@profie.me</a> までお気軽にどうぞ。
                </p>

                {{-- サービスについて --}}
                <h2 class="h5 fw-bold faq-category"><i class="bi bi-info-circle-fill" style="color: var(--pt-primary);"></i> サービスについて</h2>

                <details class="faq-item">
                    <summary>Profim ってどんなサービスですか？</summary>
                    <div class="answer">
                        ニックネーム・自己紹介・好きなもの・推し・SNS リンクなどを 1 つのページにまとめて公開できる「自己紹介ページ作成サービス」です。64 問の質問テンプレートに答えるだけで、あなたらしさが伝わるプロフィールページが作れます。5 つのテーマで世界観を切り替えることもできます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>無料で使えますか？</summary>
                    <div class="answer">
                        基本機能はすべて無料でご利用いただけます。将来的に拡張機能（カスタムドメイン・追加テーマなど）を有料プランとしてご提供する可能性はありますが、現在ある機能を後から有料化することはありません。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>スマートフォンからも使えますか？</summary>
                    <div class="answer">
                        はい、スマートフォン・タブレット・PC のいずれからもご利用いただけます。専用アプリのインストールは不要で、ブラウザだけで動作します。
                    </div>
                </details>

                {{-- 登録・ログイン --}}
                <h2 class="h5 fw-bold faq-category"><i class="bi bi-person-plus-fill" style="color: var(--pt-primary);"></i> 登録・ログイン</h2>

                <details class="faq-item">
                    <summary>何歳から使えますか？</summary>
                    <div class="answer">
                        13 歳以上の方からご利用いただけます。13 歳以上 18 歳未満の方は、保護者の同意を得たうえでご利用ください。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>Google アカウントでログインできますか？</summary>
                    <div class="answer">
                        はい、Google アカウントでの登録・ログインに対応しています。<a href="{{ route('register') }}">新規登録ページ</a> または <a href="{{ route('login') }}">ログインページ</a> の「Google で続ける」ボタンからご利用ください。なお、初回登録時のみ、生年月日と利用規約への同意の入力をお願いしています。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>パスワードを忘れました</summary>
                    <div class="answer">
                        <a href="{{ route('password.request') }}">パスワード再設定ページ</a> からメールアドレスを入力すると、再設定用のリンクをメールでお送りします。Google ログインで登録された方は、Google 側のアカウント管理からパスワード再設定をお願いします。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>退会したい / アカウントを削除したい</summary>
                    <div class="answer">
                        現在、退会機能はマイページから直接行えません。お手数ですが <a href="{{ route('contact') }}">お問い合わせフォーム</a> または contact@profie.me 宛にご連絡ください。退会日からおおむね 30 日以内に、アカウント情報とプロフィール情報を削除します。
                    </div>
                </details>

                {{-- プロフィール --}}
                <h2 class="h5 fw-bold faq-category"><i class="bi bi-card-text" style="color: var(--pt-primary);"></i> プロフィール</h2>

                <details class="faq-item">
                    <summary>公開 URL（/u/○○ の部分）は変更できますか？</summary>
                    <div class="answer">
                        はい、マイページ＞「プロフィール編集」から、3 〜 24 文字の英数字・ハイフン・アンダースコアで自由に設定できます。半角小文字に統一されます。すでに使われている文字列は使えません。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>プロフィールを非公開にできますか？</summary>
                    <div class="answer">
                        はい、マイページ＞「プロフィール編集」の「公開設定」をオフにすると、検索エンジンの除外とともに、公開 URL へのアクセスもブロックされます。後からいつでもオンに戻せます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>64 問の質問、全部答えないとダメですか？</summary>
                    <div class="answer">
                        いいえ、答えたい質問にだけ答えれば OK です。空欄のままにした質問は公開ページには表示されません。よくある質問 10 問が上に表示され、残り 54 問は「もっと答える」ボタンを押すと表示されます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>オリジナルの質問を追加できますか？</summary>
                    <div class="answer">
                        はい、マイページ＞「質問への回答」ページの下部にある「オリジナル質問を追加」フォームから、自分で考えた質問とその回答を自由に追加できます。運営の質問と同じ形式で公開ページに表示されます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>アイコン画像はどんな形式が使えますか？</summary>
                    <div class="answer">
                        JPEG / PNG / GIF / WebP の画像をアップロードできます。1 ファイルあたり 2MB までです。アップロード後は自動的に正方形にトリミングされ、円形で表示されます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>タグ（マイタグ）は何個まで登録できますか？</summary>
                    <div class="answer">
                        最大 30 個まで登録できます。ドラッグ＆ドロップで並び替えも可能です。「推し」「趣味」「好きな食べ物」など、自由に追加してください。
                    </div>
                </details>

                {{-- テーマ・SNS --}}
                <h2 class="h5 fw-bold faq-category"><i class="bi bi-palette-fill" style="color: var(--pt-primary);"></i> テーマ・SNS リンク</h2>

                <details class="faq-item">
                    <summary>テーマは何種類ありますか？</summary>
                    <div class="answer">
                        現在「モダン」「平成ピンク」「ガラケー風」「推し活ネオン」「パステル」の 5 種類のテーマをご用意しています。マイページ＞「テーマ」からいつでも切り替えられます。メインカラーだけ自分好みに上書きすることも可能です。
                        <br><br>
                        実際の見え方は <a href="/u/demo" target="_blank" rel="noopener">公式デモプロフィール</a> でテーマ切替バーから試せます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>追加できる SNS リンクの種類は？</summary>
                    <div class="answer">
                        X（旧 Twitter） / Instagram / TikTok / YouTube に加え、「その他」を選べばどんな URL でも自由に追加できます（LINE 公式アカウント、ブログ、ECサイトなど）。それぞれ表示順を入れ替えることもできます。
                    </div>
                </details>

                {{-- 安全性 --}}
                <h2 class="h5 fw-bold faq-category"><i class="bi bi-shield-fill-check" style="color: var(--pt-primary);"></i> 安全性・通報</h2>

                <details class="faq-item">
                    <summary>不適切なプロフィールを見つけたとき、どうすればいい？</summary>
                    <div class="answer">
                        該当する公開プロフィールページの下部にある「<i class="bi bi-flag"></i> このプロフィールを通報」リンクから運営にご連絡ください。内容を確認のうえ、必要に応じて該当アカウントの停止などの対応を行います。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>個人情報はどう守られていますか？</summary>
                    <div class="answer">
                        SSL（HTTPS）通信、パスワードのハッシュ化保存、アクセス権の管理など、適切な安全管理措置を講じています。詳しくは <a href="{{ route('privacy') }}">プライバシーポリシー</a> をご確認ください。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>生年月日やメールアドレスは公開されますか？</summary>
                    <div class="answer">
                        生年月日・メールアドレス・パスワードは <strong>一切公開されません</strong>。公開されるのは、あなたがマイページから登録・編集したニックネーム / アイコン / 自己紹介 / タグ / SNS リンク / 質問への回答だけです。
                    </div>
                </details>

                {{-- その他 --}}
                <h2 class="h5 fw-bold faq-category"><i class="bi bi-chat-dots-fill" style="color: var(--pt-primary);"></i> その他</h2>

                <details class="faq-item">
                    <summary>バグを見つけました / 機能要望があります</summary>
                    <div class="answer">
                        <a href="{{ route('contact') }}">お問い合わせフォーム</a> または contact@profie.me 宛にご連絡ください。サービス改善の参考にさせていただきます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>SNS でシェアした時、プレビュー画像が古いままです</summary>
                    <div class="answer">
                        SNS 側のキャッシュが残っている可能性があります。Facebook の場合は <a href="https://developers.facebook.com/tools/debug/" target="_blank" rel="noopener">Facebook Sharing Debugger</a> で URL を入力して「再スクレイプ」できます。X（Twitter）の場合は、URL に `?v=2` のようなクエリを付けて投稿すると新しい画像が取得されます。
                    </div>
                </details>

                <details class="faq-item">
                    <summary>運営はどんな会社ですか？</summary>
                    <div class="answer">
                        Profim は「Profim 運営事務局」が運営しています。お問い合わせは <a href="mailto:contact@profie.me">contact@profie.me</a> までお願いします。詳しくは <a href="{{ route('terms') }}">利用規約</a> / <a href="{{ route('privacy') }}">プライバシーポリシー</a> をご確認ください。
                    </div>
                </details>

                <div class="text-center mt-5 mb-3">
                    <p class="text-muted small mb-3">解決しない場合は…</p>
                    <a href="{{ route('contact') }}" class="btn btn-primary px-4"><i class="bi bi-envelope"></i> お問い合わせフォームへ</a>
                </div>

                <p class="mt-4"><a href="/" class="small">トップへ戻る</a></p>
            </div>
        </main>

        <footer class="border-top py-4 mt-auto" style="background: rgba(255,255,255,0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
            <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-muted">
                <span>© {{ date('Y') }} {{ config('app.name', 'Profim') }}</span>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('faq') }}" class="text-muted text-decoration-none">よくある質問</a>
                    <a href="{{ route('terms') }}" class="text-muted text-decoration-none">利用規約</a>
                    <a href="{{ route('privacy') }}" class="text-muted text-decoration-none">プライバシー</a>
                    <a href="{{ route('contact') }}" class="text-muted text-decoration-none">お問い合わせ</a>
                </div>
            </div>
        </footer>
    </body>
</html>
