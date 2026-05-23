<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>プライバシーポリシー — {{ config('app.name', 'Profim') }}</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#6366f1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

        <x-google-analytics />
    </head>
    <body class="d-flex flex-column min-vh-100 py-4 py-md-5">
        <main class="flex-grow-1">
            <div class="container px-3" style="max-width: 760px;">
                <h1 class="h3 fw-bold mb-2">プライバシーポリシー</h1>
                <p class="small text-muted mb-4">
                    制定日：2026 年 5 月 8 日 ／ 最終改定日：2026 年 5 月 24 日
                </p>

                <div class="alert alert-info small">
                    本ポリシーは初期版です。サービスの成長と関連法令の変更に合わせて改定することがあります。<br>
                    重要な変更を行う場合は、当サービス上での告知や改定日の更新により通知します。
                </div>

                <p class="small">
                    {{ config('app.name', 'Profim') }}（以下「当サービス」）は、利用者の個人情報の取扱いについて、個人情報保護法その他関連法令を遵守し、以下のとおりプライバシーポリシーを定めます。
                </p>

                <h2 class="h5 fw-bold mt-4">1. 取得する情報</h2>
                <p class="small">当サービスは、以下の情報を取得します。</p>
                <ul class="small">
                    <li>利用登録時にご提供いただく情報：メールアドレス、パスワード（暗号化して保存）、ニックネーム、生年月日</li>
                    <li>外部認証サービス（Google アカウント等）でログインされた場合に取得する情報：当該サービスの ID、メールアドレス、氏名（表示名）、プロフィール画像 URL。これらは Google が公開している OAuth プロトコルを通じて取得し、利用者の同意なくこれ以外の情報（連絡先・カレンダー・Drive 等）は取得しません。</li>
                    <li>プロフィール作成・編集により入力された情報：自己紹介、アイコン画像、好きなものリスト（タグ）、質問への回答、SNS リンク、テーマ設定</li>
                    <li>当サービスの利用にあたって自動的に取得する情報：IP アドレス、ブラウザ情報、リファラ、アクセス日時、利用端末情報、Cookie 情報</li>
                    <li>アクセスログ・操作ログ：閲覧したプロフィール、いいね・足あとの履歴等</li>
                    <li>お問い合わせ・通報フォーム入力時の情報：氏名、メールアドレス、内容</li>
                </ul>

                <h2 class="h5 fw-bold mt-4">2. 利用目的</h2>
                <p class="small">取得した情報は、以下の目的のために利用します。</p>
                <ul class="small">
                    <li>本人認証およびアカウント管理</li>
                    <li>当サービスの提供・運営・改善</li>
                    <li>不正利用・スパム・なりすまし等の検知および防止</li>
                    <li>利用状況の分析、新機能の検討</li>
                    <li>利用者からのお問い合わせ・通報への対応</li>
                    <li>重要なお知らせや、必要に応じた事務連絡</li>
                    <li>規約違反等への調査・対応</li>
                </ul>

                <h2 class="h5 fw-bold mt-4">3. 第三者提供</h2>
                <p class="small">
                    当サービスは、以下のいずれかに該当する場合を除き、利用者の個人情報を第三者に提供しません。
                </p>
                <ul class="small">
                    <li>利用者本人の同意がある場合</li>
                    <li>法令に基づく場合</li>
                    <li>人の生命、身体、財産の保護のために必要であり、本人の同意取得が困難な場合</li>
                    <li>業務委託先（クラウドサービス、メール送信、決済など）に必要な範囲で提供する場合（委託先には当ポリシーと同等の安全管理措置を求めます）</li>
                </ul>

                <h2 class="h5 fw-bold mt-4">4. 公開プロフィールの取扱い</h2>
                <p class="small">
                    利用者が「公開設定」をオンにしている場合、ニックネーム / アイコン / 自己紹介 / タグ / SNS リンク / 質問への回答などの情報は <code>/u/{slug}</code> の URL で第三者に公開されます。これらの情報は検索エンジンにインデックスされる可能性があります。<br>
                    生年月日・メールアドレス・パスワードは公開されません。
                </p>

                <h2 class="h5 fw-bold mt-4">5. Cookie・アクセス解析</h2>
                <p class="small">
                    当サービスは、利便性向上およびアクセス解析のために Cookie・類似技術を使用することがあります。Google Analytics などのアクセス解析ツールを使用する場合、これらのツールが取得する情報には個人を識別できる情報は含まれません。利用者はブラウザの設定により Cookie を無効化できますが、その場合一部機能が利用できなくなる可能性があります。
                </p>

                <h2 class="h5 fw-bold mt-4">6. セキュリティ</h2>
                <p class="small">
                    当サービスは、利用者の個人情報の漏洩・滅失・毀損の防止のために、SSL（HTTPS）通信、アクセス権の管理、パスワードの暗号化保存、その他必要かつ適切な安全管理措置を講じます。
                </p>

                <h2 class="h5 fw-bold mt-4">7. 開示・訂正・削除等の請求</h2>
                <p class="small">
                    利用者は、自己の個人情報について <a href="{{ route('contact') }}">お問い合わせフォーム</a> を通じて、開示・訂正・追加・利用停止・消去等を請求できます。本人確認のうえ、法令に従い適切に対応します。なお、ニックネーム・自己紹介などプロフィール情報は、ログイン後の編集画面から利用者自身で随時変更できます。
                </p>

                <h2 class="h5 fw-bold mt-4">8. 未成年者の個人情報</h2>
                <p class="small">
                    当サービスは 13 歳未満の方からの個人情報の取得を行いません。13 歳以上 18 歳未満の利用者は、保護者の同意を得たうえでご利用ください。
                </p>

                <h2 class="h5 fw-bold mt-4">9. 保存期間</h2>
                <p class="small">取得した個人情報は、それぞれ以下の期間保有し、不要となった情報は速やかに削除します。</p>
                <ul class="small">
                    <li>アカウント情報（メールアドレス・パスワードハッシュ・ニックネーム・生年月日・Google ID 等）：退会日からおおむね 30 日以内に削除します。ただし、不正利用・通報対応・法令対応のために必要な範囲で保存期間を延長することがあります。</li>
                    <li>プロフィール情報（自己紹介・アイコン・タグ・回答・SNS リンク等）：退会日からおおむね 30 日以内に削除します。</li>
                    <li>アクセスログ・操作ログ：取得日から最大 6 ヶ月で削除します。</li>
                    <li>データベースのバックアップ：日次で取得し、最大 14 日間保管したのち順次削除します。</li>
                    <li>お問い合わせ・通報内容：対応終了後も、再発防止と統計分析の目的で 1 年程度保存することがあります。</li>
                </ul>

                <h2 class="h5 fw-bold mt-4">10. プライバシーポリシーの変更</h2>
                <p class="small">
                    法令の変更や運営方針の変更に伴い、本ポリシーを改定することがあります。重要な変更がある場合は、当サービス上で通知します。改定後のポリシーは、当サービス上に掲載した時点で効力を生じます。
                </p>

                <h2 class="h5 fw-bold mt-4">11. お問い合わせ窓口</h2>
                <p class="small">
                    本ポリシーおよび個人情報の取扱いに関するお問い合わせは <a href="{{ route('contact') }}">お問い合わせフォーム</a> または <a href="mailto:contact@profie.me">contact@profie.me</a> 宛にご連絡ください。
                </p>

                <hr class="my-4">

                <h2 class="h6 fw-bold mt-4">運営者情報</h2>
                <ul class="small list-unstyled">
                    <li>サービス名：{{ config('app.name', 'Profim') }}</li>
                    <li>運営者：株式会社 L'aide</li>
                    <li>連絡先：<a href="mailto:contact@profie.me">contact@profie.me</a></li>
                </ul>

                <p class="mt-5"><a href="/" class="small">トップへ戻る</a></p>
            </div>
        </main>
    </body>
</html>
