@extends('layouts.app')

@section('title', 'Daftar Karier')

@section('content')
    <div class="page-head page-head--careers">
        <div class="container page-head__visual" aria-hidden="true"><img src="{{ asset('images/landing/career.webp') }}" alt="" width="220" height="150"></div>
        <div class="page-head__inner">
            <span class="directory-label"><i class="ph ph-briefcase" aria-hidden="true"></i> Jelajahi beragam pilihan profesi.</span>
            <h1 class="page-title">Daftar karier</h1>
            <p class="page-subtitle">Pelajari profesi, kisaran gaji, dan jurusan yang mengarah ke sana.</p>
            <p class="page-count">{{ number_format($careers->total()) }} profesi ditemukan</p>
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
