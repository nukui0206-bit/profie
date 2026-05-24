<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Theme;
use App\Models\User;
use App\Services\Footprint\FootprintRecorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function show(string $slug, Request $request, FootprintRecorder $footprintRecorder): View
    {
        $profile = Profile::where('slug', $slug)
            ->whereHas('user', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->with([
                'user',
                'theme',
                // 回答は question を eager load。
                // 公開する質問は: 運営質問（owner_user_id NULL）または プロフ所有者のカスタム質問。
                'answers' => fn ($q) => $q->with('question')
                    ->whereHas('question', function ($qq) {
                        $qq->where('is_active', true);
                    }),
                'favorites',
                'socialLinks',
            ])
            ->firstOrFail();

        // 非公開プロフィールは「本人」または「管理者」だけ閲覧可
        if (! $profile->is_published) {
            $viewer = Auth::user();
            if (! $viewer || ($viewer->id !== $profile->user_id && ! $viewer->isAdmin())) {
                abort(404);
            }
        }

        // 公式デモプロフィール (slug=demo) のときだけ、?theme=xxx で表示テーマを差し替え。
        // DB は変更せず、View レイヤだけ override する。
        $availableThemes = collect();
        $activeThemeKey = optional($profile->theme)->key;
        if ($profile->slug === 'demo') {
            $availableThemes = Theme::where('is_active', true)->orderBy('id')->get();
            $themeKey = $request->query('theme');
            if ($themeKey) {
                $overrideTheme = $availableThemes->firstWhere('key', $themeKey);
                if ($overrideTheme) {
                    $profile->setRelation('theme', $overrideTheme);
                    $profile->theme_id = $overrideTheme->id;
                    $profile->theme_color = null;
                    $activeThemeKey = $overrideTheme->key;
                }
            }
        }

        // ページビュー（PV）は閲覧ごとに increment（公式デモは集計対象外）
        if ($profile->slug !== 'demo') {
            $profile->increment('view_count');
        }

        // 足あと（UU 寄り）は 24h 集約。ログイン者かつ自分以外のみ。
        if (Auth::check()) {
            $footprintRecorder->record(Auth::user(), $profile);
        }

        $isLiked = $profile->isLikedBy(Auth::user());

        return view('public.profile', compact('profile', 'isLiked', 'availableThemes', 'activeThemeKey'));
    }
}
