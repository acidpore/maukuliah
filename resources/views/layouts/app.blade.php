@php
    $routeOrSoon = fn (string $name) => Route::has($name) ? route($name) : route('soon');

    $navItems = [
        ['label' => 'Kampus', 'url' => route('campuses.index'), 'pattern' => 'campuses.*'],
        ['label' => 'Jurusan', 'url' => route('majors.index'), 'pattern' => 'majors.*'],
        ['label' => 'Karier', 'url' => $routeOrSoon('careers.index'), 'pattern' => 'careers.*'],
        ['label' => 'Beasiswa', 'url' => $routeOrSoon('scholarships.index'), 'pattern' => 'scholarships.*'],
        ['label' => 'Tryout', 'url' => $routeOrSoon('tryouts.index'), 'pattern' => 'tryouts.*'],
        ['label' => 'Tes Potensi', 'url' => $routeOrSoon('tests.index'), 'pattern' => 'tests.*'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Temukan Kampus dan Jurusan Impianmu') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Platform pencarian kampus, jurusan, karier, dan beasiswa untuk calon mahasiswa Indonesia.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/features.css') }}">
</head>
<body>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    @include('partials.navbar', ['navItems' => $navItems])

    <main id="konten">
        @yield('content')
    </main>

    @include('partials.footer', ['navItems' => $navItems])

    <script src="{{ asset('js/navbar-toggle.js') }}" defer></script>
    <script src="{{ asset('js/typed-text.js') }}" defer></script>
    <script src="{{ asset('js/tabs-spy.js') }}" defer></script>
    <script src="{{ asset('js/auto-submit.js') }}" defer></script>
    <script src="{{ asset('js/program-filter.js') }}" defer></script>
    <script src="{{ asset('js/test-progress.js') }}" defer></script>
    <script src="{{ asset('js/copy-field.js') }}" defer></script>
    <script src="{{ asset('js/reveal.js') }}" defer></script>
    <script src="{{ asset('js/countdown.js') }}" defer></script>
    <script src="{{ asset('js/campus-majors.js') }}" defer></script>
</body>
</html>
