@extends('layouts.app')

@section('title', 'Segera Hadir')

@section('content')
    <div class="container page-body">
        <div class="empty">
            <h1 class="empty__title">Segera Hadir</h1>
            <p class="empty__text">Fitur ini sedang dalam pengembangan. Kunjungi kembali segera.</p>
            <a class="btn btn--primary" href="{{ route('home') }}">Kembali ke Beranda</a>
        </div>
    </div>
@endsection
