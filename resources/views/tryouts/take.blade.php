@extends('layouts.app')

@section('title', 'Kerjakan '.$tryout['title'])
@section('description', $tryout['description'])

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">{{ $tryout['title'] }}</h1>
            <p class="page-subtitle">{{ $tryout['question_count'] }} soal, {{ $tryout['duration_minutes'] }} menit.</p>
        </div>
    </div>

    <div class="container page-body">
        <form class="test-form" method="POST" action="{{ route('tryouts.submit', $tryout['slug']) }}" data-test-form data-countdown-form>
            @csrf

            <div class="test-form__progress test-form__progress--sticky" aria-live="polite">
                <progress class="progress" max="{{ $tryout['question_count'] }}" value="0" data-test-progress aria-label="Kemajuan pengerjaan"></progress>
                <span class="test-form__count"><span data-test-answered>0</span> dari {{ $tryout['question_count'] }} soal</span>
                <span class="timer" role="timer" aria-label="Sisa waktu">
                    <i class="ph ph-clock" aria-hidden="true"></i>
                    <span data-countdown="{{ $tryout['duration_minutes'] * 60 }}">{{ sprintf('%02d:00', $tryout['duration_minutes']) }}</span>
                </span>
            </div>

            <ol class="question-list">
                @foreach ($tryout['questions'] as $index => $question)
                    <li class="question">
                        <fieldset class="question__fieldset">
                            <legend class="question__text">{{ $question['text'] }}</legend>
                            <div class="question__options question__options--stack">
                                @foreach ($question['options'] as $optionIndex => $option)
                                    <label class="option">
                                        <input class="option__input" type="radio" name="answers[{{ $index }}]" value="{{ $optionIndex }}">
                                        <span class="option__label">{{ chr(65 + $optionIndex) }}. {{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    </li>
                @endforeach
            </ol>

            <button class="btn btn--primary btn--lg" type="submit">Kirim jawaban</button>
        </form>
    </div>
@endsection
