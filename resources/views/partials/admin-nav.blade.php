{{-- Props: $active (dashboard|campuses) --}}
<nav class="subnav" aria-label="Navigasi admin">
    <a class="subnav__link {{ $active === 'dashboard' ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">Calon mahasiswa</a>
    @can('verify', App\Models\Campus::class)
        <a class="subnav__link {{ $active === 'campuses' ? 'is-active' : '' }}" href="{{ route('admin.campuses.index') }}">Verifikasi kampus</a>
    @endcan
</nav>
