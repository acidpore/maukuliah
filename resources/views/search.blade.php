@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Hasil pencarian</h1>
            <p class="page-subtitle">Menampilkan hasil untuk "{{ $query }}".</p>
        </div>
    </div>

    <div class="container page-body">
        <div class="toolbar">
            @include('partials.filter-bar', [
                'action' => route('search'),
                'query' => $query,
                'placeholder' => 'Cari kampus, jurusan, atau kota',
            ])
        </div>

        @if ($campuses->isEmpty() && $majors->isEmpty())
            @include('partials.empty-state', [
                'title' => 'Tidak ada hasil untuk "'.$query.'"',
                'text' => 'Coba kata kunci yang lebih umum atau jelajahi langsung dari daftar.',
                'actionLabel' => 'Jelajahi kampus',
                'actionUrl' => route('campuses.index'),
            ])
        @else
            @if ($campuses->isNotEmpty())
                <section class="search-group">
                    @include('partials.section-head', ['title' => 'Kampus ('.$campuses->count().')'])
                    <div class="grid grid--campuses">
                        @foreach ($campuses as $campus)
                            @include('partials.campus-card', ['campus' => $campus])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($majors->isNotEmpty())
                <section class="search-group">
                    @include('partials.section-head', ['title' => 'Jurusan ('.$majors->count().')'])
                    <div class="grid grid--majors">
                        @foreach ($majors as $major)
                            @include('partials.major-card', ['major' => $major])
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </div>
@endsection
