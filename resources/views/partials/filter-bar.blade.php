{{-- Props: $action, $query, $placeholder, $hidden? (array nama => nilai) --}}
<form class="toolbar__search" action="{{ $action }}" method="GET" role="search">
    @foreach ($hidden ?? [] as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach
    <i class="ph ph-magnifying-glass" aria-hidden="true"></i>
    <input class="toolbar__search-input" type="search" name="q" value="{{ $query }}" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}">
    <button class="btn btn--primary btn--sm" type="submit">Cari</button>
</form>
