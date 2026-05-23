<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use App\Services\Slug\SlugGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@profie.local');
        $password = env('ADMIN_PASSWORD', 'admin-temp-password');

        if (User::where('email', $email)->exists()) {
            $this->command?->info("Admin user already exists: {$email}");
            return;
        }

        $admin = User::create([
            'name' => '運営',
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        Profile::create([
            'user_id' => $admin->id,
            'slug' => app(SlugGenerator::class)->generateUnique('admin'),
            'nickname' => '運営',
            'is_published' => false,
        ]);

        $this->command?->info("Admin user created: {$email}");
        $this->command?->warn("Password is set from ADMIN_PASSWORD env (default: 'admin-temp-password'). 本番では必ず変更してください。");
    }
}
