{{-- Tombol masuk dan daftar untuk tamu, atau menu pengguna bila sudah masuk. Tanpa props. --}}
@auth
    @php
        $authUser = auth()->user();
        $canAccessAdmin = in_array($authUser->role->value, ['super_admin', 'campus_admin'], true);
        $affiliateUrl = $authUser->affiliate ? route('affiliate.dashboard') : route('affiliate.index');
    @endphp
    <details class="user-menu">
        <summary class="user-menu__summary">
            <span class="user-menu__avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($authUser->name, 0, 1)) }}</span>
            <span class="user-menu__name">{{ $authUser->name }}</span>
            <i class="ph ph-caret-down" aria-hidden="true"></i>
        </summary>
        <div class="user-menu__panel">
            <a class="user-menu__link" href="{{ route('favorites.index') }}"><i class="ph ph-heart" aria-hidden="true"></i> Favorit</a>
            <a class="user-menu__link" href="{{ route('tests.history') }}"><i class="ph ph-clipboard-text" aria-hidden="true"></i> Riwayat tes</a>
            <a class="user-menu__link" href="{{ $affiliateUrl }}"><i class="ph ph-share-network" aria-hidden="true"></i> Afiliasi</a>
            @if ($canAccessAdmin)
                <a class="user-menu__link" href="{{ route('admin.dashboard') }}"><i class="ph ph-squares-four" aria-hidden="true"></i> Dasbor admin</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="user-menu__link user-menu__link--button" type="submit"><i class="ph ph-sign-out" aria-hidden="true"></i> Keluar</button>
            </form>
        </div>
    </details>
@else
    <a class="btn btn--ghost btn--sm" href="{{ route('login') }}">Masuk</a>
    <a class="btn btn--primary btn--sm" href="{{ route('register') }}">Daftar</a>
@endauth
