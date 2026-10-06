@extends('layouts.app')

@section('title', $scholarship->name)
@section('description', Str::limit($scholarship->description, 155))

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Beasiswa', 'url' => route('scholarships.index')],
            ['label' => $scholarship->name, 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container">
            <h1 class="detail-hero__title">{{ $scholarship->name }}</h1>
            <div class="detail-hero__badges">
                @include('partials.badge', [
                    'label' => $scholarship->isOpen() ? 'Sedang dibuka' : 'Ditutup',
                    'variant' => $scholarship->isOpen() ? 'open' : 'closed',
                ])
            </div>
            <p class="detail-hero__where"><i class="ph ph-calendar-blank" aria-hidden="true"></i> {{ $scholarship->periodLabel() }}</p>
            @if ($scholarship->registration_url)
                <div class="detail-hero__actions">
                    <a class="btn btn--light" href="{{ $scholarship->registration_url }}" target="_blank" rel="noopener">Daftar beasiswa <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
                </div>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Informasi beasiswa</h2>
                    <div class="info-grid">
                        @if ($scholarship->provider)
                            <div class="info-item">
                                <div class="info-item__label">Penyelenggara</div>
                                <div class="info-item__value">{{ $scholarship->provider }}</div>
                            </div>
                        @endif
                        @if ($scholarship->campus)
                            <div class="info-item">
                                <div class="info-item__label">Kampus</div>
                                <div class="info-item__value"><a href="{{ route('campuses.show', $scholarship->campus) }}">{{ $scholarship->campus->name }}</a></div>
                            </div>
                        @endif
                        <div class="info-item">
                            <div class="info-item__label">Periode pendaftaran</div>
                            <div class="info-item__value">{{ $scholarship->periodLabel() }}</div>
                        </div>
                    </div>
                    <div class="prose">
                        <p>{{ $scholarship->description }}</p>
                    </div>
                </section>
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Siapkan dirimu</h2>
                    <p class="sidebar-card__text">Kenali minat dan bakatmu agar pilihan jurusan dan beasiswa lebih tepat.</p>
                    <a class="btn btn--primary" href="{{ route('tests.index') }}">Mulai tes potensi</a>
                    <a class="btn btn--ghost" href="{{ route('scholarships.index') }}">Beasiswa lainnya</a>
                    @include('partials.favorite-button', ['type' => 'scholarship', 'model' => $scholarship])
                </div>
            </aside>
        </div>
    </div>
@endsection
