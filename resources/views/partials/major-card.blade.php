{{-- Props: $major (dengan campuses_count) --}}
<a class="major-card" href="{{ route('majors.show', $major) }}">
    @include('partials.badge', ['label' => $major->category, 'variant' => 'cat'])
    <h3 class="major-card__name">{{ $major->name }}</h3>
    <p class="major-card__campuses">{{ $major->campuses_count }} kampus menyediakan</p>
</a>
