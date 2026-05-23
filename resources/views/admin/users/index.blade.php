<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">ユーザー一覧</h1>
            <a href="{{ route('admin.dashboard') }}" class="small">← 管理ダッシュボード</a>
        </div>
    </x-slot>

    <form method="get" class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <input type="text" name="q" value="{{ $q }}" placeholder="名前 / メアド / slug" class="form-control">
                </div>
                <div class="col-6 col-md-2">
                    <select name="status" class="form-select">
                        <option value="">状態：すべて</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>アクティブ</option>
                        <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>停止中</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="provider" class="form-select">
                        <option value="">登録：すべて</option>
                        <option value="google" {{ $provider === 'google' ? 'selected' : '' }}>Google</option>
                        <option value="email" {{ $provider === 'email' ? 'selected' : '' }}>メアド</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="verified" class="form-select">
                        <option value="">認証：すべて</option>
                        <option value="yes" {{ $verified === 'yes' ? 'selected' : '' }}>認証済</option>
                        <option value="no" {{ $verified === 'no' ? 'selected' : '' }}>未認証</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="sort" class="form-select">
                        <option value="created_desc" {{ $sort === 'created_desc' ? 'selected' : '' }}>新しい順</option>
                        <option value="created_asc" {{ $sort === 'created_asc' ? 'selected' : '' }}>古い順</option>
                        <option value="login_desc" {{ $sort === 'login_desc' ? 'selected' : '' }}>最終ログイン順</option>
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <div class="small text-muted">
                    全 {{ number_format($users->total()) }} 件 / 現在 {{ $users->firstItem() ?? 0 }}〜{{ $users->lastItem() ?? 0 }} 件目
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">条件クリア</a>
                    <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-download"></i> CSV
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">検索</button>
                </div>
            </div>
        </div>
    </form>

    @if ($users->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted small">該当するユーザーはいません。</div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                    <thead>
                        <tr class="small text-muted">
                            <th>ID</th>
                            <th>名前</th>
                            <th>メール</th>
                            <th title="登録経路">登録</th>
                            <th title="メール認証">認証</th>
                            <th>ロール</th>
                            <th>状態</th>
                            <th>登録日</th>
                            <th>最終ログイン</th>
                            <th class="text-end" title="ページビュー">PV</th>
                            <th class="text-end" title="いいね数">♡</th>
                            <th title="SNSリンク数">SNS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr>
                                <td class="text-muted">{{ $u->id }}</td>
                                <td>
                                    <div>{{ $u->name }}</div>
                                    @if ($u->profile)
                                        <a href="{{ $u->profile->public_url }}" target="_blank" rel="noopener" class="small text-muted text-decoration-none">
                                            /u/{{ $u->profile->slug }}
                                            @if (! $u->profile->is_published)<span class="badge text-bg-secondary ms-1">非</span>@endif
                                        </a>
                                    @endif
                                </td>
                                <td class="small text-truncate" style="max-width: 200px;">{{ $u->email }}</td>
                                <td>
                                    @if ($u->google_id)
                                        <i class="bi bi-google" title="Google 登録" style="color: #4285F4;"></i>
                                    @else
                                        <i class="bi bi-envelope" title="メアド登録" style="color: #6b7280;"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($u->email_verified_at)
                                        <i class="bi bi-check-circle-fill text-success" title="認証済 {{ $u->email_verified_at->format('Y/m/d') }}"></i>
                                    @else
                                        <i class="bi bi-exclamation-circle text-warning" title="未認証"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($u->isAdmin())
                                        <span class="badge text-bg-dark">admin</span>
                                    @else
                                        <span class="badge text-bg-light">user</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($u->isActive())
                                        <span class="badge text-bg-success">ON</span>
                                    @else
                                        <span class="badge text-bg-danger">停止</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $u->created_at->format('m/d') }}</td>
                                <td class="small text-muted">
                                    {{ $u->last_login_at ? $u->last_login_at->diffForHumans() : '—' }}
                                </td>
                                <td class="text-end small">{{ number_format($u->profile?->view_count ?? 0) }}</td>
                                <td class="text-end small">{{ number_format($u->profile?->like_count ?? 0) }}</td>
                                <td class="text-center small">{{ $u->profile?->socialLinks->count() ?? 0 }}</td>
                                <td><a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-primary">詳細</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    @endif
</x-app-layout>
