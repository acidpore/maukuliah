{{-- Props: $scholarship (campus opsional) --}}
<a class="scholarship-card" href="{{ route('scholarships.show', $scholarship) }}">
    @include('partials.badge', [
        'label' => $scholarship->isOpen() ? 'Sedang dibuka' : 'Ditutup',
        'variant' => $scholarship->isOpen() ? 'open' : 'closed',
    ])
    <h3 class="scholarship-card__name">{{ $scholarship->name }}</h3>
    <p class="scholarship-card__provider">{{ $scholarship->campus?->name ?? $scholarship->provider }}</p>
    <p class="scholarship-card__period">
        <i class="ph ph-calendar-blank" aria-hidden="true"></i>
        {{ $scholarship->periodLabel() }}
    </p>
</a>
