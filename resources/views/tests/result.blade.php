@extends('layouts.app')

@section('title', 'Hasil '.$summary['title'])

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Tes potensi', 'url' => route('tests.index')],
            ['label' => 'Hasil', 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container detail-hero__main">
            <div>
                <p class="eyebrow eyebrow--light">{{ $summary['title'] }}</p>
                <h1 class="detail-hero__title">{{ $summary['headline'] }}</h1>
                <p class="detail-hero__where">{{ $summary['description'] }}</p>
                <div class="detail-hero__actions">
                    <a class="btn btn--light" href="{{ route('tests.index') }}">Tes lainnya</a>
                    <a class="btn btn--outline-light" href="{{ route('tests.history') }}">Riwayat tes</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Skor kamu</h2>
                    <ul class="score-list">
                        @foreach ($scores as $score)
                            <li class="score">
                                <span class="score__label">{{ $score['label'] }}</span>
                                <progress class="progress" max="{{ $score['max'] }}" value="{{ $score['value'] }}" aria-label="{{ $score['label'] }}"></progress>
                                <span class="score__value">{{ $score['value'] }} / {{ $score['max'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="content-panel">
                    <h2 class="content-panel__title">Jurusan yang cocok untukmu</h2>
                    @if ($recommendedMajors->isEmpty())
                        <p class="prose">Belum ada rekomendasi jurusan untuk hasil ini.</p>
                    @else
                        <div class="tag-grid tag-grid--cols">
                            @foreach ($recommendedMajors as $major)
                                <a href="{{ route('majors.show', $major) }}"><i class="ph ph-graduation-cap" aria-hidden="true"></i> {{ $major->name }}</a>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Langkah berikutnya</h2>
                    <p class="sidebar-card__text">Cari kampus yang menyediakan jurusan rekomendasimu, atau daftar langsung.</p>
                    <a class="btn btn--primary" href="{{ route('campuses.index') }}">Cari kampus</a>
                    <a class="btn btn--ghost" href="{{ route('applications.create') }}">Daftar kuliah</a>
                </div>
            </aside>
        </div>
    </div>
@endsection
