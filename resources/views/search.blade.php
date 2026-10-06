@extends('layouts.app')

@section('title', 'Hasil Pencarian')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Hasil Pencarian</h1>
            <p class="page-subtitle">Menampilkan hasil untuk "{{ $query }}".</p>
        </div>
    </div>

    <div class="container page-body">
        @if ($campuses->isEmpty() && $majors->isEmpty())
            <div class="empty">
                <h2 class="empty__title">Tidak ada hasil untuk "{{ $query }}"</h2>
                <p class="empty__text">Coba kata kunci yang lebih umum atau jelajahi langsung dari daftar.</p>
                <a class="btn btn--primary" href="{{ route('campuses.index') }}">Jelajahi Kampus</a>
            </div>
        @else
            @if ($campuses->isNotEmpty())
                <section>
                    <div class="section__head">
                        <h2 class="section__title">Kampus ({{ $campuses->count() }})</h2>
                    </div>
                    <div class="grid grid--campuses">
                        @foreach ($campuses as $campus)
                            @include('partials.campus-card', ['campus' => $campus])
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($majors->isNotEmpty())
                <section>
                    <div class="section__head">
                        <h2 class="section__title">Jurusan ({{ $majors->count() }})</h2>
                    </div>
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
