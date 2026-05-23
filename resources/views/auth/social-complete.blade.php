<x-guest-layout>
    <h1 class="h4 fw-bold text-center mb-3">あと少しで登録完了</h1>
    <p class="text-muted small text-center mb-4">
        Google アカウントの認証が完了しました。<br>
        利用にあたって、生年月日と利用規約への同意が必要です。
    </p>

    <div class="d-flex align-items-center gap-2 p-3 mb-4 rounded-3" style="background: rgba(99,102,241,0.06); border: 1px solid rgba(99,102,241,0.18);">
        @if (!empty($oauthData['avatar']))
            <img src="{{ $oauthData['avatar'] }}" alt="" width="36" height="36" class="rounded-circle" referrerpolicy="no-referrer">
        @endif
        <div class="flex-grow-1 small">
            <div class="fw-semibold">{{ $oauthData['name'] }}</div>
            <div class="text-muted" style="font-size: 0.75rem;">{{ $oauthData['email'] }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('oauth.google.complete.store') }}">
        @csrf

        <div class="mb-3">
            <x-input-label value="生年月日" />
            <x-birthdate-selector name="birthdate" :value="old('birthdate')" />
            <div class="form-text small">13歳以上の方のみご利用いただけます。</div>
            <x-input-error :messages="$errors->get('birthdate')" />
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
            <label class="form-check-label small" for="terms">
                <a href="{{ route('terms') }}" target="_blank" rel="noopener">利用規約</a>
                および
                <a href="{{ route('privacy') }}" target="_blank" rel="noopener">プライバシーポリシー</a>
                に同意します
            </label>
            <x-input-error :messages="$errors->get('terms')" />
        </div>

        <div class="d-grid">
            <x-primary-button>登録を完了する</x-primary-button>
        </div>

        <p class="small text-muted text-center mt-3 mb-0">
            <a href="{{ route('register') }}" class="text-decoration-none text-muted">キャンセル</a>
        </p>
    </form>
</x-guest-layout>
