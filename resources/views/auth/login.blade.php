@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
    <div class="container">
        <div class="auth-card">
            <div>
                <h1 class="auth-card__title">Masuk</h1>
                <p class="auth-card__text">Masuk untuk menyimpan favorit, melihat hasil tes, dan mengunduh brosur.</p>
            </div>

            @include('partials.flash')

            <form class="auth-card__form" method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                @include('partials.form-field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'])
                @include('partials.form-field', ['name' => 'password', 'label' => 'Kata sandi', 'type' => 'password', 'value' => '', 'autocomplete' => 'current-password'])
                <label class="check">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <span>Ingat saya</span>
                </label>
                <button class="btn btn--primary btn--lg btn--block" type="submit">Masuk</button>
            </form>

            <p class="auth-card__alt">
                <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
            </p>
            <p class="auth-card__alt">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
        </div>
    </div>
@endsection
