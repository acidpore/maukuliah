@extends('layouts.app')

@section('title', 'Tryout')
@section('description', 'Latihan soal seleksi masuk perguruan tinggi dengan timer dan pembahasan.')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Tryout</h1>
            <p class="page-subtitle">Uji kesiapanmu dengan soal berwaktu, lengkap dengan skor dan pembahasan.</p>
        </div>
    </div>

    <div class="container page-body">
        @if ($tryouts->isEmpty())
            @include('partials.empty-state', [
                'title' => 'Belum ada tryout yang dibuka',
                'text' => 'Tryout baru akan muncul di sini. Sementara itu, coba tes potensi untuk mengenali minatmu.',
                'icon' => 'notepad',
                'actionLabel' => 'Ke tes potensi',
                'actionUrl' => route('tests.index'),
            ])
        @else
            <div class="grid grid--tests">
                @foreach ($tryouts as $tryout)
                    <a class="test-card" href="{{ route('tryouts.show', $tryout['slug']) }}">
                        <span class="test-card__icon"><i class="ph ph-notepad" aria-hidden="true"></i></span>
                        <span class="test-card__head">
                            <h2 class="test-card__title">{{ $tryout['title'] }}</h2>
                            @include('partials.badge', [
                                'label' => $tryout['is_open'] ? 'Dibuka' : 'Segera',
                                'variant' => $tryout['is_open'] ? 'open' : 'warning',
                            ])
                        </span>
                        <p class="test-card__text">{{ $tryout['description'] }}</p>
                        <span class="test-card__meta">
                            <i class="ph ph-clock" aria-hidden="true"></i> {{ $tryout['duration_minutes'] }} menit
                            <span aria-hidden="true">/</span>
                            {{ $tryout['question_count'] > 0 ? $tryout['question_count'].' soal' : 'Soal menyusul' }}
                            <span aria-hidden="true">/</span>
                            {{ $tryout['price'] === 0 ? 'Gratis' : 'Rp'.number_format($tryout['price'], 0, ',', '.') }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
