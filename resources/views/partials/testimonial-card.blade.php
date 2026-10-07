{{-- Props: $testimonial. Inisial dipakai karena alumni belum punya foto. --}}
<figure class="testimonial-card">
    <blockquote class="testimonial-card__quote">{{ $testimonial->quote }}</blockquote>
    <figcaption class="testimonial-card__who">
        <span class="monogram monogram--sm" aria-hidden="true">{{ $testimonial->initials() }}</span>
        <span>
            <span class="testimonial-card__name">{{ $testimonial->name }}</span>
            <span class="testimonial-card__meta">
                {{ $testimonial->major_name }}@if ($testimonial->graduation_year), angkatan {{ $testimonial->graduation_year }}@endif
            </span>
            <span class="testimonial-card__job">{{ $testimonial->current_job }}</span>
        </span>
    </figcaption>
</figure>
