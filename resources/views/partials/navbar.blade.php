<header class="site-header">
    <div class="container navbar">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ config('app.name') }}">
            <span class="brand__mark">K</span>
            <span>{{ config('app.name') }}</span>
        </a>

        <nav class="nav" id="site-nav">
            <a class="nav__link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="nav__link {{ request()->routeIs('campuses.*') ? 'is-active' : '' }}" href="{{ route('campuses.index') }}">Kampus</a>
            <a class="nav__link {{ request()->routeIs('majors.*') ? 'is-active' : '' }}" href="{{ route('majors.index') }}">Jurusan</a>
        </nav>

        <div class="navbar__right">
            <form class="nav-search" action="{{ route('search') }}" method="GET" role="search">
                <input class="nav-search__input" type="search" name="q" value="{{ request('q') }}" placeholder="Cari kampus atau jurusan..." aria-label="Cari kampus atau jurusan">
                <button class="nav-search__btn" type="submit" aria-label="Kirim pencarian">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                </button>
            </form>
            <a class="btn btn--primary btn--sm" href="{{ route('soon') }}">Masuk</a>
            <button class="nav-toggle" type="button" aria-label="Buka menu" aria-expanded="false" aria-controls="site-nav">
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
            </button>
        </div>
    </div>
</header>
