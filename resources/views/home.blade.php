@extends('layouts.app')
@section('title', 'Temukan Kampus dan Jurusan Impianmu')
@section('content')
<div class="mk-home">
    <section class="mk-hero">
        <div class="container mk-hero__inner">
            <div class="mk-hero__copy">
                <h1>Persiapkan kuliah dengan mudah,<br><span>raih masa depan cerahmu.</span></h1>
                <p>Temukan kampus dan jurusan yang pas. Mulai perjalanan kuliahmu bersama Siapkuliah.</p>
                <form class="mk-search" action="{{ route('search') }}" method="GET" role="search">
                    <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" name="q" placeholder="Cari kampus, jurusan, atau kota impianmu" aria-label="Cari kampus, jurusan, atau kota">
                    <button type="submit" aria-label="Cari"><i class="ph ph-arrow-right" aria-hidden="true"></i></button>
                </form>
            </div>
            <img class="mk-hero__students" src="{{ asset('images/landing/students.webp') }}" alt="Dua siswa menyambut perjalanan menuju dunia kuliah" width="680" height="680" fetchpriority="high">
        </div>
    </section>
    <div class="container mk-shortcuts">
        @foreach ([['Tes Potensi','tests.index','potential'],['Kampus','campuses.index','campus'],['Jurusan','majors.index','major'],['Tryout','tryouts.index','tryout'],['Beasiswa','scholarships.index','scholarship'],['Karier','careers.index','career']] as [$label,$target,$image])
            <a href="{{ route($target) }}"><img src="{{ asset('images/landing/'.$image.'.webp') }}" alt="" width="230" height="154"><span>{{ $label }}</span></a>
        @endforeach
    </div>
    <section class="mk-section container">
        @include('partials.section-head', ['title'=>'Mau kuliah di kampus favorit?', 'subtitle'=>'Temukan kampus pilihan dan program studi yang sesuai dengan impianmu.', 'linkLabel'=>'Lihat kampus lainnya', 'linkUrl'=>route('campuses.index')])
        <div class="mk-slider" data-slider>
            <div class="mk-rail" data-rail>
                @forelse ($featuredCampuses as $campus)
                <a class="mk-campus" href="{{ route('campuses.show', $campus) }}">
                    @php($photo = 'images/campuses/'.$campus->slug.'/'.$campus->slug.'-1.jpg')
                    <div class="mk-campus__image">@if (file_exists(public_path($photo)))<img src="{{ asset($photo) }}" alt="Kampus {{ $campus->name }}" loading="lazy" width="400" height="240">@else<i class="ph ph-buildings" aria-hidden="true"></i>@endif</div>
                    <div class="mk-campus__body"><div class="mk-campus__top">@include('partials.campus-logo', ['campus'=>$campus])<span class="mk-tag">{{ $campus->typeLabel() }}</span></div><h3>{{ $campus->name }}</h3><p><i class="ph ph-map-pin" aria-hidden="true"></i> {{ $campus->city }}</p><div class="mk-campus__meta"><span>Akreditasi {{ $campus->accreditation }}</span><span>{{ $campus->majors_count }} prodi</span></div></div>
                </a>
                @empty <p>Belum ada kampus yang tersedia.</p> @endforelse
            </div>
            <button class="mk-next" type="button" data-next aria-label="Lihat kampus selanjutnya"><i class="ph ph-arrow-right" aria-hidden="true"></i></button>
        </div>
    </section>
    <section class="mk-section container">
        @include('partials.section-head', ['title'=>'Mau kuliah di kota impian?', 'subtitle'=>'Cari suasana baru, temukan kampus di kota pilihanmu.', 'linkLabel'=>'Lihat semua kota', 'linkUrl'=>route('campuses.index')])
        <div class="mk-cities">
            @foreach ([['Yogyakarta','universitas-gadjah-mada'],['Bandung','institut-teknologi-bandung'],['Surabaya','universitas-airlangga'],['Depok','universitas-indonesia']] as [$city,$slug])
                @php($slug = str_replace(' ', '', $slug))
                <a href="{{ route('campuses.index', ['city'=>$city]) }}"><img src="{{ asset('images/campuses/'.$slug.'/'.$slug.'-1.jpg') }}" alt="Kampus di {{ $city }}" width="400" height="300" loading="lazy"><span><strong>{{ $city }}</strong><span>Jelajahi kampus <i class="ph ph-arrow-up-right" aria-hidden="true"></i></span></span></a>
            @endforeach
        </div>
    </section>
    <section class="mk-section container">
        @include('partials.section-head', ['title'=>'Jurusan kuliah terpopuler', 'subtitle'=>'Kenali bidang yang kamu suka dan peluang masa depannya.', 'linkLabel'=>'Lihat jurusan lainnya', 'linkUrl'=>route('majors.index')])
        <div class="mk-majors">
            @foreach ($featuredMajors->take(4) as $major)
                <a href="{{ route('majors.show', $major) }}"><div class="mk-major-art mk-major-art--{{ $loop->index }}"><i class="ph ph-{{ ['chart-line-up','calculator','scales','chats'][$loop->index] }}" aria-hidden="true"></i></div><div><span class="mk-tag">{{ $major->category }}</span><h3>{{ $major->name }}</h3><p>{{ $major->campuses_count }} kampus menyediakan jurusan ini</p><span class="mk-text-link">Kenali jurusan <i class="ph ph-arrow-right" aria-hidden="true"></i></span></div></a>
            @endforeach
        </div>
    </section>
    <section class="container mk-test">
        <img src="{{ asset('images/landing/potential.webp') }}" alt="Ilustrasi mengenali minat dan potensi diri" width="460" height="308" loading="lazy">
        <div><span class="mk-tag">Kenali dirimu lebih dekat</span><h2>Masih bingung pilih jurusan?<br>Yuk, ikuti Tes Potensi!</h2><p>Temukan minat, gaya belajar, dan kepribadianmu sebagai bekal memilih jurusan yang sesuai.</p><a class="btn btn--primary" href="{{ route('tests.index') }}">Mulai tes sekarang <i class="ph ph-arrow-right" aria-hidden="true"></i></a></div>
    </section>
    <section class="mk-benefits">
        <div class="container"><h2>Mengapa mempersiapkan kuliah di Siapkuliah?</h2><div class="mk-benefits__grid">
            @foreach ([['buildings','Banyak pilihan kampus','Bandingkan kampus, jurusan, dan akreditasi untuk menemukan pilihan yang paling sesuai.'],['clipboard-text','Mudah mengajukan pendaftaran','Cari program studi pilihan dan ajukan pendaftaran kuliah langsung dari satu tempat.'],['devices','Akses di mana pun, kapan pun','Persiapkan kuliah dari laptop atau ponsel, kapan saja kamu punya waktu.']] as [$icon,$title,$text])
                <article><i class="ph ph-{{ $icon }}" aria-hidden="true"></i><h3>{{ $title }}</h3><p>{{ $text }}</p></article>
            @endforeach
        </div></div>
    </section>
    <section class="mk-section container">
        @include('partials.section-head', ['title'=>'Wujudkan impian kuliahmu dengan beasiswa', 'subtitle'=>'Jangan biarkan biaya menghalangi langkahmu. Temukan kesempatan beasiswa di sini.', 'linkLabel'=>'Lihat beasiswa lainnya', 'linkUrl'=>route('scholarships.index')])
        <div class="mk-scholarships">
            @forelse ($featuredScholarships as $scholarship)
                <a href="{{ route('scholarships.show', $scholarship) }}"><img src="{{ asset('images/landing/scholarship.webp') }}" alt="" width="230" height="154" loading="lazy"><div><span class="mk-tag">{{ $scholarship->isOpen() ? 'Pendaftaran dibuka' : 'Info beasiswa' }}</span><h3>{{ $scholarship->name }}</h3><p>{{ $scholarship->campus?->name ?? $scholarship->provider }}</p><small><i class="ph ph-calendar-blank" aria-hidden="true"></i> {{ $scholarship->periodLabel() }}</small></div></a>
            @empty <div class="mk-empty"><i class="ph ph-graduation-cap" aria-hidden="true"></i><h3>Siapkan langkah untuk beasiswa berikutnya</h3><p>Lihat informasi dan persyaratan beasiswa yang tersedia.</p><a class="mk-text-link" href="{{ route('scholarships.index') }}">Jelajahi beasiswa <i class="ph ph-arrow-right" aria-hidden="true"></i></a></div> @endforelse
        </div>
    </section>
    <section class="mk-section container">
        @include('partials.section-head', ['title'=>'Ketahui prospek karier incaranmu', 'subtitle'=>'Dari jurusan pilihan, temukan profesi yang ingin kamu tuju.', 'linkLabel'=>'Lihat karier lainnya', 'linkUrl'=>route('careers.index')])
        <div class="mk-careers">@foreach ($featuredCareers->take(4) as $career)<a href="{{ route('careers.show', $career) }}"><i class="ph ph-{{ ['briefcase','megaphone','desktop','pencil-ruler'][$loop->index] }}" aria-hidden="true"></i><h3>{{ $career->name }}</h3><p>{{ Str::limit(strip_tags($career->description), 105) }}</p>@if ($career->salaryRange())<small>{{ $career->salaryRange() }}</small>@endif</a>@endforeach</div>
    </section>
    @if ($latestArticles->isNotEmpty())
    <section class="mk-section container">
        @include('partials.section-head', ['title'=>'Temukan inspirasi untuk kuliahmu', 'subtitle'=>'Tips dan panduan untuk menemani persiapan kuliahmu.', 'linkLabel'=>'Lihat artikel lainnya', 'linkUrl'=>route('articles.index')])
        <div class="mk-articles">@foreach ($latestArticles->take(4) as $article)<a class="{{ $loop->first ? 'mk-article-featured' : 'mk-article-small' }}" href="{{ route('articles.show', $article) }}">@if ($loop->first)<img src="{{ asset('images/landing/major.webp') }}" alt="Ilustrasi siswa mempersiapkan kuliah" width="600" height="400" loading="lazy">@endif<div><span class="mk-tag">{{ $loop->first ? 'Artikel terbaru' : $article->categoryLabel() }}</span><h3>{{ $article->title }}</h3><p>{{ Str::limit($article->excerpt, 110) }}</p><small>{{ $article->publishedLabel() }} / {{ $article->readingMinutes() }} menit baca</small></div></a>@endforeach</div>
    </section>
    @endif
    <section class="container mk-partner"><div><h2>Kampusmu, pilihan masa depan mereka.</h2><p>Tampilkan profil kampus dan program studi. Terhubung dengan calon mahasiswa melalui Siapkuliah.</p><a class="btn btn--primary" href="{{ route('soon') }}">Daftarkan kampus <i class="ph ph-arrow-right" aria-hidden="true"></i></a></div><img src="{{ asset('images/landing/campus.webp') }}" alt="Ilustrasi kampus dan mahasiswa" width="345" height="230" loading="lazy"></section>
    <section class="mk-about"><div class="container"><h3>Mengenal Siapkuliah</h3><p>Siapkuliah membantu calon mahasiswa mencari dan membandingkan kampus, mengenali jurusan sesuai minat dan bakat, serta menemukan beasiswa dan prospek karier. Dengan tes potensi dan latihan tryout, kamu dapat mempersiapkan langkah menuju dunia kuliah dengan lebih percaya diri.</p><details><summary>Lihat selengkapnya</summary><p>Jelajahi profil kampus, program studi, dan pilihan jadwal belajar. Simpan kampus favoritmu, cari informasi pendaftaran, dan ajukan pendaftaran kuliah melalui platform ini.</p></details></div></section>
</div>
@endsection
