@extends('layouts.app')

@section('title', $major->name)
@section('description', Str::limit($major->description, 155))

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Jurusan', 'url' => route('majors.index')],
            ['label' => $major->name, 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container">
            <h1 class="detail-hero__title">{{ $major->name }}</h1>
            <div class="detail-hero__badges">
                @include('partials.badge', ['label' => $major->category, 'variant' => 'cat'])
                @include('partials.badge', ['label' => $campuses->count().' kampus menyediakan', 'variant' => 'outline'])
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Tentang jurusan</h2>
                    <div class="prose">
                        <p>{{ $major->description }}</p>
                    </div>
                </section>

                @if (! empty($major->courses))
                    <section class="content-panel">
                        <h2 class="content-panel__title">Mata kuliah</h2>
                        <ul class="tag-grid tag-grid--cols">
                            @foreach ($major->courses as $course)
                                <li><i class="ph ph-book-open" aria-hidden="true"></i> {{ $course }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($major->relationLoaded('careers') && $major->careers->isNotEmpty())
                    <section class="content-panel">
                        <h2 class="content-panel__title">Prospek karier</h2>
                        <div class="tag-grid tag-grid--cols">
                            @foreach ($major->careers as $career)
                                <a href="{{ route('careers.show', $career) }}"><i class="ph ph-briefcase" aria-hidden="true"></i> {{ $career->name }}</a>
                            @endforeach
                        </div>
                    </section>
                @elseif (! empty($major->career_prospects))
                    <section class="content-panel">
                        <h2 class="content-panel__title">Prospek karier</h2>
                        <ul class="tag-grid tag-grid--cols">
                            @foreach ($major->career_prospects as $career)
                                <li><i class="ph ph-briefcase" aria-hidden="true"></i> {{ $career }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Belum yakin memilih?</h2>
                    <p class="sidebar-card__text">Kerjakan tes potensi untuk melihat apakah jurusan ini cocok denganmu.</p>
                    <a class="btn btn--primary" href="{{ route('tests.index') }}">Mulai tes potensi</a>
                    <a class="btn btn--ghost" href="{{ route('campuses.index') }}">Jelajahi kampus</a>
                    @include('partials.favorite-button', ['type' => 'major', 'model' => $major])
                </div>
            </aside>
        </div>
    </div>

    <section class="section section--tint">
        <div class="container">
            @include('partials.section-head', ['title' => 'Kampus penyedia'])
            @if ($campuses->isEmpty())
                @include('partials.empty-state', [
                    'icon' => 'buildings',
                    'title' => 'Belum ada kampus penyedia',
                    'text' => 'Data kampus untuk jurusan ini sedang dilengkapi.',
                ])
            @else
                <div class="grid grid--campuses">
                    @foreach ($campuses as $campus)
                        @include('partials.campus-card', ['campus' => $campus])
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
