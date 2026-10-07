<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampusPhoto extends Model
{
    protected $fillable = [
        'campus_id',
        'path',
        'caption',
        'sort',
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function url(): string
    {
        return asset($this->path);
    }
}
