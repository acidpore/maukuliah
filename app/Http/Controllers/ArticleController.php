<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleFilterRequest;
use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(ArticleFilterRequest $request): View
    {
        $query = $request->term();
        $category = $request->category();

        $articles = Article::query()
            ->published()
            ->ofCategory($category)
            ->search($query)
            ->latestFirst()
            ->paginate(config('articles.per_page'))
            ->withQueryString();

        return view('articles.index', [
            'articles' => $articles,
            'query' => $query,
            'category' => $category,
            'categories' => config('articles.categories'),
        ]);
    }

    public function show(Article $article): View
    {
        abort_unless($article->published_at !== null && $article->published_at->isPast(), 404);

        $relatedArticles = Article::query()
            ->published()
            ->ofCategory($article->category)
            ->whereKeyNot($article->getKey())
            ->latestFirst()
            ->limit(config('articles.related_count'))
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
