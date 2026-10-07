<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/data/articles.php' as $article) {
            $daysAgo = $article['days_ago'];
            unset($article['days_ago']);

            Article::updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                $article + ['published_at' => now()->subDays($daysAgo)->startOfDay()->addHours(8)],
            );
        }
    }
}
