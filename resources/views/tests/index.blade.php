@extends('layouts.app')

@section('title', 'Tes Potensi')
@section('description', 'Kerjakan tes minat dan bakat gratis untuk menemukan jurusan yang cocok denganmu.')

@php
    $icons = ['riasec' => 'target', 'learning-style' => 'lightbulb', 'mbti' => 'identification-card'];
@endphp

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Tes potensi</h1>
            <p class="page-subtitle">Kenali minat, gaya belajar, dan kepribadianmu untuk mendapatkan rekomendasi jurusan yang paling cocok. Gratis.</p>
            @auth
                <p class="page-count"><a href="{{ route('tests.history') }}">Lihat riwayat tesmu</a></p>
            @endauth
        </div>
    </div>

    <div class="container page-body">
        <div class="grid grid--tests">
            @foreach ($tests as $test)
                <a class="test-card" href="{{ route('tests.start', $test['key']) }}">
                    <span class="test-card__icon"><i class="ph ph-{{ $icons[$test['key']] ?? 'clipboard-text' }}" aria-hidden="true"></i></span>
                    <span class="test-card__head">
                        <h2 class="test-card__title">{{ $test['title'] }}</h2>
                        @include('partials.badge', ['label' => $test['badge'], 'variant' => 'cat'])
                    </span>
                    <p class="test-card__text">{{ $test['description'] }}</p>
                    <span class="test-card__meta"><i class="ph ph-clock" aria-hidden="true"></i> {{ $test['duration_label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
