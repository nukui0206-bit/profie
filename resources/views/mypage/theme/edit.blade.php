<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">テーマ設定</h1>
            <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> 公開ページを開く
            </a>
        </div>
    </x-slot>

    @if (session('status') === 'theme-updated')
        <div class="alert alert-success small">テーマを更新しました。公開ページで確認してください。</div>
    @endif

    <p class="text-muted small mb-4">
        公開プロフィールページ <code>/u/{{ $profile->slug }}</code> の見た目を切り替えられます。
        プリセットを選んだあと、メインカラーだけ自分好みに上書きすることもできます。
    </p>

    <form method="post" action="{{ route('mypage.theme.update') }}">
        @csrf
        @method('patch')

        {{-- プリセット選択 --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">プリセットを選ぶ</h2>

                <div class="row g-3">
                    @foreach ($themes as $theme)
                        @php($selected = (int) old('theme_id', $profile->theme_id) === $theme->id)
                        <div class="col-12 col-sm-6 col-md-4">
                            <label class="d-block h-100" style="cursor: pointer;">
                                <input type="radio" name="theme_id" value="{{ $theme->id }}"
                                       class="visually-hidden theme-radio"
                                       data-theme-key="{{ $theme->key }}"
                                       data-default-color="{{ $theme->default_color }}"
                                       {{ $selected ? 'checked' : '' }}>
                                <div class="card h-100 theme-card {{ $selected ? 'border-primary' : '' }}"
                                     style="border-width: 2px;">
                                    <div class="card-body p-2">
                                        {{-- ライブプレビュー（ミニ） --}}
                                        <div class="theme-preview theme-{{ $theme->key }} mb-2"
                                             style="min-height: 96px;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center pt-public-avatar flex-shrink-0"
                                                     style="width: 28px; height: 28px; color: #fff; font-size: 0.75rem; font-weight: 700;">
                                                    A
                                                </div>
                                                <div class="small fw-bold text-truncate">サンプル</div>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge rounded-pill px-2 pt-public-tag" style="font-size: 0.65rem; font-weight: 600;">#推し</span>
                                                <span class="badge rounded-pill px-2 pt-public-tag" style="font-size: 0.65rem; font-weight: 600;">#音楽</span>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <div class="fw-bold small">{{ $theme->name }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">{{ $theme->key }}</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach

                    {{-- 「指定なし」 --}}
                    @php($noneSelected = !old('theme_id', $profile->theme_id))
                    <div class="col-12 col-sm-6 col-md-4">
                        <label class="d-block h-100" style="cursor: pointer;">
                            <input type="radio" name="theme_id" value=""
                                   class="visually-hidden theme-radio"
                                   data-theme-key="modern"
                                   data-default-color="#6366f1"
                                   {{ $noneSelected ? 'checked' : '' }}>
                            <div class="card h-100 theme-card {{ $noneSelected ? 'border-primary' : '' }}"
                                 style="border-width: 2px;">
                                <div class="card-body p-2 d-flex flex-column justify-content-center text-center"
                                     style="min-height: 156px;">
                                    <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                         style="width: 48px; height: 48px; background: #f3f4f6; color: #94a3b8; font-size: 1.25rem;">
                                        —
                                    </div>
                                    <div class="fw-bold small">指定なし</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">default</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('theme_id')" />
            </div>
        </div>

        {{-- メインカラー上書き --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">メインカラー（任意）</h2>
                <p class="text-muted small">
                    プリセットのアクセントカラーを上書きしたい時に使います。「リセット」を押すとテーマ既定の色に戻ります。
                </p>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <input type="color" id="theme_color_picker"
                           value="{{ old('theme_color', $profile->theme_color ?? '#6366f1') }}"
                           class="form-control form-control-color"
                           style="width: 4rem; height: 3rem;">
                    <input type="hidden" name="theme_color" id="theme_color_input"
                           value="{{ old('theme_color', $profile->theme_color) }}">
                    <code id="theme_color_display" class="small">
                        {{ old('theme_color', $profile->theme_color) ?: '（未設定）' }}
                    </code>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="theme_color_reset">
                        リセット
                    </button>
                </div>

                <x-input-error :messages="$errors->get('theme_color')" />
            </div>
        </div>

        {{-- 大きなライブプレビュー --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">ライブプレビュー</h2>
                <p class="text-muted small">
                    上で選んだテーマと色が、公開ページにどう反映されるかをそのまま確認できます。
                </p>

                @php($initialKey = $profile->theme?->key ?? 'modern')
                @php($initialAccent = $profile->theme_color)
                <div id="live-preview"
                     class="theme-preview theme-{{ $initialKey }}"
                     style="padding: 1.5rem; min-height: 240px; {{ $initialAccent ? "--pt-public-accent: {$initialAccent};" : '' }}">
                    <div class="text-center mb-3">
                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center pt-public-avatar"
                             style="width: 56px; height: 56px; color: #fff; font-size: 1.5rem; font-weight: 700;">
                            {{ mb_substr($profile->nickname, 0, 1) }}
                        </div>
                        <div class="fw-bold">{{ $profile->nickname }}</div>
                        <div class="small pt-public-muted">{{ '@' . $profile->slug }}</div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-center mb-3">
                        <span class="badge rounded-pill px-3 py-2 pt-public-tag" style="font-weight: 600;"><span class="opacity-75">#</span>推し</span>
                        <span class="badge rounded-pill px-3 py-2 pt-public-tag" style="font-weight: 600;"><span class="opacity-75">#</span>音楽</span>
                        <span class="badge rounded-pill px-3 py-2 pt-public-tag" style="font-weight: 600;"><span class="opacity-75">#</span>カフェ</span>
                    </div>

                    <div class="small">
                        <div class="fw-semibold pt-public-q mb-1">Q. 最近ハマっていることは？</div>
                        <div>サンプルの回答テキストがここに表示されます。</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">キャンセル</a>
            <x-primary-button>保存する</x-primary-button>
        </div>
    </form>

    @push('scripts')
        <script>
            (function () {
                const cards = document.querySelectorAll('.theme-card');
                const radios = document.querySelectorAll('.theme-radio');
                const preview = document.getElementById('live-preview');
                const picker = document.getElementById('theme_color_picker');
                const input = document.getElementById('theme_color_input');
                const display = document.getElementById('theme_color_display');
                const reset = document.getElementById('theme_color_reset');

                const applyAccent = (color) => {
                    if (color) {
                        preview.style.setProperty('--pt-public-accent', color);
                    } else {
                        preview.style.removeProperty('--pt-public-accent');
                    }
                };

                const switchTheme = (key) => {
                    // 既存の theme-<key> を除去（基盤クラス theme-preview は残す）して新しい key を付与
                    [...preview.classList].forEach(c => {
                        if (c.startsWith('theme-') && c !== 'theme-preview') {
                            preview.classList.remove(c);
                        }
                    });
                    preview.classList.add('theme-' + key);
                };

                // ラジオ変更：枠線とプレビュー切替
                radios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        cards.forEach(c => c.classList.remove('border-primary'));
                        const card = radio.closest('label').querySelector('.theme-card');
                        card?.classList.add('border-primary');
                        switchTheme(radio.dataset.themeKey);
                    });
                });

                // カラーピッカー：プレビューに即反映 + hidden input 反映
                picker?.addEventListener('input', () => {
                    input.value = picker.value;
                    display.textContent = picker.value;
                    applyAccent(picker.value);
                });

                // リセット：テーマ既定色に戻す
                reset?.addEventListener('click', () => {
                    input.value = '';
                    display.textContent = '（未設定）';
                    applyAccent(null);
                });
            })();
        </script>
    @endpush
</x-app-layout>
