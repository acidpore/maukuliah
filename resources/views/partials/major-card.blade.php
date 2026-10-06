<a class="major-card" href="{{ route('majors.show', $major) }}">
    <span class="badge badge--cat">{{ $major->category }}</span>
    <h3 class="major-card__name">{{ $major->name }}</h3>
    <p class="major-card__campuses">{{ $major->campuses_count }} kampus menyediakan</p>
</a>
