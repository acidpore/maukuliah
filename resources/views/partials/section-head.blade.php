{{-- Props: $title, $subtitle?, $eyebrow?, $linkLabel?, $linkUrl? --}}
<div class="section__head">
    <div>
        @isset($eyebrow)
            <span class="eyebrow">{{ $eyebrow }}</span>
        @endisset
        <h2 class="section__title">{{ $title }}</h2>
        @isset($subtitle)
            <p class="section__subtitle">{{ $subtitle }}</p>
        @endisset
    </div>
    @isset($linkUrl)
        <a class="section__link" href="{{ $linkUrl }}">{{ $linkLabel }} <i class="ph ph-arrow-right" aria-hidden="true"></i></a>
    @endisset
</div>
