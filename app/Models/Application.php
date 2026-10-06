<?php

namespace App\Models;

use App\Enums\ClassSchedule;
use App\Enums\LastEducation;
use App\Enums\LeadStatus;
use App\Enums\ProgramType;
use App\Enums\SourceInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campus_id',
        'major_id',
        'full_name',
        'email',
        'whatsapp',
        'last_education',
        'region',
        'program_type',
        'schedule',
        'source_info',
        'accepted_terms',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'last_education' => LastEducation::class,
            'program_type' => ProgramType::class,
            'schedule' => ClassSchedule::class,
            'source_info' => SourceInfo::class,
            'status' => LeadStatus::class,
            'accepted_terms' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function referral(): HasOne
    {
        return $this->hasOne(AffiliateReferral::class);
    }
}
