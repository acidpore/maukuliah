@extends('layouts.app')

@section('title', 'Favorit')

@php
    $total = collect($favorites)->sum(fn ($group) => $group->count());
@endphp

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Favorit</h1>
            <p class="page-subtitle">Kampus, jurusan, karier, dan beasiswa yang kamu simpan.</p>
            <p class="page-count">{{ $total }} item tersimpan</p>
        </div>
    </div>

    <div class="container page-body">
        @if ($total === 0)
            @include('partials.empty-state', [
                'icon' => 'heart',
                'title' => 'Belum ada favorit',
                'text' => 'Tekan tombol simpan pada kampus, jurusan, karier, atau beasiswa untuk menyimpannya di sini.',
                'actionLabel' => 'Cari kampus',
                'actionUrl' => route('campuses.index'),
            ])
        @endif

        @if ($favorites['campuses']->isNotEmpty())
            <section class="favorite-group">
                <h2 class="content-panel__title">Kampus</h2>
                <div class="grid grid--campuses grid--campuses-list">
                    @foreach ($favorites['campuses'] as $campus)
                        <div class="favorite-item">
                            @include('partials.campus-card', ['campus' => $campus->loadCount('majors')])
                            @include('partials.favorite-button', ['type' => 'campus', 'model' => $campus])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($favorites['majors']->isNotEmpty())
            <section class="favorite-group">
                <h2 class="content-panel__title">Jurusan</h2>
                <div class="grid grid--majors">
                    @foreach ($favorites['majors'] as $major)
                        <div class="favorite-item">
                            @include('partials.major-card', ['major' => $major->loadCount('campuses')])
                            @include('partials.favorite-button', ['type' => 'major', 'model' => $major])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($favorites['careers']->isNotEmpty())
            <section class="favorite-group">
                <h2 class="content-panel__title">Karier</h2>
                <ul class="row-list">
                    @foreach ($favorites['careers'] as $career)
                        <li class="favorite-item">
                            @include('partials.career-row', ['career' => $career])
                            @include('partials.favorite-button', ['type' => 'career', 'model' => $career])
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($favorites['scholarships']->isNotEmpty())
            <section class="favorite-group">
                <h2 class="content-panel__title">Beasiswa</h2>
                <div class="grid grid--scholarships-pair">
                    @foreach ($favorites['scholarships'] as $scholarship)
                        <div class="favorite-item">
                            @include('partials.scholarship-card', ['scholarship' => $scholarship->loadMissing('campus')])
                            @include('partials.favorite-button', ['type' => 'scholarship', 'model' => $scholarship])
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
