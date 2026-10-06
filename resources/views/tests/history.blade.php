@extends('layouts.app')

@section('title', 'Riwayat tes')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Riwayat tes</h1>
            <p class="page-subtitle">Semua tes potensi yang pernah kamu kerjakan.</p>
        </div>
    </div>

    <div class="container page-body">
        @if ($results->isEmpty())
            @include('partials.empty-state', [
                'icon' => 'clipboard-text',
                'title' => 'Belum ada tes yang dikerjakan',
                'text' => 'Kerjakan tes pertamamu untuk mendapatkan rekomendasi jurusan.',
                'actionLabel' => 'Mulai tes',
                'actionUrl' => route('tests.index'),
            ])
        @else
            <ul class="row-list">
                @foreach ($results as $result)
                    <li>
                        <a class="row-card" href="{{ route('tests.result', $result) }}">
                            <div>
                                <h2 class="row-card__title">{{ $testTitles[$result->test_type] ?? $result->test_type }}</h2>
                                <p class="row-card__text">{{ $result->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                            <span class="row-card__side"><i class="ph ph-arrow-right" aria-hidden="true"></i></span>
                        </a>
                    </li>
                @endforeach
            </ul>
            {{ $results->links('pagination.custom') }}
        @endif
    </div>
@endsection
