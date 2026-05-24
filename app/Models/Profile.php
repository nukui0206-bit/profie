<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'nickname',
        'avatar_path',
        'bio',
        'theme_id',
        'theme_color',
        'theme_font',
        'theme_bg_color',
        'theme_bg_pattern',
        'view_count',
        'like_count',
        'is_published',
    ];

    public const THEME_FONTS = [
        'modern' => 'モダン（標準）',
        'mincho' => '明朝（落ち着き）',
        'garake' => 'ガラケー風（等幅）',
    ];

    public const THEME_BG_PATTERNS = [
        'none' => '無地',
        'gradient' => 'グラデ（テーマ標準）',
        'dots' => 'ドット',
        'stripes' => 'ストライプ',
        'checker' => '市松',
        'stars' => '星キラキラ',
        'hearts' => 'ハート',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'view_count' => 'integer',
            'like_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class)->orderBy('sort_order');
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort_order');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function footprints(): HasMany
    {
        return $this->hasMany(Footprint::class);
    }

    public function isLikedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getPublicUrlAttribute(): string
    {
        return route('public.profile', ['slug' => $this->slug]);
    }
}
