{{-- Props: $type (campus|major|career|scholarship), $model (instance Eloquent), $variant? (kelas tambahan) --}}
@auth
    @php
        // Relasi favorites dimuat sekali per request, jadi banyak tombol tidak menambah query.
        $isActive = auth()->user()->favorites->contains(
            fn ($favorite) => $favorite->favoritable_type === $model->getMorphClass()
                && $favorite->favoritable_id === $model->getKey()
        );
    @endphp
    <form class="favorite-button {{ $variant ?? '' }}" method="POST" action="{{ route('favorites.toggle') }}">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="id" value="{{ $model->getKey() }}">
        <button class="btn btn--ghost {{ $isActive ? 'is-active' : '' }}" type="submit" aria-pressed="{{ $isActive ? 'true' : 'false' }}">
            <i class="ph ph-heart" aria-hidden="true"></i>
            {{ $isActive ? 'Hapus dari favorit' : 'Simpan ke favorit' }}
        </button>
    </form>
@else
    <a class="btn btn--ghost {{ $variant ?? '' }}" href="{{ route('login') }}"><i class="ph ph-heart" aria-hidden="true"></i> Simpan ke favorit</a>
@endauth
