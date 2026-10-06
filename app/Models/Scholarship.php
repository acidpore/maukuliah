<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'name',
        'slug',
        'description',
        'provider',
        'start_date',
        'end_date',
        'registration_url',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function isOpen(): bool
    {
        return today()->between($this->start_date, $this->end_date);
    }

    public function periodLabel(): string
    {
        return $this->start_date->translatedFormat('d M Y')
            .' - '.$this->end_date->translatedFormat('d M Y');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $query) use ($like) {
            $query->where('name', 'like', $like)
                ->orWhere('provider', 'like', $like);
        });
    }

    public function scopeUpcomingFirst(Builder $query): Builder
    {
        return $query->orderBy('end_date');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today());
    }
}
