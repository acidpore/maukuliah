<?php

namespace App\Models;

use App\Enums\BrochureType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brochure extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'title',
        'type',
        'file_path',
    ];

    protected function casts(): array
    {
        return ['type' => BrochureType::class];
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }
}
