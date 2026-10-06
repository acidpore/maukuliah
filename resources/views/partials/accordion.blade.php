{{-- Props: $items (iterable berisi question dan answer), $title? --}}
@if (count($items))
    <div class="accordion">
        @isset($title)
            <h2 class="content-panel__title">{{ $title }}</h2>
        @endisset
        @foreach ($items as $item)
            <details class="accordion__item">
                <summary class="accordion__summary">
                    <span>{{ $item->question }}</span>
                    <i class="ph ph-caret-down" aria-hidden="true"></i>
                </summary>
                <div class="accordion__body">{{ $item->answer }}</div>
            </details>
        @endforeach
    </div>
@endif
