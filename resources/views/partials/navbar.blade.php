<header class="site-header">
    <div class="container navbar">
        <a class="brand" href="{{ route('home') }}" aria-label="{{ config('app.name') }}, beranda">
            <span class="brand__mark">{{ mb_substr(config('app.name'), 0, 1) }}</span>
            <span>{{ config('app.name') }}</span>
        </a>

        <nav class="nav" data-nav aria-label="Navigasi utama">
            @foreach ($navItems as $item)
                <a class="nav__link {{ request()->routeIs($item['pattern']) ? 'is-active' : '' }}" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            @endforeach
            <div class="nav__auth">
                @include('partials.auth-actions')
            </div>
        </nav>

        <div class="navbar__right">
            <div class="navbar__auth">
                @include('partials.auth-actions')
            </div>
            <button class="navbar__toggle" type="button" data-nav-toggle aria-label="Buka menu" aria-expanded="false">
                <i class="ph ph-list" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>
