@extends('layouts.app')

@section('title', 'Lupa kata sandi')

@section('content')
    <div class="container">
        <div class="auth-card">
            <div>
                <h1 class="auth-card__title">Lupa kata sandi</h1>
                <p class="auth-card__text">Masukkan email akunmu. Kami akan mengirim tautan untuk mengatur ulang kata sandi.</p>
            </div>

            @include('partials.flash')

            <form class="auth-card__form" method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf
                @include('partials.form-field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'])
                <button class="btn btn--primary btn--lg btn--block" type="submit">Kirim tautan</button>
            </form>

            <p class="auth-card__alt"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></p>
        </div>
    </div>
@endsection
