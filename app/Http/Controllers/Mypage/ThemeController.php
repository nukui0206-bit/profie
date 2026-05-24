<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function edit(): View
    {
        $profile = Auth::user()->profile;
        $themes = Theme::active()->get();

        return view('mypage.theme.edit', [
            'profile' => $profile,
            'themes' => $themes,
            'fontOptions' => Profile::THEME_FONTS,
            'patternOptions' => Profile::THEME_BG_PATTERNS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_id' => ['nullable', 'integer', 'exists:themes,id'],
            'theme_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_font' => ['nullable', 'in:' . implode(',', array_keys(Profile::THEME_FONTS))],
            'theme_bg_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_bg_pattern' => ['nullable', 'in:' . implode(',', array_keys(Profile::THEME_BG_PATTERNS))],
        ], [
            'theme_color.regex' => 'メインカラーは #RRGGBB 形式（HEX 7文字）で指定してください。',
            'theme_bg_color.regex' => '背景色は #RRGGBB 形式（HEX 7文字）で指定してください。',
        ]);

        $profile = Auth::user()->profile;
        $profile->theme_id = $request->input('theme_id') ?: null;
        $profile->theme_color = $request->input('theme_color') ?: null;
        $profile->theme_font = $request->input('theme_font') ?: null;
        $profile->theme_bg_color = $request->input('theme_bg_color') ?: null;
        $profile->theme_bg_pattern = $request->input('theme_bg_pattern') ?: null;
        $profile->save();

        return redirect()->route('mypage.theme.edit')->with('status', 'theme-updated');
    }
}
