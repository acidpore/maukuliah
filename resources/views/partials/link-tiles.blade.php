{{-- Props: $tiles (array berisi icon, title, text, url), $modifier? --}}
<div class="link-tiles {{ $modifier ?? '' }}">
    @foreach ($tiles as $tile)
        <a class="link-tile" href="{{ $tile['url'] }}">
            <i class="link-tile__icon ph ph-{{ $tile['icon'] }}" aria-hidden="true"></i>
            <span class="link-tile__title">{{ $tile['title'] }}</span>
            @isset($tile['text'])
                <span class="link-tile__text">{{ $tile['text'] }}</span>
            @endisset
        </a>
    @endforeach
</div>
