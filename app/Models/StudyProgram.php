<?php

namespace App\Models;

use App\Enums\ClassSchedule;
use App\Enums\DegreeLevel;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'major_id',
        'degree_level',
        'degree_title',
        'program_type',
        'accreditation',
        'registration_fee',
        'first_payment',
        'monthly_installment',
        'original_monthly_installment',
        'schedules',
        'methods',
    ];

    protected function casts(): array
    {
        return [
            'degree_level' => DegreeLevel::class,
            'program_type' => ProgramType::class,
            'schedules' => AsEnumCollection::of(ClassSchedule::class),
            'methods' => AsEnumCollection::of(LearningMethod::class),
        ];
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function hasDiscount(): bool
    {
        return $this->original_monthly_installment !== null
            && $this->original_monthly_installment > $this->monthly_installment;
    }
}
