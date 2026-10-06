<?php

namespace App\Models;

use App\Enums\AffiliateCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'category',
    ];

    protected function casts(): array
    {
        return ['category' => AffiliateCategory::class];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(AffiliateReferral::class);
    }

    public function commissions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Commission::class,
            AffiliateReferral::class,
            'affiliate_id',
            'affiliate_referral_id',
        );
    }
}
