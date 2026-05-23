<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>通報 — {{ config('app.name', 'Profie') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100 py-4 py-md-5">
        <main class="flex-grow-1">
            <div class="container px-3" style="max-width: 36rem;">
                <h1 class="h4 fw-bold mb-3">通報</h1>

                @if (! $targetExists)
                    <div class="alert alert-warning small">
                        通報対象が見つかりません。URL が正しいかご確認ください。
                    </div>
                @endif

                <p class="text-muted small mb-4">
                    対象：<strong>{{ $targetLabel }}</strong>
                </p>

                <form method="post" action="{{ route('report.store') }}" class="card border-0 shadow-sm">
                    @csrf
                    <input type="hidden" name="target_type" value="{{ $targetType }}">
                    <input type="hidden" name="target_id" value="{{ $targetId }}">

                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="reason">通報理由</label>
                            <select id="reason" name="reason" class="form-select" required>
                                <option value="">選択してください</option>
                                @foreach (\App\Models\Report::REASONS as $key => $label)
                                    <option value="{{ $key }}" {{ old('reason') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('reason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="body">補足説明（任意・最大 1000 文字）</label>
                            <textarea id="body" name="body" rows="5" maxlength="1000"
                                      class="form-control"
                                      placeholder="具体的な状況やスクリーンショットの URL があれば記入してください。">{{ old('body') }}</textarea>
                            @error('body')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <p class="small text-muted">
                            通報内容は運営側で確認します。誹謗中傷など悪質な行為が確認できた場合、
                            アカウント停止などの対応を行うことがあります。<br>
                            <strong>虚偽の通報は禁止行為です。</strong>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between gap-2">
                        <a href="/" class="btn btn-outline-secondary">キャンセル</a>
                        <button type="submit" class="btn btn-danger">通報する</button>
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>
