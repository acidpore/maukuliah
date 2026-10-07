@php
    $adminUser = auth()->user();
    $isSuperAdmin = $adminUser->role === App\Enums\UserRole::SuperAdmin;
    $menu = [
        ['label' => 'Dasbor', 'icon' => 'squares-four', 'url' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard'), 'visible' => true],
        ['label' => 'Verifikasi kampus', 'icon' => 'seal-check', 'url' => route('admin.campuses.index'), 'active' => request()->routeIs('admin.campuses.*'), 'visible' => $isSuperAdmin],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dasbor') - Admin {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/features.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin">
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    <div class="admin-shell" data-admin>
        <aside class="admin-sidebar" id="admin-sidebar" data-admin-sidebar aria-label="Menu admin">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <span class="admin-brand__mark" aria-hidden="true">K</span>
                <span class="admin-brand__text">{{ config('app.name') }} <small>Admin</small></span>
            </a>

            <nav class="admin-menu">
                @foreach ($menu as $item)
                    @if ($item['visible'])
                        <a class="admin-menu__link {{ $item['active'] ? 'is-active' : '' }}" href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif>
                            <i class="ph ph-{{ $item['icon'] }}" aria-hidden="true"></i> {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <a class="admin-menu__link admin-sidebar__site" href="{{ route('home') }}">
                <i class="ph ph-arrow-square-out" aria-hidden="true"></i> Lihat situs
            </a>
        </aside>

        <div class="admin-backdrop" data-admin-backdrop hidden></div>

        <div class="admin-body">
            <header class="admin-topbar">
                <button class="admin-topbar__toggle" type="button" data-admin-toggle aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka menu">
                    <i class="ph ph-list" aria-hidden="true"></i>
                </button>
                <span class="admin-topbar__role">{{ $isSuperAdmin ? 'Pengelola platform' : 'Admin kampus' }}</span>
                <div class="admin-topbar__user">
                    <span class="admin-topbar__name">{{ $adminUser->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn--ghost btn--sm" type="submit"><i class="ph ph-sign-out" aria-hidden="true"></i> Keluar</button>
                    </form>
                </div>
            </header>

            <main class="admin-main" id="konten">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/auto-submit.js') }}" defer></script>
    <script src="{{ asset('js/admin-drawer.js') }}" defer></script>
</body>
</html>
