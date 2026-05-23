<?php

namespace App\Providers;

use App\Services\Storage\AvatarStorageInterface;
use App\Services\Storage\LocalAvatarStorage;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AvatarStorageInterface::class,
            LocalAvatarStorage::class,
        );
    }

    public function boot(): void
    {
        // ログイン時に users.last_login_at を自動更新
        Event::listen(function (Login $event) {
            $event->user->forceFill(['last_login_at' => now()])->save();
        });
    }
}
