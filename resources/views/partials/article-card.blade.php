{{-- Props: $article --}}
<a class="article-card" href="{{ route('articles.show', $article) }}">
    <span class="article-card__cover" aria-hidden="true">
        <i class="ph ph-{{ $article->coverIcon() }}"></i>
    </span>
    <span class="article-card__body">
        @include('partials.badge', ['label' => $article->categoryLabel(), 'variant' => 'cat'])
        <h3 class="article-card__title">{{ $article->title }}</h3>
        <p class="article-card__excerpt">{{ $article->excerpt }}</p>
        <span class="article-card__meta">
            {{ $article->publishedLabel() }}
            <span aria-hidden="true">/</span>
            {{ $article->readingMinutes() }} menit baca
        </span>
    </span>
</a>
