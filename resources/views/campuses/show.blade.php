@extends('layouts.app')

@section('title', $campus->name)
@section('description', Str::limit($campus->description, 155))

@section('content')
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Beranda</a>
            <span class="breadcrumb__sep">/</span>
            <a href="{{ route('campuses.index') }}">Kampus</a>
            <span class="breadcrumb__sep">/</span>
            <span>{{ $campus->name }}</span>
        </nav>
    </div>

    <div class="detail-hero">
        <div class="container detail-hero__main">
            <span class="monogram">{{ $campus->initials() }}</span>
            <div>
                <h1 class="detail-hero__title">{{ $campus->name }}</h1>
                <div class="detail-hero__badges">
                    <span class="badge badge--{{ $campus->type }}">{{ $campus->typeLabel() }}</span>
                    <span class="badge badge--outline">{{ $campus->formLabel() }}</span>
                    <span class="badge badge--accred">Akreditasi {{ $campus->accreditation }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Informasi Kampus</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-item__label">Kota</div>
                            <div class="info-item__value">{{ $campus->city }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Provinsi</div>
                            <div class="info-item__value">{{ $campus->province }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Akreditasi</div>
                            <div class="info-item__value">{{ $campus->accreditation }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Bentuk</div>
                            <div class="info-item__value">{{ $campus->formLabel() }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Tahun Berdiri</div>
                            <div class="info-item__value">{{ $campus->established_year ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Website</div>
                            <div class="info-item__value">
                                @if ($campus->website)
                                    <a href="{{ $campus->website }}" target="_blank" rel="noopener">{{ parse_url($campus->website, PHP_URL_HOST) }}</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                <section class="content-panel">
                    <h2 class="content-panel__title">Tentang Kampus</h2>
                    <div class="prose">
                        <p>{{ $campus->description }}</p>
                    </div>
                </section>

                <section class="content-panel">
                    <h2 class="content-panel__title">Program Studi ({{ $campus->majors->count() }})</h2>
                    @if ($campus->majors->isEmpty())
                        <p class="prose">Belum ada data program studi.</p>
                    @else
                        <ul class="check-list">
                            @foreach ($campus->majors as $major)
                                <li><a href="{{ route('majors.show', $major) }}">{{ $major->name }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Tertarik dengan kampus ini?</h2>
                    <p class="sidebar-card__text">Unduh brosur resmi dan simpan kampus ini ke daftar favoritmu.</p>
                    <a class="btn btn--primary" href="{{ route('soon') }}">Unduh Brosur</a>
                    <a class="btn btn--ghost" href="{{ route('soon') }}">Simpan ke Favorit</a>
                </div>
            </aside>
        </div>
    </div>
@endsection
