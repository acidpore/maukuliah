<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    use HasFactory;

    public const TYPE_NEGERI = 'negeri';

    public const TYPE_SWASTA = 'swasta';

    public const TYPE_KEDINASAN = 'kedinasan';

    public const TYPES = [
        self::TYPE_NEGERI,
        self::TYPE_SWASTA,
        self::TYPE_KEDINASAN,
    ];

    public const TYPE_LABELS = [
        self::TYPE_NEGERI => 'Negeri',
        self::TYPE_SWASTA => 'Swasta',
        self::TYPE_KEDINASAN => 'Kedinasan',
    ];

    public const FORM_LABELS = [
        'universitas' => 'Universitas',
        'institut' => 'Institut',
        'sekolah-tinggi' => 'Sekolah Tinggi',
        'akademi' => 'Akademi',
        'politeknik' => 'Politeknik',
    ];

    public const FILTERABLE_COLUMNS = [
        'type',
        'form',
        'city',
        'province',
        'accreditation',
    ];

    protected $fillable = [
        'name',
        'slug',
        'type',
        'form',
        'city',
        'province',
        'accreditation',
        'description',
        'established_year',
        'website',
        'logo',
        'phone',
        'email',
        'address',
        'verification_status',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'verification_status' => VerificationStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Satu jurusan bisa punya beberapa baris program studi (jenjang atau tipe
     * program berbeda), jadi daftar jurusan dibuat unik.
     */
    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'study_programs')->distinct();
    }

    public function studyPrograms(): HasMany
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function admissionPeriods(): HasMany
    {
        return $this->hasMany(AdmissionPeriod::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return $this->verification_status === VerificationStatus::Verified;
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('verification_status', VerificationStatus::Verified->value);
    }

    public function scholarships(): HasMany
    {
        return $this->hasMany(Scholarship::class);
    }

    public function brochures(): HasMany
    {
        return $this->hasMany(Brochure::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CampusPhoto::class)->orderBy('sort');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function typeLabel(): string
    {
        return self::TYPE_LABELS[$this->type] ?? ucfirst($this->type);
    }

    public function formLabel(): string
    {
        return self::FORM_LABELS[$this->form] ?? ucfirst($this->form);
    }

    public function logoUrl(): ?string
    {
        if ($this->logo === null || ! is_file(public_path($this->logo))) {
            return null;
        }

        return asset($this->logo);
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', $this->name) ?: [];

        $letters = array_map(
            fn (string $word) => mb_substr($word, 0, 1),
            array_slice($words, 0, 3),
        );

        return mb_strtoupper(implode('', $letters));
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $query) use ($like) {
            $query->where('name', 'like', $like)
                ->orWhere('city', 'like', $like)
                ->orWhere('province', 'like', $like)
                ->orWhere('type', 'like', $like)
                ->orWhere('form', 'like', $like)
                ->orWhere('accreditation', 'like', $like);
        });
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        foreach (self::FILTERABLE_COLUMNS as $column) {
            if (blank($filters[$column] ?? null)) {
                continue;
            }

            $query->where($column, $filters[$column]);
        }

        return $query;
    }

    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'newest' => $query->orderByDesc('established_year'),
            default => $query->orderBy('name'),
        };
    }
}
