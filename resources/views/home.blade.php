@extends('layouts.app')

@section('title', 'Temukan Kampus dan Jurusan Impianmu')

@section('content')
    <section class="hero">
        <div class="container hero__inner">
            <div>
                <h1 class="hero__title">
                    Persiapkan kuliah dengan mudah, temukan
                    <span class="hero__typed" data-typed="kampus impianmu|jurusan yang pas|beasiswa terbaik|masa depan cerahmu"><span data-typed-label>kampus impianmu</span><span class="hero__caret" aria-hidden="true"></span></span>
                </h1>
                <p class="hero__subtitle">Bandingkan kampus dan jurusan di seluruh Indonesia, kenali minat bakatmu, lalu daftar langsung dari satu tempat.</p>

                <form class="search-bar" action="{{ route('search') }}" method="GET" role="search">
                    <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                    <input class="search-bar__input" type="search" name="q" placeholder="Cari kampus, jurusan, atau kota" aria-label="Cari kampus, jurusan, atau kota">
                    <button class="btn btn--primary" type="submit">Cari</button>
                </form>

                <div class="hero__links">
                    <a href="{{ route('campuses.index') }}">Jelajahi kampus <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                    <a href="{{ route('majors.index') }}">Lihat jurusan <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>

            @php($spotlight = $featuredCampuses->first())
            @if ($spotlight)
                <div class="hero-panel">
                    <div class="hero-panel__card">
                        @include('partials.campus-card', ['campus' => $spotlight])
                    </div>
                    <div class="hero-panel__row">
                        @include('partials.badge', ['label' => $spotlight->typeLabel(), 'variant' => 'negeri'])
                        @include('partials.badge', ['label' => $spotlight->formLabel(), 'variant' => 'swasta'])
                        @include('partials.badge', ['label' => 'Akreditasi '.$spotlight->accreditation, 'variant' => 'accred'])
                    </div>
                    <div class="hero-panel__stats">
                        <div class="hero-panel__stat">
                            <i class="ph ph-buildings" aria-hidden="true"></i>
                            <span><strong>{{ $stats['campuses'] }}</strong> kampus</span>
                        </div>
                        <div class="hero-panel__stat">
                            <i class="ph ph-graduation-cap" aria-hidden="true"></i>
                            <span><strong>{{ $stats['majors'] }}</strong> jurusan</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <div class="container">
        <div class="stat-strip">
            <div class="stat">
                <div class="stat__num">{{ $stats['campuses'] }}</div>
                <div class="stat__label">Kampus</div>
            </div>
            <div class="stat">
                <div class="stat__num">{{ $stats['majors'] }}</div>
                <div class="stat__label">Jurusan</div>
            </div>
            <div class="stat">
                <div class="stat__num">{{ $stats['cities'] }}</div>
                <div class="stat__label">Kota</div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            @include('partials.section-head', [
                'title' => 'Semua keputusan kuliah di satu tempat',
                'subtitle' => 'Dari mencari kampus sampai mengenali jurusan yang cocok, tanpa biaya.',
            ])
            <div class="bento">
                <article class="bento__cell bento__cell--blue" data-reveal>
                    <span class="bento__icon"><i class="ph ph-buildings" aria-hidden="true"></i></span>
                    <h3 class="bento__title">Cari kampus dengan filter lengkap</h3>
                    <p class="bento__text">Saring berdasarkan lokasi, jenis, bentuk, dan akreditasi. Hasilnya bisa dibagikan lewat tautan.</p>
                </article>
                <article class="bento__cell bento__cell--tint" data-reveal>
                    <span class="bento__icon"><i class="ph ph-books" aria-hidden="true"></i></span>
                    <h3 class="bento__title">Kenali jurusan</h3>
                    <p class="bento__text">Mata kuliah dan prospek kariernya.</p>
                </article>
                <article class="bento__cell" data-reveal>
                    <span class="bento__icon"><i class="ph ph-medal" aria-hidden="true"></i></span>
                    <h3 class="bento__title">Temukan beasiswa</h3>
                    <p class="bento__text">Lengkap dengan periode pendaftaran.</p>
                </article>
                <article class="bento__cell bento__cell--pattern" data-reveal>
                    <span class="bento__icon"><i class="ph ph-compass" aria-hidden="true"></i></span>
                    <h3 class="bento__title">Ukur minat dan bakat</h3>
                    <p class="bento__text">Tes potensi membantu memilih jurusan yang sesuai dirimu.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--tint">
        <div class="container">
            @include('partials.section-head', [
                'title' => 'Rekomendasi kampus',
                'subtitle' => 'Geser untuk melihat kampus lainnya.',
                'linkLabel' => 'Lihat semua kampus',
                'linkUrl' => route('campuses.index'),
            ])
            <div class="rail">
                @foreach ($featuredCampuses as $campus)
                    @include('partials.campus-card', ['campus' => $campus])
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @include('partials.section-head', [
                'title' => 'Jurusan yang banyak dicari',
                'linkLabel' => 'Lihat semua jurusan',
                'linkUrl' => route('majors.index'),
            ])
            <div class="grid grid--majors">
                @foreach ($featuredMajors as $major)
                    @include('partials.major-card', ['major' => $major])
                @endforeach
            </div>
        </div>
    </section>

    @if (! empty($featuredCareers) && count($featuredCareers))
        <section class="section section--tint">
            <div class="container">
                @include('partials.section-head', [
                    'title' => 'Karier yang bisa kamu tuju',
                    'subtitle' => 'Lihat kisaran gaji dan jurusan yang mengarah ke profesi ini.',
                    'linkLabel' => 'Lihat semua karier',
                    'linkUrl' => route('careers.index'),
                ])
                <div class="row-list row-list--cols">
                    @foreach ($featuredCareers as $career)
                        @include('partials.career-row', ['career' => $career])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (! empty($featuredScholarships) && count($featuredScholarships))
        <section class="section">
            <div class="container">
                @include('partials.section-head', [
                    'title' => 'Beasiswa untuk kuliahmu',
                    'linkLabel' => 'Lihat semua beasiswa',
                    'linkUrl' => route('scholarships.index'),
                ])
                <div class="grid grid--scholarships">
                    @foreach ($featuredScholarships as $scholarship)
                        @include('partials.scholarship-card', ['scholarship' => $scholarship])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            @include('partials.section-head', [
                'title' => 'Kuliah sesuai jadwalmu',
                'subtitle' => 'Pilih kelas yang pas dengan waktu luangmu, termasuk kelas malam dan akhir pekan untuk yang bekerja.',
                'linkLabel' => 'Lihat semua kampus',
                'linkUrl' => route('campuses.index'),
            ])
            @include('partials.link-tiles', [
                'modifier' => 'link-tiles--five',
                'tiles' => [
                    ['icon' => 'sun-horizon', 'title' => 'Kuliah pagi', 'text' => 'Waktu belajar penuh', 'url' => route('campuses.by-schedule', ['schedule' => 'pagi'])],
                    ['icon' => 'sun', 'title' => 'Kuliah sore', 'text' => 'Setelah jam sekolah', 'url' => route('campuses.by-schedule', ['schedule' => 'sore'])],
                    ['icon' => 'moon', 'title' => 'Kuliah malam', 'text' => 'Selepas jam kerja', 'url' => route('campuses.by-schedule', ['schedule' => 'malam'])],
                    ['icon' => 'calendar-check', 'title' => 'Akhir pekan', 'text' => 'Sabtu dan Minggu', 'url' => route('campuses.by-schedule', ['schedule' => 'akhir-pekan'])],
                    ['icon' => 'arrows-clockwise', 'title' => 'Kelas shift', 'text' => 'Jadwal bergantian', 'url' => route('campuses.by-schedule', ['schedule' => 'shift'])],
                ],
            ])
        </div>
    </section>

    <section class="section section--tint">
        <div class="container">
            @include('partials.section-head', [
                'title' => 'Program dan metode belajar',
                'subtitle' => 'Reguler, kelas karyawan, atau rekognisi pembelajaran lampau. Tatap muka, blended, hybrid, atau online.',
            ])
            @include('partials.link-tiles', [
                'tiles' => [
                    ['icon' => 'briefcase', 'title' => 'Kelas karyawan', 'text' => 'Kuliah sambil bekerja', 'url' => route('campuses.by-program', ['programType' => 'karyawan'])],
                    ['icon' => 'student', 'title' => 'Kelas reguler', 'text' => 'Untuk lulusan SMA', 'url' => route('campuses.by-program', ['programType' => 'reguler'])],
                    ['icon' => 'certificate', 'title' => 'RPL', 'text' => 'Pengakuan pengalaman kerja', 'url' => route('campuses.by-program', ['programType' => 'rpl'])],
                    ['icon' => 'chalkboard-teacher', 'title' => 'Tatap muka', 'text' => 'Belajar langsung di kelas', 'url' => route('campuses.by-method', ['method' => 'tatap-muka'])],
                    ['icon' => 'git-merge', 'title' => 'Blended', 'text' => 'Kelas dan daring', 'url' => route('campuses.by-method', ['method' => 'blended'])],
                    ['icon' => 'laptop', 'title' => 'Full online', 'text' => 'Dari mana saja', 'url' => route('campuses.by-method', ['method' => 'full-online'])],
                ],
            ])
        </div>
    </section>

    @if (! empty($latestArticles) && count($latestArticles))
        <section class="section section--tint home-articles">
            <div class="container">
                @include('partials.section-head', [
                    'title' => 'Artikel terbaru',
                    'subtitle' => 'Panduan memilih kampus, jurusan, beasiswa, dan biaya kuliah.',
                    'linkLabel' => 'Lihat semua artikel',
                    'linkUrl' => route('articles.index'),
                ])
                <div class="grid grid--articles">
                    @foreach ($latestArticles as $article)
                        @include('partials.article-card', ['article' => $article])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            @include('partials.section-head', [
                'title' => 'Cari kampus berdasarkan kota',
            ])
            <div class="city-list">
                @foreach (['Yogyakarta', 'Bandung', 'Surabaya', 'Malang', 'Semarang', 'Depok', 'Bogor', 'Medan'] as $city)
                    <a href="{{ route('campuses.index', ['city' => $city]) }}">{{ $city }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cta-banner">
                <div>
                    <span class="eyebrow">Tes potensi</span>
                    <h2 class="cta-banner__title">Masih bingung memilih jurusan?</h2>
                    <p class="cta-banner__text">Kerjakan tes minat dan bakat gratis untuk mendapatkan rekomendasi jurusan yang cocok denganmu.</p>
                    <div class="cta-banner__actions">
                        <a class="btn btn--light btn--lg" href="{{ route('tests.index') }}">Mulai tes</a>
                    </div>
                </div>
                <div class="test-list">
                    <a class="test-list__item" href="{{ route('tests.start', 'riasec') }}">
                        <i class="ph ph-target" aria-hidden="true"></i>
                        <span>Tes RIASEC <small>Cocokkan minat dengan jurusan</small></span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a class="test-list__item" href="{{ route('tests.start', 'learning-style') }}">
                        <i class="ph ph-lightbulb" aria-hidden="true"></i>
                        <span>Gaya belajar <small>Kenali cara belajar yang efektif</small></span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a class="test-list__item" href="{{ route('tests.start', 'mbti') }}">
                        <i class="ph ph-identification-card" aria-hidden="true"></i>
                        <span>Tes MBTI <small>Pahami tipe kepribadianmu</small></span>
                        <i class="ph ph-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="cta-banner">
                <div>
                    <span class="eyebrow">Program afiliasi</span>
                    <h2 class="cta-banner__title">Ajak teman kuliah, dapatkan komisi</h2>
                    <p class="cta-banner__text">Bagikan tautan rujukanmu. Setiap mahasiswa yang mendaftar dan menyelesaikan pembayaran lewat tautanmu menghasilkan komisi.</p>
                    <div class="cta-banner__actions">
                        <a class="btn btn--light btn--lg" href="{{ route('affiliate.index') }}">Pelajari afiliasi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section section--tint">
        <div class="container partner">
            <div class="partner__panel" aria-hidden="true">
                <span class="partner__badge"><i class="ph ph-buildings" aria-hidden="true"></i></span>
            </div>
            <div>
                <span class="eyebrow">Untuk kampus</span>
                <h2 class="section__title">Jaring calon mahasiswa baru</h2>
                <ul class="partner__list">
                    <li><i class="ph ph-check-circle" aria-hidden="true"></i> Tampilkan profil dan program studi kampusmu</li>
                    <li><i class="ph ph-check-circle" aria-hidden="true"></i> Unggah brosur untuk diunduh calon mahasiswa</li>
                    <li><i class="ph ph-check-circle" aria-hidden="true"></i> Pantau daftar peminat dari satu dasbor</li>
                </ul>
                <a class="btn btn--primary btn--lg" href="{{ route('soon') }}">Daftarkan kampus</a>
            </div>
        </div>
    </section>
@endsection
