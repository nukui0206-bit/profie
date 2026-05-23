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
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, SlugGenerator $slugGenerator): RedirectResponse
    {
        // 生年月日が年/月/日 select から送られてきた場合は YYYY-MM-DD に組み立て
        if (! $request->filled('birthdate') && $request->filled(['birthdate_year', 'birthdate_month', 'birthdate_day'])) {
            $request->merge([
                'birthdate' => sprintf(
                    '%04d-%02d-%02d',
                    (int) $request->input('birthdate_year'),
                    (int) $request->input('birthdate_month'),
                    (int) $request->input('birthdate_day'),
                ),
            ]);
        }

        $maxBirthdate = Carbon::today()->subYears(13)->toDateString();

        $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'birthdate' => ['required', 'date', 'before_or_equal:'.$maxBirthdate],
            'terms' => ['accepted'],
        ], [
            'birthdate.before_or_equal' => '13歳未満の方はご利用いただけません。',
            'terms.accepted' => '利用規約・プライバシーポリシーに同意してください。',
        ]);

        $user = DB::transaction(function () use ($request, $slugGenerator) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'birthdate' => $request->birthdate,
                'role' => User::ROLE_USER,
                'status' => User::STATUS_ACTIVE,
            ]);

            Profile::create([
                'user_id' => $user->id,
                'slug' => $slugGenerator->generateUnique($request->name),
                'nickname' => $request->name,
                'is_published' => true,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
