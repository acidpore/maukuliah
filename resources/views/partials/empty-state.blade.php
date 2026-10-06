{{-- Props: $title, $text, $icon?, $actionLabel?, $actionUrl? --}}
<div class="empty">
    <span class="empty__icon"><i class="ph ph-{{ $icon ?? 'magnifying-glass' }}" aria-hidden="true"></i></span>
    <h2 class="empty__title">{{ $title }}</h2>
    <p class="empty__text">{{ $text }}</p>
    @isset($actionUrl)
        <a class="btn btn--primary" href="{{ $actionUrl }}">{{ $actionLabel }}</a>
    @endisset
</div>
