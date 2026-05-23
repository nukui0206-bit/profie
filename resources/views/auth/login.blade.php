<x-guest-layout>
    <h1 class="h4 fw-bold text-center mb-4">ログイン</h1>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    @if ($errors->has('oauth'))
        <div class="alert alert-danger small">{{ $errors->first('oauth') }}</div>
    @endif

    {{-- Google でログイン --}}
    <a href="{{ route('oauth.google.redirect') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2 py-2 mb-3" style="font-weight: 500;">
        <svg width="18" height="18" viewBox="0 0 18 18" aria-hidden="true">
            <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615Z"/>
            <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18Z"/>
            <path fill="#FBBC05" d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.997 8.997 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332Z"/>
            <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.167 6.656 3.58 9 3.58Z"/>
        </svg>
        <span>Google でログイン</span>
    </a>

    <div class="d-flex align-items-center text-muted small my-3" style="gap: 0.75rem;">
        <hr class="flex-grow-1 my-0">
        <span>または</span>
        <hr class="flex-grow-1 my-0">
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" value="パスワード" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label small text-muted" for="remember_me">ログイン状態を保持する</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="small text-decoration-none" href="{{ route('password.request') }}">パスワードを忘れた？</a>
            @endif
            <x-primary-button>ログイン</x-primary-button>
        </div>

        <hr class="my-4">
        <div class="text-center small">
            アカウント未作成？ <a href="{{ route('register') }}">新規登録</a>
        </div>
    </form>
</x-guest-layout>
