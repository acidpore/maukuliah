@extends('layouts.app')

@section('title', $major->name)
@section('description', Str::limit($major->description, 155))

@section('content')
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb__sep">/</span>
            <a href="{{ route('majors.index') }}">Jurusan</a>
            <span class="breadcrumb__sep">/</span>
            <span>{{ $major->name }}</span>
        </nav>
    </div>

    <div class="detail-hero">
        <div class="container">
            <h1 class="detail-hero__title">{{ $major->name }}</h1>
            <div class="detail-hero__badges">
                <span class="badge badge--cat">{{ $major->category }}</span>
                <span class="badge badge--outline">{{ $campuses->count() }} kampus menyediakan</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout detail-layout--flush">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Deskripsi</h2>
                    <div class="prose">
                        <p>{{ $major->description }}</p>
                    </div>
                </section>

                @if (!empty($major->courses))
                    <section class="content-panel">
                        <h2 class="content-panel__title">Mata Kuliah</h2>
                        <ul class="check-list">
                            @foreach ($major->courses as $course)
                                <li>{{ $course }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if (!empty($major->career_prospects))
                    <section class="content-panel">
                        <h2 class="content-panel__title">Prospek Karier</h2>
                        <ul class="check-list">
                            @foreach ($major->career_prospects as $career)
                                <li>{{ $career }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Belum yakin memilih kampus?</h2>
                    <p class="sidebar-card__text">Bandingkan kampus yang menyediakan jurusan ini dan temukan yang paling sesuai.</p>
                    <a class="btn btn--primary" href="{{ route('campuses.index') }}">Jelajahi Kampus</a>
                    <a class="btn btn--ghost" href="{{ route('soon') }}">Tes Potensi</a>
                </div>
            </aside>
        </div>
    </div>

    <section class="section section--alt">
        <div class="container">
            <div class="section__head">
                <h2 class="section__title">Kampus Penyedia</h2>
            </div>
            @if ($campuses->isEmpty())
                <div class="empty">
                    <h2 class="empty__title">Belum ada kampus penyedia</h2>
                    <p class="empty__text">Data kampus untuk jurusan ini sedang dilengkapi.</p>
                </div>
            @else
                <div class="grid grid--campuses">
                    @foreach ($campuses as $campus)
                        @include('partials.campus-card', ['campus' => $campus])
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
