<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResult extends Model
{
    public const TYPE_RIASEC = 'riasec';

    public const TYPE_LEARNING_STYLE = 'learning_style';

    public const TYPE_MBTI = 'mbti';

    protected $fillable = [
        'user_id',
        'test_type',
        'result',
    ];

    protected function casts(): array
    {
        return ['result' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
