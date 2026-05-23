<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Services\Slug\SlugGenerator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private const SESSION_KEY = 'oauth_google_pending';

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('Google OAuth callback failed: ' . $e->getMessage());

            return redirect()->route('login')->withErrors([
                'oauth' => 'Google ログインに失敗しました。もう一度お試しください。',
            ]);
        }

        // 1) 既に google_id で紐付け済みのユーザーを優先
        $user = User::where('google_id', $googleUser->getId())->first();
        if ($user) {
            return $this->signIn($user);
        }

        // 2) 同じメアドの既存ユーザーがいれば google_id を紐付けてそのままログイン
        $user = User::where('email', $googleUser->getEmail())->first();
        if ($user) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();

            return $this->signIn($user);
        }

        // 3) 完全新規 → birthdate + terms 入力ページに誘導
        session([
            self::SESSION_KEY => [
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'ユーザー'),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ],
        ]);

        return redirect()->route('oauth.google.complete');
    }

    public function showComplete(): View|RedirectResponse
    {
        if (! session()->has(self::SESSION_KEY)) {
            return redirect()->route('register');
        }

        return view('auth.social-complete', [
            'oauthData' => session(self::SESSION_KEY),
        ]);
    }

    public function completeRegistration(Request $request, SlugGenerator $slugGenerator): RedirectResponse
    {
        if (! session()->has(self::SESSION_KEY)) {
            return redirect()->route('register');
        }

        $oauth = session(self::SESSION_KEY);
        $maxBirthdate = Carbon::today()->subYears(13)->toDateString();

        $request->validate([
            'birthdate' => ['required', 'date', 'before_or_equal:' . $maxBirthdate],
            'terms' => ['accepted'],
        ], [
            'birthdate.before_or_equal' => '13歳未満の方はご利用いただけません。',
            'terms.accepted' => '利用規約・プライバシーポリシーに同意してください。',
        ]);

        // Google 認証直後に他経路でその email が登録されてしまう race condition 対策
        if (User::where('email', $oauth['email'])->orWhere('google_id', $oauth['id'])->exists()) {
            session()->forget(self::SESSION_KEY);

            return redirect()->route('login')->withErrors([
                'oauth' => 'このアカウントは既に登録済みです。ログインしてください。',
            ]);
        }

        $user = DB::transaction(function () use ($oauth, $request, $slugGenerator) {
            $user = User::create([
                'name' => mb_substr($oauth['name'], 0, 40),
                'email' => $oauth['email'],
                'google_id' => $oauth['id'],
                'password' => Hash::make(Str::random(60)),
                'birthdate' => $request->birthdate,
                'role' => User::ROLE_USER,
                'status' => User::STATUS_ACTIVE,
                'email_verified_at' => now(),
            ]);

            Profile::create([
                'user_id' => $user->id,
                'slug' => $slugGenerator->generateUnique($oauth['name']),
                'nickname' => mb_substr($oauth['name'], 0, 30),
                'is_published' => true,
            ]);

            return $user;
        });

        event(new Registered($user));
        session()->forget(self::SESSION_KEY);

        Auth::login($user, remember: true);

        return redirect()->route('dashboard');
    }

    private function signIn(User $user): RedirectResponse
    {
        if ($user->status !== User::STATUS_ACTIVE) {
            return redirect()->route('login')->withErrors([
                'oauth' => 'このアカウントは現在利用できません。',
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
