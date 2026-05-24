<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // フォント：modern / mincho / garake / rounded
            $table->string('theme_font', 30)->nullable()->after('theme_color');
            // 背景色：#RRGGBB
            $table->string('theme_bg_color', 9)->nullable()->after('theme_font');
            // 背景パターン：none / gradient / dots / stripes / checker / stars / hearts
            $table->string('theme_bg_pattern', 30)->nullable()->after('theme_bg_color');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['theme_font', 'theme_bg_color', 'theme_bg_pattern']);
        });
    }
};
