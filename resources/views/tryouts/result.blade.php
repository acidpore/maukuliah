@extends('layouts.app')

@section('title', 'Hasil '.$tryout['title'])

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Tryout', 'url' => route('tryouts.index')],
            ['label' => 'Hasil', 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container detail-hero__main">
            <div>
                <p class="eyebrow eyebrow--light">{{ $tryout['title'] }}</p>
                <h1 class="detail-hero__title">Skor {{ $result['percent'] }}</h1>
                <p class="detail-hero__where">{{ $result['correct'] }} benar dari {{ $result['total'] }} soal.</p>
                <div class="detail-hero__actions">
                    <a class="btn btn--light" href="{{ route('tryouts.take', $tryout['slug']) }}">Coba lagi</a>
                    <a class="btn btn--outline-light" href="{{ route('tryouts.index') }}">Tryout lain</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Pembahasan</h2>
                    <ol class="question-list">
                        @foreach ($result['review'] as $item)
                            <li class="question {{ $item['is_correct'] ? '' : 'question--error' }}">
                                <p class="question__text">{{ $item['text'] }}</p>
                                <p class="review__line">
                                    @include('partials.badge', [
                                        'label' => $item['is_correct'] ? 'Benar' : 'Salah',
                                        'variant' => $item['is_correct'] ? 'open' : 'danger',
                                    ])
                                    Jawabanmu: {{ $item['chosen'] === null ? 'tidak dijawab' : $item['options'][$item['chosen']] }}
                                </p>
                                <p class="review__line">Jawaban benar: {{ $item['options'][$item['answer']] }}</p>
                                <p class="prose">{{ $item['explanation'] }}</p>
                            </li>
                        @endforeach
                    </ol>
                </section>
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Langkah berikutnya</h2>
                    <p class="sidebar-card__text">Kenali minatmu lewat tes potensi, lalu cari kampus yang cocok.</p>
                    <a class="btn btn--primary" href="{{ route('tests.index') }}">Tes potensi</a>
                    <a class="btn btn--ghost" href="{{ route('campuses.index') }}">Cari kampus</a>
                </div>
            </aside>
        </div>
    </div>
@endsection
