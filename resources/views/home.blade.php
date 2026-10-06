@extends('layouts.app')

@section('title', 'Temukan Kampus dan Jurusan Impianmu')

@section('content')
    <section class="hero">
        <div class="container hero__inner">
            <span class="hero__eyebrow">Platform pencarian kampus terlengkap</span>
            <h1 class="hero__title">Persiapkan kuliah dengan mudah, <span>raih masa depan cerah</span></h1>
            <p class="hero__subtitle">Temukan kampus, jurusan, dan informasi perkuliahan yang tepat sesuai minat dan bakatmu.</p>

            <form class="hero__search" action="{{ route('search') }}" method="GET" role="search">
                <input class="hero__search-input" type="search" name="q" placeholder="Cari kampus, jurusan, atau kota..." aria-label="Cari kampus atau jurusan">
                <button class="btn btn--primary" type="submit">Cari</button>
            </form>

            <div class="hero__cta">
                <a class="btn btn--light" href="{{ route('campuses.index') }}">Jelajahi Kampus</a>
                <a class="btn btn--ghost" href="{{ route('majors.index') }}">Lihat Jurusan</a>
                <a class="btn btn--ghost" href="{{ route('soon') }}">Tes Potensi</a>
            </div>

            <div class="hero__stats">
                <div class="hero__stat">
                    <div class="hero__stat-num">{{ $stats['campuses'] }}</div>
                    <div class="hero__stat-label">Kampus</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-num">{{ $stats['majors'] }}</div>
                    <div class="hero__stat-label">Jurusan</div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-num">{{ $stats['cities'] }}</div>
                    <div class="hero__stat-label">Kota</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section__head">
                <div>
                    <h2 class="section__title">Kenapa {{ config('app.name') }}?</h2>
                    <p class="section__subtitle">Semua yang kamu butuhkan untuk menentukan pilihan kuliah dalam satu platform.</p>
                </div>
            </div>
            <div class="features">
                <div class="feature">
                    <span class="feature__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                    </span>
                    <h3 class="feature__title">Pencarian Lengkap</h3>
                    <p class="feature__text">Cari kampus dan jurusan berdasarkan lokasi, jenis, bentuk, dan akreditasi.</p>
                </div>
                <div class="feature">
                    <span class="feature__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                    </span>
                    <h3 class="feature__title">Filter Cerdas</h3>
                    <p class="feature__text">Saring hasil dengan cepat agar menemukan pilihan yang paling relevan.</p>
                </div>
                <div class="feature">
                    <span class="feature__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>
                    </span>
                    <h3 class="feature__title">Informasi Ringkas</h3>
                    <p class="feature__text">Data kampus dan jurusan disajikan ringkas dan mudah dipahami.</p>
                </div>
                <div class="feature">
                    <span class="feature__icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    </span>
                    <h3 class="feature__title">Gratis dan Mudah</h3>
                    <p class="feature__text">Akses semua informasi tanpa biaya, kapan saja dan di mana saja.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--alt">
        <div class="container">
            <div class="section__head">
                <div>
                    <h2 class="section__title">Rekomendasi Kampus</h2>
                    <p class="section__subtitle">Kampus-kampus dengan reputasi baik dan prospek lulusan yang cerah.</p>
                </div>
                <a class="section__link" href="{{ route('campuses.index') }}">Lihat Lainnya</a>
            </div>
            <div class="grid grid--campuses">
                @foreach ($featuredCampuses as $campus)
                    @include('partials.campus-card', ['campus' => $campus])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section__head">
                <div>
                    <h2 class="section__title">Jelajahi Jurusan</h2>
                    <p class="section__subtitle">Temukan jurusan yang paling banyak diminati dan sesuai minatmu.</p>
                </div>
                <a class="section__link" href="{{ route('majors.index') }}">Lihat Semua</a>
            </div>
            <div class="grid grid--majors">
                @foreach ($featuredMajors as $major)
                    @include('partials.major-card', ['major' => $major])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section--alt">
        <div class="container">
            <div class="section__head">
                <div>
                    <h2 class="section__title">Kota Pelajar Populer</h2>
                    <p class="section__subtitle">Jelajahi kampus berdasarkan kota tujuanmu.</p>
                </div>
            </div>
            <div class="chips">
                @foreach (['Yogyakarta', 'Bandung', 'Surabaya', 'Malang', 'Semarang', 'Depok', 'Bogor', 'Medan'] as $city)
                    <a class="chip" href="{{ route('campuses.index', ['city' => $city]) }}">{{ $city }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cta-banner">
                <div>
                    <h2 class="cta-banner__title">Masih bingung memilih jurusan?</h2>
                    <p class="cta-banner__text">Kenali minat dan bakatmu lewat tes potensi untuk mendapatkan rekomendasi jurusan yang paling cocok.</p>
                </div>
                <a class="btn btn--light btn--lg" href="{{ route('soon') }}">Ikuti Tes Potensi</a>
            </div>
        </div>
    </section>
@endsection
