@extends('layouts.app')

@section('title', 'Halaman tidak ditemukan')

@section('content')
    <div class="container page-body">
        @include('partials.empty-state', [
            'icon' => 'compass',
            'title' => 'Halaman tidak ditemukan',
            'text' => 'Alamat yang kamu buka tidak ada atau sudah dipindahkan. Coba cari kampus atau jurusan dari beranda.',
            'actionLabel' => 'Kembali ke beranda',
            'actionUrl' => route('home'),
        ])
    </div>
@endsection
