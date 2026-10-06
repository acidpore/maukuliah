@extends('layouts.app')

@section('title', 'Daftar Beasiswa')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Daftar beasiswa</h1>
            <p class="page-subtitle">Cek periode pendaftaran beasiswa dari pemerintah dan kampus.</p>
            <p class="page-count">{{ $scholarships->total() }} beasiswa ditemukan</p>
        </div>
    </div>

    <div class="container page-body">
        <div class="toolbar">
            @include('partials.filter-bar', [
                'action' => route('scholarships.index'),
                'query' => $query,
                'placeholder' => 'Cari nama beasiswa',
            ])
        </div>

        @if ($scholarships->isEmpty())
            @include('partials.empty-state', [
                'icon' => 'medal',
                'title' => 'Belum ada beasiswa',
                'text' => 'Tidak ada beasiswa yang cocok dengan pencarianmu saat ini.',
                'actionLabel' => 'Reset pencarian',
                'actionUrl' => route('scholarships.index'),
            ])
        @else
            <div class="grid grid--scholarships">
                @foreach ($scholarships as $scholarship)
                    @include('partials.scholarship-card', ['scholarship' => $scholarship])
                @endforeach
            </div>
            {{ $scholarships->links('pagination.custom') }}
        @endif
    </div>
@endsection
