{{-- Props: $campus (dengan majors_count; min_monthly_installment dan studyPrograms bila dimuat) --}}
@php
    $programs = $campus->relationLoaded('studyPrograms') ? $campus->studyPrograms : collect();
    $programTypes = $programs->pluck('program_type')->unique();
    $schedules = $programs->flatMap(fn ($program) => $program->schedules)->unique();
@endphp
<a class="campus-card" href="{{ route('campuses.show', $campus) }}">
    <div class="campus-card__head">
        @include('partials.campus-logo', ['campus' => $campus])
        @include('partials.badge', ['label' => $campus->typeLabel(), 'variant' => $campus->type])
    </div>
    <h3 class="campus-card__name">{{ $campus->name }}</h3>
    <p class="campus-card__location"><i class="ph ph-map-pin" aria-hidden="true"></i> {{ $campus->city }}, {{ $campus->province }}</p>
    @if ($programTypes->isNotEmpty() || $schedules->isNotEmpty())
        <ul class="campus-card__tags" aria-label="Program dan jadwal">
            @foreach ($programTypes as $type)
                <li>@include('partials.badge', ['label' => $type->label(), 'variant' => 'outline'])</li>
            @endforeach
            @foreach ($schedules as $schedule)
                <li>@include('partials.badge', ['label' => $schedule->label(), 'variant' => 'cat'])</li>
            @endforeach
        </ul>
    @endif
    @if (! empty($campus->min_monthly_installment))
        @include('partials.price', ['prefix' => 'Mulai dari', 'amount' => (int) $campus->min_monthly_installment, 'suffix' => '/ bulan'])
    @endif
    <div class="campus-card__meta">
        @include('partials.badge', ['label' => 'Akreditasi '.$campus->accreditation, 'variant' => 'accred'])
        <span class="campus-card__majors">{{ $campus->majors_count }} program studi</span>
    </div>
</a>
