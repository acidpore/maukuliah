{{-- Props: $career --}}
<a class="row-card" href="{{ route('careers.show', $career) }}">
    <h3 class="row-card__title">{{ $career->name }}</h3>
    @if ($career->salaryRange())
        <span class="row-card__side">{{ $career->salaryRange() }}</span>
    @endif
    <p class="row-card__text">{{ $career->description }}</p>
</a>
