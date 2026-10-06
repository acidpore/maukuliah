@extends('layouts.app')

@section('title', $career->name)
@section('description', Str::limit($career->description, 155))

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Karier', 'url' => route('careers.index')],
            ['label' => $career->name, 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container">
            <h1 class="detail-hero__title">{{ $career->name }}</h1>
            @if ($career->salaryRange())
                <p class="detail-hero__where"><i class="ph ph-wallet" aria-hidden="true"></i> Kisaran gaji {{ $career->salaryRange() }} per bulan</p>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Tentang karier</h2>
                    <div class="prose">
                        <p>{{ $career->description }}</p>
                    </div>
                </section>

                @if (! empty($career->positions))
                    <section class="content-panel">
                        <h2 class="content-panel__title">Jenjang posisi</h2>
                        <ol class="steps">
                            @foreach ($career->positions as $position)
                                <li>{{ $position }}</li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                @if ($career->majors->isNotEmpty())
                    <section class="content-panel">
                        <h2 class="content-panel__title">Jurusan yang direkomendasikan</h2>
                        <div class="tag-grid tag-grid--cols">
                            @foreach ($career->majors as $major)
                                <a href="{{ route('majors.show', $major) }}"><i class="ph ph-graduation-cap" aria-hidden="true"></i> {{ $major->name }}</a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Cocokkah untukmu?</h2>
                    <p class="sidebar-card__text">Kerjakan tes potensi untuk mengetahui profesi yang sesuai minatmu.</p>
                    <a class="btn btn--primary" href="{{ route('tests.index') }}">Mulai tes potensi</a>
                    @include('partials.favorite-button', ['type' => 'career', 'model' => $career])
                </div>
            </aside>
        </div>
    </div>

    @if ($relatedCampuses->isNotEmpty())
        <section class="section section--tint">
            <div class="container">
                @include('partials.section-head', [
                    'title' => 'Kampus yang bisa kamu tuju',
                    'linkLabel' => 'Lihat semua kampus',
                    'linkUrl' => route('campuses.index'),
                ])
                <div class="grid grid--campuses">
                    @foreach ($relatedCampuses as $campus)
                        @include('partials.campus-card', ['campus' => $campus])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
