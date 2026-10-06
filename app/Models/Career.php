<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Career extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'salary_min',
        'salary_max',
        'positions',
    ];

    protected function casts(): array
    {
        return ['positions' => 'array'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class);
    }

    public function salaryRange(): ?string
    {
        if ($this->salary_min === null || $this->salary_max === null) {
            return null;
        }

        return 'Rp'.number_format($this->salary_min, 0, ',', '.')
            .' - Rp'.number_format($this->salary_max, 0, ',', '.');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $query) use ($like) {
            $query->where('name', 'like', $like)
                ->orWhere('description', 'like', $like);
        });
    }
}
