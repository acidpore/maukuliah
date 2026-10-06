@extends('layouts.app')

@section('title', $title)
@section('description', $description)

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">{{ $title }}</h1>
            <p class="page-subtitle">{{ $description }}</p>
            @guest
                <p class="page-count">Kamu bisa mengerjakan tes tanpa akun. Masuk atau daftar saat ingin melihat hasil.</p>
            @endguest
        </div>
    </div>

    <div class="container page-body">
        <form class="test-form" method="POST" action="{{ route('tests.submit', $type) }}" data-test-form novalidate>
            @csrf

            <div class="test-form__progress" aria-live="polite">
                <progress class="progress" max="{{ count($questions) }}" value="0" data-test-progress aria-label="Kemajuan pengerjaan"></progress>
                <span class="test-form__count"><span data-test-answered>0</span> dari {{ count($questions) }} soal terjawab</span>
            </div>

            @if ($errors->any())
                <p class="notice notice--danger" role="alert">Masih ada soal yang belum dijawab. Soal yang kosong ditandai.</p>
            @endif

            <ol class="question-list">
                @foreach ($questions as $question)
                    @php
                        $answered = old('answers.'.$question['index']);
                        $isLikert = ! empty($scaleLabels);
                    @endphp
                    <li class="question {{ $errors->has('answers.'.$question['index']) ? 'question--error' : '' }}">
                        <fieldset class="question__fieldset">
                            <legend class="question__text">{{ $question['text'] }}</legend>
                            <div class="question__options {{ $isLikert ? 'question__options--scale' : 'question__options--choice' }}">
                                @foreach ($question['options'] as $option)
                                    <label class="option">
                                        <input class="option__input" type="radio" name="answers[{{ $question['index'] }}]" value="{{ $option['value'] }}" @checked((string) $answered === (string) $option['value'])>
                                        <span class="option__label">{{ $option['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    </li>
                @endforeach
            </ol>

            <button class="btn btn--primary btn--lg" type="submit">Lihat hasil</button>
        </form>
    </div>
@endsection
