@extends('layouts.app')

@section('title', $article->title)
@section('description', $article->excerpt)

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Artikel', 'url' => route('articles.index')],
            ['label' => $article->title, 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container">
            <div class="detail-hero__badges">
                @include('partials.badge', ['label' => $article->categoryLabel(), 'variant' => 'outline'])
            </div>
            <h1 class="detail-hero__title">{{ $article->title }}</h1>
            <p class="detail-hero__where">
                {{ $article->author_name }}
                <span aria-hidden="true">/</span>
                {{ $article->publishedLabel() }}
                <span aria-hidden="true">/</span>
                {{ $article->readingMinutes() }} menit baca
            </p>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <article class="content-panel">
                <div class="prose prose--article">
                    @foreach ($article->paragraphs() as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </article>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Bagikan artikel</h2>
                    <div class="copy-field">
                        <input class="field__control" type="text" readonly value="{{ route('articles.show', $article) }}" aria-label="Tautan artikel" data-copy-source>
                        <button class="btn btn--primary btn--sm" type="button" data-copy-button>Salin</button>
                    </div>
                </div>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Siap memilih kampus?</h2>
                    <p class="sidebar-card__text">Bandingkan program, jadwal, dan biaya dari kampus terverifikasi.</p>
                    <a class="btn btn--primary" href="{{ route('campuses.index') }}">Cari kampus</a>
                    <a class="btn btn--ghost" href="{{ route('tests.index') }}">Tes potensi</a>
                </div>
            </aside>
        </div>

        @if ($relatedArticles->isNotEmpty())
            <section class="section">
                @include('partials.section-head', [
                    'title' => 'Artikel terkait',
                    'linkLabel' => 'Semua artikel',
                    'linkUrl' => route('articles.index'),
                ])
                <div class="grid grid--articles">
                    @foreach ($relatedArticles as $related)
                        @include('partials.article-card', ['article' => $related])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
