@extends('layouts.app')

@section('title', 'Artikel')
@section('description', 'Panduan memilih kampus dan jurusan, info beasiswa, biaya kuliah, dan tips kuliah untuk calon mahasiswa.')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Artikel</h1>
            <p class="page-subtitle">Panduan dan tips seputar kampus, jurusan, beasiswa, dan biaya kuliah.</p>
            <p class="page-count">{{ $articles->total() }} artikel ditemukan</p>
        </div>
    </div>

    <div class="container page-body">
        <div class="toolbar">
            @include('partials.filter-bar', [
                'action' => route('articles.index'),
                'query' => $query,
                'placeholder' => 'Cari judul artikel',
                'hidden' => array_filter(['category' => $category]),
            ])
        </div>

        <nav class="chips" aria-label="Kategori artikel">
            <a class="chip {{ $category === null ? 'is-active' : '' }}" href="{{ route('articles.index', array_filter(['q' => $query])) }}">Semua</a>
            @foreach ($categories as $key => $item)
                <a class="chip {{ $category === $key ? 'is-active' : '' }}" href="{{ route('articles.index', array_filter(['q' => $query, 'category' => $key])) }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        @if ($articles->isEmpty())
            @include('partials.empty-state', [
                'icon' => 'newspaper',
                'title' => 'Belum ada artikel',
                'text' => 'Tidak ada artikel yang cocok dengan pencarian atau kategori ini.',
                'actionLabel' => 'Lihat semua artikel',
                'actionUrl' => route('articles.index'),
            ])
        @else
            <div class="grid grid--articles">
                @foreach ($articles as $article)
                    @include('partials.article-card', ['article' => $article])
                @endforeach
            </div>
            {{ $articles->links('pagination.custom') }}
        @endif
    </div>
@endsection
