{{-- Props: $campus. Logo bila ada, jatuh ke inisial. --}}
@if ($campus->logoUrl())
    <span class="monogram monogram--logo"><img src="{{ $campus->logoUrl() }}" alt="Logo {{ $campus->name }}" loading="lazy"></span>
@else
    <span class="monogram">{{ $campus->initials() }}</span>
@endif
