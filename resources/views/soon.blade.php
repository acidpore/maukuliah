@extends('layouts.app')

@section('title', 'Segera Hadir')

@section('content')
    <div class="container page-body">
        @include('partials.empty-state', [
            'icon' => 'hourglass-medium',
            'title' => 'Segera hadir',
            'text' => 'Fitur ini sedang dalam pengembangan. Kunjungi kembali dalam waktu dekat.',
            'actionLabel' => 'Kembali ke beranda',
            'actionUrl' => route('home'),
        ])
    </div>
@endsection
