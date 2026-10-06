<?php

namespace App\Models;

use App\Enums\ReferralStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AffiliateReferral extends Model
{
    protected $fillable = [
        'affiliate_id',
        'application_id',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReferralStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function commission(): HasOne
    {
        return $this->hasOne(Commission::class);
    }
}
