<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        $appName = config('app.name', 'Profie');
        $recipient = config('services.contact.recipient', 'contact@profie.me');
        $receivedAt = now()->format('Y/m/d H:i');

        // 1) 運営宛のお問い合わせ通知
        $adminMessage = <<<EOT
        [{$appName}] お問い合わせを受信しました

        受信日時：{$receivedAt}
        お名前　：{$validated['name']}
        メール　：{$validated['email']}

        ─── お問い合わせ内容 ───────────────
        {$validated['body']}
        ──────────────────────────────────────

        ※ このメールへの「返信」で、{$validated['name']} 様に直接返信できます。
        EOT;

        try {
            Mail::raw($adminMessage, function ($msg) use ($validated, $recipient) {
                $msg->to($recipient)
                    ->replyTo($validated['email'], $validated['name'])
                    ->subject('[Profie] お問い合わせを受信しました');
            });
        } catch (\Throwable $e) {
            // 運営宛が失敗したらユーザー側にも返さない（不整合回避）
            Log::error('Contact form (admin) mail failed: ' . $e->getMessage(), [
                'email' => $validated['email'],
            ]);

            return back()->withInput()->withErrors([
                'send' => 'メール送信に失敗しました。時間をおいて再度お試しください。',
            ]);
        }

        // 2) ユーザーへの自動返信
        $userMessage = <<<EOT
        {$validated['name']} 様

        このたびは Profie にお問い合わせいただきありがとうございます。
        以下の内容で受け付けました。

        ─── お問い合わせ内容 ───────────────
        {$validated['body']}
        ──────────────────────────────────────

        担当者より、おおむね 2〜3 営業日以内にご返信いたします。
        混雑時はお時間をいただく場合があります、ご了承ください。

        ※ このメールは送信専用です。このメールへの返信ではお問い合わせ
          にはなりませんので、ご返信は不要です。
          追加のお問い合わせは https://profie.me/contact または
          {$recipient} 宛に直接メールでご連絡ください。

        ──
        Profie 運営事務局
        https://profie.me/
        EOT;

        try {
            Mail::raw($userMessage, function ($msg) use ($validated) {
                $msg->to($validated['email'], $validated['name'])
                    ->subject('[Profie] お問い合わせを受け付けました');
            });
        } catch (\Throwable $e) {
            // 自動返信失敗は致命的ではないので、ログだけ残してユーザーには通常完了表示
            Log::warning('Contact form (auto-reply) mail failed: ' . $e->getMessage(), [
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('contact')->with('status', 'contact-sent');
    }
}
