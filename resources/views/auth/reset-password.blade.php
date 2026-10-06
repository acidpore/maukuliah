@extends('layouts.app')

@section('title', 'Atur ulang kata sandi')

@section('content')
    <div class="container">
        <div class="auth-card">
            <div>
                <h1 class="auth-card__title">Atur ulang kata sandi</h1>
                <p class="auth-card__text">Buat kata sandi baru untuk akunmu.</p>
            </div>

            <form class="auth-card__form" method="POST" action="{{ route('password.update') }}" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                @include('partials.form-field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => old('email', $email), 'autocomplete' => 'email'])
                @include('partials.form-field', ['name' => 'password', 'label' => 'Kata sandi baru', 'type' => 'password', 'value' => '', 'autocomplete' => 'new-password'])
                @include('partials.form-field', ['name' => 'password_confirmation', 'label' => 'Ulangi kata sandi baru', 'type' => 'password', 'value' => '', 'autocomplete' => 'new-password'])
                <button class="btn btn--primary btn--lg btn--block" type="submit">Simpan kata sandi</button>
            </form>
        </div>
    </div>
@endsection
