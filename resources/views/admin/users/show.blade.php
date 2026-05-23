<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">ユーザー詳細</h1>
            <a href="{{ route('admin.users.index') }}" class="small">← 一覧に戻る</a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-info small">{{ session('status') }}</div>
    @endif

    {{-- アカウント基本情報 --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-transparent">
            <h2 class="h6 fw-bold mb-0"><i class="bi bi-person-circle"></i> アカウント情報</h2>
        </div>
        <div class="card-body">
            <dl class="row mb-0 small">
                <dt class="col-sm-3 text-muted">ID</dt>
                <dd class="col-sm-9">{{ $user->id }}</dd>

                <dt class="col-sm-3 text-muted">名前</dt>
                <dd class="col-sm-9">{{ $user->name }}</dd>

                <dt class="col-sm-3 text-muted">メール</dt>
                <dd class="col-sm-9">
                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    @if ($user->email_verified_at)
                        <span class="badge text-bg-success ms-2"><i class="bi bi-check-circle-fill"></i> 認証済 {{ $user->email_verified_at->format('Y/m/d') }}</span>
                    @else
                        <span class="badge text-bg-warning text-dark ms-2"><i class="bi bi-exclamation-circle"></i> 未認証</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">登録経路</dt>
                <dd class="col-sm-9">
                    @if ($user->google_id)
                        <i class="bi bi-google" style="color: #4285F4;"></i> Google 連携
                        <code class="small ms-2">{{ $user->google_id }}</code>
                    @else
                        <i class="bi bi-envelope"></i> メールアドレス
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">生年月日</dt>
                <dd class="col-sm-9">
                    {{ optional($user->birthdate)->format('Y/m/d') ?? '—' }}
                    @if ($user->birthdate)
                        <span class="text-muted ms-2">（{{ $user->birthdate->age }} 歳）</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">ロール</dt>
                <dd class="col-sm-9">
                    @if ($user->isAdmin())
                        <span class="badge text-bg-dark">admin</span>
                    @else
                        <span class="badge text-bg-light">user</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">状態</dt>
                <dd class="col-sm-9">
                    @if ($user->isActive())
                        <span class="badge text-bg-success">アクティブ</span>
                    @else
                        <span class="badge text-bg-danger">停止</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">登録日時</dt>
                <dd class="col-sm-9">{{ $user->created_at->format('Y/m/d H:i') }} <span class="text-muted ms-2">{{ $user->created_at->diffForHumans() }}</span></dd>

                <dt class="col-sm-3 text-muted">最終ログイン</dt>
                <dd class="col-sm-9">
                    @if ($user->last_login_at)
                        {{ $user->last_login_at->format('Y/m/d H:i') }} <span class="text-muted ms-2">{{ $user->last_login_at->diffForHumans() }}</span>
                    @else
                        <span class="text-muted">—（ログイン履歴なし）</span>
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    {{-- プロフィール統計 --}}
    @if ($stats)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-bar-chart-line"></i> プロフィール統計</h2>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4 col-md-2">
                        <div class="text-muted small">ページビュー</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['view_count']) }}</div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="text-muted small">いいね</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['like_count']) }}</div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="text-muted small">足あと</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['footprints_count']) }}</div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="text-muted small">回答数</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['answers_count']) }}</div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="text-muted small">タグ</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['tags_count']) }}</div>
                    </div>
                    <div class="col-4 col-md-2">
                        <div class="text-muted small">SNS</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['social_links_count']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- プロフィール詳細 --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-transparent">
            <h2 class="h6 fw-bold mb-0"><i class="bi bi-card-text"></i> 公開プロフィール</h2>
        </div>
        <div class="card-body">
            @if ($user->profile)
                <dl class="row mb-0 small">
                    <dt class="col-sm-3 text-muted">公開 URL</dt>
                    <dd class="col-sm-9">
                        <a href="{{ $user->profile->public_url }}" target="_blank" rel="noopener">
                            /u/{{ $user->profile->slug }}
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </dd>
                    <dt class="col-sm-3 text-muted">公開状態</dt>
                    <dd class="col-sm-9">
                        @if ($user->profile->is_published)
                            <span class="badge text-bg-success">公開中</span>
                        @else
                            <span class="badge text-bg-secondary">非公開</span>
                        @endif
                    </dd>
                    <dt class="col-sm-3 text-muted">ニックネーム</dt>
                    <dd class="col-sm-9">{{ $user->profile->nickname }}</dd>
                    <dt class="col-sm-3 text-muted">自己紹介</dt>
                    <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $user->profile->bio ?: '—' }}</dd>
                    <dt class="col-sm-3 text-muted">テーマ</dt>
                    <dd class="col-sm-9">
                        {{ $user->profile->theme?->name ?? '指定なし（モダン）' }}
                        @if ($user->profile->theme_color)
                            <span class="badge ms-2" style="background: {{ $user->profile->theme_color }}; color: #fff;">{{ $user->profile->theme_color }}</span>
                        @endif
                    </dd>
                </dl>
            @else
                <p class="text-muted small mb-0">プロフィールが作成されていません。</p>
            @endif
        </div>
    </div>

    {{-- SNS リンク --}}
    @if ($user->profile && $user->profile->socialLinks->isNotEmpty())
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-link-45deg"></i> SNS リンク ({{ $user->profile->socialLinks->count() }})</h2>
            </div>
            <div class="list-group list-group-flush">
                @foreach ($user->profile->socialLinks as $link)
                    <div class="list-group-item d-flex align-items-center gap-3 small">
                        <i class="bi {{ $link->iconClass() }} fs-5"></i>
                        <div class="flex-grow-1 text-truncate">
                            <div class="fw-semibold">{{ $link->platformLabel() }}</div>
                            <a href="{{ $link->url }}" target="_blank" rel="noopener" class="text-muted text-decoration-none">
                                {{ $link->url }}
                            </a>
                        </div>
                        @if ($link->label)
                            <span class="badge text-bg-light">{{ $link->label }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- 操作 --}}
    @unless ($user->isAdmin())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent">
                <h2 class="h6 fw-bold mb-0"><i class="bi bi-gear"></i> 運営操作</h2>
            </div>
            <div class="card-body">
                @if ($user->isActive())
                    <form method="post" action="{{ route('admin.users.suspend', $user) }}"
                          onsubmit="return confirm('このユーザーを停止しますか？停止中はログインできません。');">
                        @csrf @method('patch')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-pause-circle"></i> アカウントを停止
                        </button>
                    </form>
                @else
                    <form method="post" action="{{ route('admin.users.activate', $user) }}">
                        @csrf @method('patch')
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-play-circle"></i> アクティブに戻す
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endunless
</x-app-layout>
