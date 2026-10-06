<a class="campus-card" href="{{ route('campuses.show', $campus) }}">
    <div class="campus-card__head">
        <span class="monogram">{{ $campus->initials() }}</span>
        <span class="badge badge--{{ $campus->type }}">{{ $campus->typeLabel() }}</span>
    </div>
    <h3 class="campus-card__name">{{ $campus->name }}</h3>
    <p class="campus-card__location">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        {{ $campus->city }}, {{ $campus->province }}
    </p>
    <div class="campus-card__meta">
        <span class="badge badge--accred">{{ $campus->accreditation }}</span>
        <span class="campus-card__majors">{{ $campus->majors_count }} Program Studi</span>
    </div>
</a>
