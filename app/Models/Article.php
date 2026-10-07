<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'body',
        'author_name',
        'cover_icon',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    public function scopeOfCategory(Builder $query, ?string $category): Builder
    {
        return blank($category) ? $query : $query->where('category', $category);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $query) use ($like) {
            $query->where('title', 'like', $like)
                ->orWhere('excerpt', 'like', $like)
                ->orWhere('body', 'like', $like);
        });
    }

    public function categoryLabel(): string
    {
        return config("articles.categories.{$this->category}.label", Str::headline($this->category));
    }

    public function coverIcon(): string
    {
        return $this->cover_icon
            ?? config("articles.categories.{$this->category}.icon", config('articles.default_icon'));
    }

    public function publishedLabel(): string
    {
        return $this->published_at->translatedFormat('d M Y');
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count($this->body) / config('articles.words_per_minute')));
    }

    /**
     * @return array<int, string>
     */
    public function paragraphs(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', $this->body))));
    }
}
