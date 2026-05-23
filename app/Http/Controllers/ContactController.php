<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('static.contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:255'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required' => 'お名前を入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'body.required' => 'お問い合わせ内容を入力してください。',
            'body.min' => 'お問い合わせ内容は 10 文字以上で入力してください。',
            'body.max' => 'お問い合わせ内容は 2000 文字以内で入力してください。',
        ]);

        $message = sprintf(
            "[%s] からのお問い合わせ\n\nお名前：%s\nメール：%s\n\n--- 内容 ---\n%s\n",
            config('app.name', 'Profie'),
            $validated['name'],
            $validated['email'],
            $validated['body'],
        );

        // 運営宛にメール送信。MAIL_MAILER=log の場合は storage/logs/laravel.log に出力される。
        Mail::raw($message, function ($msg) use ($validated) {
            $msg->to(config('mail.from.address'))
                ->replyTo($validated['email'], $validated['name'])
                ->subject('[Profie] お問い合わせを受信しました');
        });

        return redirect()->route('contact')->with('status', 'contact-sent');
    }
}
