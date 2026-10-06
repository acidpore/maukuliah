{{-- Props: $items (array berisi ['label' => string, 'url' => string|null]) --}}
<nav class="breadcrumb" aria-label="Breadcrumb">
    @foreach ($items as $item)
        @if (! $loop->first)
            <span class="breadcrumb__sep">/</span>
        @endif
        @if ($item['url'] ?? null)
            <a class="breadcrumb__link" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        @else
            <span aria-current="page">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
