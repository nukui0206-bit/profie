<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['owner_user_id', 'body', 'sort_order', 'is_active', 'is_featured'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function isCustom(): bool
    {
        return $this->owner_user_id !== null;
    }

    /**
     * 運営定義の質問のみ（owner_user_id IS NULL）。
     */
    public function scopeOfficial($query)
    {
        return $query->whereNull('owner_user_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * 運営定義の質問のうち、よくある質問（is_featured = true）。
     * 入力フォームの上段に通常表示する 10 問程度。
     */
    public function scopeFeatured($query)
    {
        return $query->whereNull('owner_user_id')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order');
    }

    /**
     * 運営定義の質問のうち、よくある質問以外（is_featured = false）。
     * 入力フォームではアコーディオンに格納する。
     */
    public function scopeOthers($query)
    {
        return $query->whereNull('owner_user_id')
            ->where('is_active', true)
            ->where('is_featured', false)
            ->orderBy('sort_order');
    }

    /**
     * 後方互換：official と同義。
     */
    public function scopeActive($query)
    {
        return $query->official();
    }

    /**
     * あるユーザーのカスタム質問のみ。
     */
    public function scopeCustomFor($query, int $userId)
    {
        return $query->where('owner_user_id', $userId)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }
}
