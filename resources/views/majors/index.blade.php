@extends('layouts.app')

@section('title', 'Daftar Jurusan')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Daftar Jurusan</h1>
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
            <form class="toolbar__search" action="{{ route('majors.index') }}" method="GET" role="search">
                <input type="hidden" name="category" value="{{ $category }}">
                <input class="toolbar__search-input" type="search" name="q" value="{{ $query }}" placeholder="Cari nama jurusan..." aria-label="Cari jurusan">
                <button class="btn btn--primary btn--sm" type="submit">Cari</button>
            </form>
        </div>

        @if ($majors->isEmpty())
            <div class="empty">
                <h2 class="empty__title">Tidak ada jurusan yang cocok</h2>
                <p class="empty__text">Coba ubah kata kunci atau kategori pencarianmu.</p>
                <a class="btn btn--primary" href="{{ route('majors.index') }}">Reset Pencarian</a>
            </div>
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
