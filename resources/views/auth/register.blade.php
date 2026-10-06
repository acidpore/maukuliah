@extends('layouts.app')

@section('title', 'Daftar akun')

@section('content')
    <div class="container">
        <div class="auth-card">
            <div>
                <h1 class="auth-card__title">Buat akun</h1>
                <p class="auth-card__text">Gratis. Simpan favorit, kerjakan tes potensi, dan pantau pendaftaranmu.</p>
            </div>

            <form class="auth-card__form" method="POST" action="{{ route('register') }}" novalidate>
                @csrf
                @include('partials.form-field', ['name' => 'name', 'label' => 'Nama lengkap', 'autocomplete' => 'name'])
                @include('partials.form-field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email'])
                @include('partials.form-field', ['name' => 'phone', 'label' => 'Nomor telepon', 'type' => 'tel', 'required' => false, 'autocomplete' => 'tel'])
                @include('partials.form-field', ['name' => 'password', 'label' => 'Kata sandi', 'type' => 'password', 'value' => '', 'hint' => 'Gunakan kombinasi yang sulit ditebak.', 'autocomplete' => 'new-password'])
                @include('partials.form-field', ['name' => 'password_confirmation', 'label' => 'Ulangi kata sandi', 'type' => 'password', 'value' => '', 'autocomplete' => 'new-password'])
                <button class="btn btn--primary btn--lg btn--block" type="submit">Daftar</button>
            </form>

            <p class="auth-card__alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
        </div>
    </div>
@endsection
