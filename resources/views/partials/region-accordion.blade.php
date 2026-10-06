{{-- Props: $regions (koleksi nama wilayah), $regionRoute? (nama rute SEO), $regionParams? (array), $title? --}}
@if (count($regions))
    <details class="accordion__item region-accordion">
        <summary class="accordion__summary">
            <span>{{ $title ?? 'Cari berdasarkan wilayah' }}</span>
            <i class="ph ph-caret-down" aria-hidden="true"></i>
        </summary>
        <ul class="region-accordion__list">
            @foreach ($regions as $region)
                @php
                    $regionUrl = isset($regionRoute)
                        ? route($regionRoute, ($regionParams ?? []) + ['region' => Str::slug($region)])
                        : route('campuses.index', ['province' => $region]);
                @endphp
                <li><a href="{{ $regionUrl }}">{{ $region }}</a></li>
            @endforeach
        </ul>
    </details>
@endif
