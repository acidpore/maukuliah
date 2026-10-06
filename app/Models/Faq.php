<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    public const SCOPE_GLOBAL = 'global';

    public const SCOPE_PATH = 'path';

    protected $fillable = [
        'scope_type',
        'scope_key',
        'question',
        'answer',
        'sort',
    ];
}
