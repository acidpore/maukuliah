<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_only_published_articles_newest_first(): void
    {
        $older = $this->article(['title' => 'Artikel Lama', 'published_at' => now()->subDays(5)]);
        $newer = $this->article(['title' => 'Artikel Baru', 'published_at' => now()->subDay()]);
        $this->article(['title' => 'Artikel Draf', 'published_at' => null]);
        $this->article(['title' => 'Artikel Terjadwal', 'published_at' => now()->addDay()]);

        $this->get(route('articles.index'))
            ->assertOk()
            ->assertSeeInOrder([$newer->title, $older->title])
            ->assertDontSee('Artikel Draf')
            ->assertDontSee('Artikel Terjadwal');
    }

    public function test_index_searches_title(): void
    {
        $this->article(['title' => 'Panduan Beasiswa']);
        $this->article(['title' => 'Tips Tryout']);

        $this->get(route('articles.index', ['q' => 'beasiswa']))
            ->assertOk()
            ->assertSee('Panduan Beasiswa')
            ->assertDontSee('Tips Tryout');
    }

    public function test_index_filters_by_category(): void
    {
        $this->article(['title' => 'Soal Karier', 'category' => 'karier']);
        $this->article(['title' => 'Soal Kampus', 'category' => 'kampus']);

        $this->get(route('articles.index', ['category' => 'karier']))
            ->assertOk()
            ->assertSee('Soal Karier')
            ->assertDontSee('Soal Kampus');
    }

    public function test_index_rejects_unknown_category(): void
    {
        $this->get(route('articles.index', ['category' => 'tidak-ada']))
            ->assertSessionHasErrors('category');
    }

    public function test_index_shows_empty_state_when_nothing_matches(): void
    {
        $this->get(route('articles.index', ['q' => 'tidak-ada-hasil']))
            ->assertOk()
            ->assertSee('Belum ada artikel');
    }

    public function test_show_renders_article_with_related_articles(): void
    {
        $article = $this->article(['title' => 'Artikel Utama', 'category' => 'jurusan', 'body' => "Paragraf satu.\n\nParagraf dua."]);
        $this->article(['title' => 'Artikel Serumpun', 'category' => 'jurusan']);
        $this->article(['title' => 'Artikel Lain Kategori', 'category' => 'karier']);

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('Artikel Utama')
            ->assertSee('Paragraf satu.')
            ->assertSee('Paragraf dua.')
            ->assertSee('Artikel Serumpun')
            ->assertDontSee('Artikel Lain Kategori');
    }

    public function test_show_is_not_found_for_unknown_unpublished_or_scheduled_article(): void
    {
        $draft = $this->article(['published_at' => null]);
        $scheduled = $this->article(['published_at' => now()->addDay()]);

        $this->get(route('articles.show', 'tidak-ada'))->assertNotFound();
        $this->get(route('articles.show', $draft))->assertNotFound();
        $this->get(route('articles.show', $scheduled))->assertNotFound();
    }

    public function test_home_shows_latest_articles(): void
    {
        $this->article(['title' => 'Artikel Beranda']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Artikel terbaru')
            ->assertSee('Artikel Beranda');
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function article(array $attributes = []): Article
    {
        static $counter = 0;
        $counter++;

        return Article::create($attributes + [
            'title' => "Artikel {$counter}",
            'slug' => "artikel-{$counter}",
            'category' => 'kampus',
            'excerpt' => 'Ringkasan artikel.',
            'body' => 'Isi artikel.',
            'author_name' => 'Tim Redaksi',
            'published_at' => now()->subDay(),
        ]);
    }
}
