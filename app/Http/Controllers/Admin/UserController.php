<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Footprint;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = $this->buildQuery($request)
            ->paginate(30)
            ->withQueryString();

        return view('admin.users.index', array_merge(
            ['users' => $users],
            $this->filterState($request),
        ));
    }

    public function show(User $user): View
    {
        $user->load([
            'profile.theme',
            'profile.socialLinks',
            'profile.favorites',
        ]);

        $stats = null;
        if ($user->profile) {
            $stats = [
                'view_count' => (int) $user->profile->view_count,
                'like_count' => (int) $user->profile->like_count,
                'answers_count' => $user->profile->answers()->count(),
                'tags_count' => $user->profile->favorites->count(),
                'social_links_count' => $user->profile->socialLinks->count(),
                'footprints_count' => Footprint::where('profile_id', $user->profile->id)->count(),
            ];
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'profie-users-' . now()->format('Ymd-His') . '.csv';
        $query = $this->buildQuery($request)->with(['profile.theme', 'profile.socialLinks']);

        return new StreamedResponse(function () use ($query) {
            $output = fopen('php://output', 'w');
            // BOM を出力して Excel で UTF-8 を正しく表示
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'ID', '名前', 'メール', 'メール認証日', '登録経路', 'ロール', '状態',
                '生年月日', '登録日時', '最終ログイン',
                'プロフィール slug', '公開状態', 'ニックネーム', '自己紹介', 'テーマ',
                'PV', 'いいね数',
                'X (Twitter)', 'Instagram', 'TikTok', 'YouTube', 'その他リンク',
            ]);

            $query->orderByDesc('created_at')->chunk(100, function ($users) use ($output) {
                foreach ($users as $u) {
                    $links = $u->profile?->socialLinks->groupBy('platform') ?? collect();
                    fputcsv($output, [
                        $u->id,
                        $u->name,
                        $u->email,
                        optional($u->email_verified_at)->format('Y-m-d H:i') ?? '',
                        $u->google_id ? 'Google' : 'メアド',
                        $u->role,
                        $u->status,
                        optional($u->birthdate)->format('Y-m-d') ?? '',
                        $u->created_at->format('Y-m-d H:i'),
                        optional($u->last_login_at)->format('Y-m-d H:i') ?? '',
                        $u->profile?->slug ?? '',
                        $u->profile ? ($u->profile->is_published ? '公開' : '非公開') : '',
                        $u->profile?->nickname ?? '',
                        $u->profile?->bio ?? '',
                        $u->profile?->theme?->name ?? '',
                        $u->profile?->view_count ?? 0,
                        $u->profile?->like_count ?? 0,
                        $links->get(SocialLink::PLATFORM_X)?->pluck('url')->implode(' | ') ?? '',
                        $links->get(SocialLink::PLATFORM_INSTAGRAM)?->pluck('url')->implode(' | ') ?? '',
                        $links->get(SocialLink::PLATFORM_TIKTOK)?->pluck('url')->implode(' | ') ?? '',
                        $links->get(SocialLink::PLATFORM_YOUTUBE)?->pluck('url')->implode(' | ') ?? '',
                        $links->get(SocialLink::PLATFORM_OTHER)?->pluck('url')->implode(' | ') ?? '',
                    ]);
                }
            });

            fclose($output);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function suspend(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.show', $user)
                ->with('status', '管理者は停止できません。');
        }
        $user->update(['status' => User::STATUS_SUSPENDED]);
        return redirect()->route('admin.users.show', $user)
            ->with('status', "{$user->name} を停止しました。");
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['status' => User::STATUS_ACTIVE]);
        return redirect()->route('admin.users.show', $user)
            ->with('status', "{$user->name} を有効化しました。");
    }

    private function buildQuery(Request $request): Builder
    {
        $q = trim((string) $request->input('q', ''));
        $status = $request->input('status', '');
        $provider = $request->input('provider', '');
        $verified = $request->input('verified', '');
        $sort = $request->input('sort', 'created_desc');

        $query = User::query()->with(['profile.theme', 'profile.socialLinks']);

        if ($q !== '') {
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhereHas('profile', fn ($p) => $p->where('slug', 'like', "%{$q}%"));
            });
        }

        if (in_array($status, [User::STATUS_ACTIVE, User::STATUS_SUSPENDED], true)) {
            $query->where('status', $status);
        }

        if ($provider === 'google') {
            $query->whereNotNull('google_id');
        } elseif ($provider === 'email') {
            $query->whereNull('google_id');
        }

        if ($verified === 'yes') {
            $query->whereNotNull('email_verified_at');
        } elseif ($verified === 'no') {
            $query->whereNull('email_verified_at');
        }

        return match ($sort) {
            'created_asc' => $query->orderBy('created_at', 'asc'),
            'login_desc' => $query->orderByRaw('last_login_at IS NULL, last_login_at DESC'),
            default => $query->orderBy('created_at', 'desc'),
        };
    }

    private function filterState(Request $request): array
    {
        return [
            'q' => trim((string) $request->input('q', '')),
            'status' => $request->input('status', ''),
            'provider' => $request->input('provider', ''),
            'verified' => $request->input('verified', ''),
            'sort' => $request->input('sort', 'created_desc'),
        ];
    }
}
