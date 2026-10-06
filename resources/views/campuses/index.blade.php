@extends('layouts.app')

@php
    $pageTitle = $heading ?? 'Daftar kampus';
    $pageIntro = $intro ?? 'Bandingkan kampus berdasarkan program, jadwal, metode belajar, biaya, lokasi, dan akreditasi.';
    $seoRoute = Route::currentRouteName();
    $isSeoPage = in_array($seoRoute, ['campuses.by-schedule', 'campuses.by-method'], true);
    $regionParams = match ($seoRoute) {
        'campuses.by-schedule' => ['schedule' => $filters['schedule'] ?? null],
        'campuses.by-method' => ['method' => $filters['method'] ?? null],
        default => [],
    };
    $formAction = $isSeoPage ? url()->current() : route('campuses.index');
@endphp

@section('title', $pageTitle)
@section('description', $pageIntro)

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">{{ $pageTitle }}</h1>
            <p class="page-subtitle">{{ $pageIntro }}</p>
            <p class="page-count">{{ $campuses->total() }} kampus ditemukan</p>
        </div>
    </div>

    <div class="container">
        <form action="{{ $formAction }}" method="GET">
            <div class="list-layout">
                <aside class="sidebar">
                    @include('partials.campus-filters', ['resetUrl' => $formAction])
                </aside>

                <div>
                    <div class="toolbar">
                        <div class="toolbar__search">
                            <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                            <input class="toolbar__search-input" type="search" name="q" value="{{ $query }}" placeholder="Cari nama kampus, kota, atau jenis" aria-label="Cari kampus">
                            <button class="btn btn--primary btn--sm" type="submit">Cari</button>
                        </div>
                        <select class="toolbar__sort" name="sort" data-auto-submit aria-label="Urutkan kampus">
                            <option value="">Urutkan: nama (A-Z)</option>
                            <option value="newest" @selected($sort === 'newest')>Urutkan: terbaru</option>
                            <option value="cheapest" @selected($sort === 'cheapest')>Urutkan: termurah</option>
                        </select>
                    </div>

                    @if ($campuses->isEmpty())
                        @include('partials.empty-state', [
                            'icon' => 'buildings',
                            'title' => 'Tidak ada kampus yang cocok',
                            'text' => 'Coba ubah kata kunci atau filter pencarianmu.',
                            'actionLabel' => 'Reset pencarian',
                            'actionUrl' => $formAction,
                        ])
                    @else
                        <div class="grid grid--campuses grid--campuses-list">
                            @foreach ($campuses as $campus)
                                @include('partials.campus-card', ['campus' => $campus])
                            @endforeach
                        </div>
                        {{ $campuses->links('pagination.custom') }}
                    @endif

                    @include('partials.region-accordion', [
                        'regions' => $provinces,
                        'regionRoute' => $isSeoPage ? $seoRoute : null,
                        'regionParams' => $regionParams,
                    ])
                </div>
            </div>
        </form>
    </div>
@endsection
