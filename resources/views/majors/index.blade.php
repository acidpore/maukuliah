@extends('layouts.app')

@section('title', 'Daftar Jurusan')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Daftar jurusan</h1>
            <p class="page-subtitle">Temukan jurusan yang sesuai minat, bakat, dan prospek kariermu.</p>
            <p class="page-count">{{ $majors->total() }} jurusan ditemukan</p>
        </div>
    </div>

    <div class="container page-body">
        <div class="chips" aria-label="Filter kategori jurusan">
            <a class="chip {{ blank($category) ? 'is-active' : '' }}" href="{{ route('majors.index', ['q' => $query]) }}">Semua</a>
            @foreach ($categories as $cat)
                <a class="chip {{ $category === $cat ? 'is-active' : '' }}" href="{{ route('majors.index', ['category' => $cat, 'q' => $query]) }}">{{ $cat }}</a>
            @endforeach
        </div>

        <div class="toolbar">
            @include('partials.filter-bar', [
                'action' => route('majors.index'),
                'query' => $query,
                'placeholder' => 'Cari nama jurusan',
                'hidden' => ['category' => $category],
            ])
        </div>

        @if ($majors->isEmpty())
            @include('partials.empty-state', [
                'icon' => 'books',
                'title' => 'Tidak ada jurusan yang cocok',
                'text' => 'Coba ubah kata kunci atau kategori pencarianmu.',
                'actionLabel' => 'Reset pencarian',
                'actionUrl' => route('majors.index'),
            ])
        @else
            <div class="grid grid--majors">
                @foreach ($majors as $major)
                    @include('partials.major-card', ['major' => $major])
                @endforeach
            </div>
            {{ $majors->links('pagination.custom') }}
        @endif
    </div>
@endsection
