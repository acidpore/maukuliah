@extends('layouts.app')

@section('title', 'Daftar Karier')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Daftar karier</h1>
            <p class="page-subtitle">Pelajari profesi, kisaran gaji, dan jurusan yang mengarah ke sana.</p>
            <p class="page-count">{{ $careers->total() }} karier ditemukan</p>
        </div>
    </div>

    <div class="container page-body">
        <div class="toolbar">
            @include('partials.filter-bar', [
                'action' => route('careers.index'),
                'query' => $query,
                'placeholder' => 'Cari nama karier',
            ])
        </div>

        @if ($careers->isEmpty())
            @include('partials.empty-state', [
                'icon' => 'briefcase',
                'title' => 'Tidak ada karier yang cocok',
                'text' => 'Coba kata kunci yang lebih umum.',
                'actionLabel' => 'Reset pencarian',
                'actionUrl' => route('careers.index'),
            ])
        @else
            <div class="row-list row-list--cols">
                @foreach ($careers as $career)
                    @include('partials.career-row', ['career' => $career])
                @endforeach
            </div>
            {{ $careers->links('pagination.custom') }}
        @endif
    </div>
@endsection
