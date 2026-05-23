<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">質問への回答</h1>
            <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> 公開ページを開く
            </a>
        </div>
    </x-slot>

    @if (session('status'))
        @php($msg = match(session('status')) {
            'answers-updated' => '回答を保存しました。',
            'custom-added' => 'オリジナル質問を追加しました。',
            'custom-updated' => 'オリジナル質問を更新しました。',
            'custom-deleted' => 'オリジナル質問を削除しました。',
            default => '保存しました。',
        })
        <div class="alert alert-success small">{{ $msg }}</div>
    @endif

    {{-- 運営質問の回答 --}}
    <h2 class="h6 fw-bold text-muted mb-3" style="letter-spacing: 0.05em;">公式の質問</h2>

    <p class="text-muted small mb-4">
        運営が用意した質問にあなたの言葉で答えてください。
        空欄のまま保存すると、その質問は公開ページに表示されません（500文字以内）。
    </p>

    <form method="post" action="{{ route('mypage.answers.update') }}">
        @csrf
        @method('patch')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                @foreach ($officialQuestions as $idx => $q)
                    <div class="mb-4 {{ $idx === count($officialQuestions) - 1 ? 'mb-0' : '' }}">
                        <label for="answer-{{ $q->id }}" class="form-label fw-semibold">
                            <span class="text-muted small me-2">Q{{ $loop->iteration }}.</span>
                            {{ $q->body }}
                        </label>
                        <textarea id="answer-{{ $q->id }}"
                                  name="answers[{{ $q->id }}]"
                                  rows="2" maxlength="500"
                                  class="form-control"
                                  placeholder="（空欄のままでもOK）">{{ old("answers.{$q->id}", optional($answers->get($q->id))->body) }}</textarea>
                        <x-input-error :messages="$errors->get('answers.' . $q->id)" />
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <x-primary-button>公式質問の回答を保存</x-primary-button>
        </div>
    </form>

    {{-- オリジナル質問 --}}
    <h2 class="h6 fw-bold text-muted mb-3" style="letter-spacing: 0.05em;">あなたのオリジナル質問</h2>

    <p class="text-muted small mb-4">
        自分で質問を作って、自分で答えるオリジナル Q&A を追加できます。<br>
        運営の質問と同じく、公開ページに表示されます。
    </p>

    {{-- 既存のカスタム質問リスト --}}
    @if ($customQuestions->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                @foreach ($customQuestions as $cq)
                    @php($currentAnswer = optional($answers->get($cq->id))->body)
                    <form method="post" action="{{ route('mypage.answers.custom.update', $cq) }}"
                          class="pb-3 mb-3 {{ $loop->last ? 'pb-0 mb-0' : 'border-bottom' }}">
                        @csrf
                        @method('patch')
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">質問</label>
                            <input type="text" name="body" maxlength="120" required
                                   value="{{ old('body', $cq->body) }}"
                                   class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-semibold small">回答</label>
                            <textarea name="answer" rows="2" maxlength="500" required
                                      class="form-control">{{ old('answer', $currentAnswer) }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">更新</button>
                            <button type="submit"
                                    formaction="{{ route('mypage.answers.custom.destroy', $cq) }}"
                                    formmethod="post"
                                    onclick="event.stopPropagation(); if(!confirm('このオリジナル質問を削除しますか？')) return false;"
                                    class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i> 削除
                            </button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
        {{-- 削除専用フォーム --}}
        @foreach ($customQuestions as $cq)
            <form id="delete-custom-{{ $cq->id }}" method="post" action="{{ route('mypage.answers.custom.destroy', $cq) }}" class="d-none">
                @csrf
                @method('delete')
            </form>
        @endforeach
    @else
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-muted small text-center py-4">
                まだオリジナル質問がありません。下のフォームから追加してみましょう。
            </div>
        </div>
    @endif

    {{-- カスタム質問追加フォーム --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h3 class="h6 fw-bold mb-3">オリジナル質問を追加</h3>
            <form method="post" action="{{ route('mypage.answers.custom.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">質問</label>
                    <input type="text" name="body" maxlength="120" required
                           value="{{ old('body') }}"
                           placeholder="例：好きなマンガは？／一番影響を受けた言葉は？"
                           class="form-control">
                    <x-input-error :messages="$errors->get('body')" />
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">回答</label>
                    <textarea name="answer" rows="3" maxlength="500" required
                              class="form-control"
                              placeholder="あなたの答えを入力してください。">{{ old('answer') }}</textarea>
                    <x-input-error :messages="$errors->get('answer')" />
                </div>
                <div class="d-flex justify-content-end">
                    <x-primary-button>
                        <i class="bi bi-plus-lg"></i> 質問を追加
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 削除ボタンの formaction submit を hidden form 経由にする（method=DELETE 用）
        document.querySelectorAll('button[formaction]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (!confirm('このオリジナル質問を削除しますか？')) return;
                const action = btn.getAttribute('formaction');
                const id = action.split('/').pop();
                document.getElementById('delete-custom-' + id)?.submit();
            });
        });
    </script>
</x-app-layout>
