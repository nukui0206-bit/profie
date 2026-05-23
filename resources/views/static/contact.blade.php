<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>お問い合わせ — {{ config('app.name', 'Profie') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100 py-4 py-md-5">
        <main class="flex-grow-1">
            <div class="container px-3" style="max-width: 36rem;">
                <h1 class="h4 fw-bold mb-3">お問い合わせ</h1>

                @if (session('status') === 'contact-sent')
                    <div class="alert alert-success small">
                        お問い合わせありがとうございます。受け付けました。<br>
                        内容を確認のうえ、必要に応じてご返信いたします。
                    </div>
                @endif

                <p class="text-muted small mb-4">
                    バグ報告・機能要望・運営へのご連絡はこちらのフォームからお送りください。<br>
                    通報はお手数ですが、対象プロフィールページの「通報」リンクからお願いします。
                </p>

                <form method="post" action="{{ route('contact.send') }}" class="card border-0 shadow-sm">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="name">お名前</label>
                            <input type="text" id="name" name="name" maxlength="60"
                                   value="{{ old('name', auth()->user()->name ?? '') }}"
                                   class="form-control" required>
                            @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="email">メールアドレス</label>
                            <input type="email" id="email" name="email" maxlength="255"
                                   value="{{ old('email', auth()->user()->email ?? '') }}"
                                   class="form-control" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="body">お問い合わせ内容（10〜2000 文字）</label>
                            <textarea id="body" name="body" rows="6" minlength="10" maxlength="2000"
                                      class="form-control" required>{{ old('body') }}</textarea>
                            @error('body')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <p class="small text-muted mb-0">
                            送信前に <a href="{{ route('privacy') }}" target="_blank">プライバシーポリシー</a> をご確認ください。
                        </p>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between">
                        <a href="/" class="btn btn-outline-secondary">トップへ戻る</a>
                        <button type="submit" class="btn btn-primary">送信する</button>
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>
