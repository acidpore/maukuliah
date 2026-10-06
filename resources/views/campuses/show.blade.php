@extends('layouts.app')

@php
    $programs = $studyPrograms ?? $campus->studyPrograms->loadMissing('major');
    $periods = $campus->admissionPeriods;
    $files = $brochures ?? $campus->brochures;
    $faqItems = $faqs ?? collect();
    $hasScholarships = $campus->relationLoaded('scholarships') && $campus->scholarships->isNotEmpty();
    $applyUrl = route('applications.create', ['campus' => $campus->slug]);
    $brochureLabels = ['program' => 'Brosur program studi', 'tuition' => 'Biaya kuliah', 'general' => 'Brosur umum'];
    $downloadUrl = fn ($brochure) => Route::has('brochures.download') ? route('brochures.download', [$campus, $brochure]) : route('soon');
    $cheapest = (int) $programs->min('monthly_installment');
@endphp

@section('title', $campus->name)
@section('description', Str::limit($campus->description, 155))

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Kampus', 'url' => route('campuses.index')],
            ['label' => $campus->name, 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container detail-hero__main">
            <span class="monogram">{{ $campus->initials() }}</span>
            <div>
                <h1 class="detail-hero__title">{{ $campus->name }}</h1>
                <div class="detail-hero__badges">
                    @include('partials.badge', ['label' => $campus->typeLabel(), 'variant' => $campus->type])
                    @include('partials.badge', ['label' => $campus->formLabel(), 'variant' => 'outline'])
                    @include('partials.badge', ['label' => 'Akreditasi '.$campus->accreditation, 'variant' => 'accred'])
                </div>
                <p class="detail-hero__where"><i class="ph ph-map-pin" aria-hidden="true"></i> {{ $campus->city }}, {{ $campus->province }}</p>
                <div class="detail-hero__actions">
                    @if ($files->isNotEmpty())
                        <a class="btn btn--light" href="#brosur"><i class="ph ph-download-simple" aria-hidden="true"></i> Unduh brosur</a>
                    @endif
                    <a class="btn btn--outline-light" href="{{ $applyUrl }}"><i class="ph ph-paper-plane-tilt" aria-hidden="true"></i> Ajukan pendaftaran</a>
                </div>
            </div>
        </div>
    </div>

    <div class="tabs" data-tabs>
        <div class="container tabs__inner">
            <a class="tabs__link is-active" href="#tentang" data-tab-link>Tentang kampus</a>
            <a class="tabs__link" href="#program-studi" data-tab-link>Program studi</a>
            @if ($periods->isNotEmpty())
                <a class="tabs__link" href="#jalur-pendaftaran" data-tab-link>Jalur pendaftaran</a>
            @endif
            @if ($files->isNotEmpty())
                <a class="tabs__link" href="#brosur" data-tab-link>Brosur</a>
            @endif
            @if ($hasScholarships)
                <a class="tabs__link" href="#beasiswa" data-tab-link>Beasiswa</a>
            @endif
            @if ($faqItems->isNotEmpty())
                <a class="tabs__link" href="#faq" data-tab-link>FAQ</a>
            @endif
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel" id="tentang">
                    <h2 class="content-panel__title">Tentang kampus</h2>
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
                            <div class="info-item__label">Tahun berdiri</div>
                            <div class="info-item__value">{{ $campus->established_year ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Situs web</div>
                            <div class="info-item__value">
                                @if ($campus->website)
                                    <a href="{{ $campus->website }}" target="_blank" rel="noopener">{{ parse_url($campus->website, PHP_URL_HOST) }}</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="prose">
                        <p>{{ $campus->description }}</p>
                    </div>
                </section>

                <section class="content-panel" id="program-studi">
                    <h2 class="content-panel__title">Program studi ({{ $programs->isNotEmpty() ? $programs->count() : $campus->majors->count() }})</h2>
                    @if ($programs->isNotEmpty())
                        @include('partials.program-table', ['programs' => $programs])
                    @elseif ($campus->majors->isNotEmpty())
                        <div class="tag-grid tag-grid--cols">
                            @foreach ($campus->majors as $major)
                                <a href="{{ route('majors.show', $major) }}"><i class="ph ph-graduation-cap" aria-hidden="true"></i> {{ $major->name }}</a>
                            @endforeach
                        </div>
                    @else
                        <p class="prose">Belum ada data program studi.</p>
                    @endif
                </section>

                @if ($periods->isNotEmpty())
                    <section class="content-panel" id="jalur-pendaftaran">
                        <h2 class="content-panel__title">Jalur pendaftaran</h2>
                        <ul class="row-list">
                            @foreach ($periods as $period)
                                <li class="row-card">
                                    <div>
                                        <h3 class="row-card__title">{{ $period->name }}</h3>
                                        <p class="row-card__text">{{ $period->opens_at->translatedFormat('d M Y') }} - {{ $period->closes_at->translatedFormat('d M Y') }}</p>
                                    </div>
                                    @include('partials.badge', ['label' => $period->isOpen() ? 'Dibuka' : 'Ditutup', 'variant' => $period->isOpen() ? 'open' : 'closed'])
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($files->isNotEmpty())
                    <section class="content-panel" id="brosur">
                        <h2 class="content-panel__title">Brosur</h2>
                        <ul class="row-list">
                            @foreach ($files as $brochure)
                                <li class="row-card">
                                    <div>
                                        <h3 class="row-card__title">{{ $brochure->title }}</h3>
                                        <p class="row-card__text">{{ $brochureLabels[$brochure->type->value] ?? '' }}</p>
                                    </div>
                                    <a class="btn btn--soft btn--sm" href="{{ $downloadUrl($brochure) }}"><i class="ph ph-download-simple" aria-hidden="true"></i> Unduh</a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($hasScholarships)
                    <section class="content-panel" id="beasiswa">
                        <h2 class="content-panel__title">Beasiswa kampus</h2>
                        <div class="grid grid--scholarships-pair">
                            @foreach ($campus->scholarships as $scholarship)
                                @include('partials.scholarship-card', ['scholarship' => $scholarship])
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($faqItems->isNotEmpty())
                    <section class="content-panel" id="faq">
                        @include('partials.accordion', ['items' => $faqItems, 'title' => 'Pertanyaan umum'])
                    </section>
                @endif
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Tertarik dengan kampus ini?</h2>
                    @if ($cheapest > 0)
                        @include('partials.price', ['prefix' => 'Angsuran mulai dari', 'amount' => $cheapest, 'suffix' => '/ bulan'])
                    @endif
                    <p class="sidebar-card__text">Isi formulir singkat, tim kami akan menghubungimu lewat WhatsApp. Tanpa perlu masuk akun.</p>
                    <a class="btn btn--primary" href="{{ $applyUrl }}">Ajukan pendaftaran</a>
                    @if ($files->isNotEmpty())
                        <a class="btn btn--ghost" href="#brosur"><i class="ph ph-download-simple" aria-hidden="true"></i> Lihat brosur</a>
                    @endif
                    @include('partials.favorite-button', ['type' => 'campus', 'model' => $campus])
                </div>
            </aside>
        </div>
    </div>
@endsection
