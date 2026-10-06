{{-- Props: $amount (int|null), $original? (int|null), $suffix? (string), $prefix? (string) --}}
@if ($amount !== null)
    <p class="price">
        @isset($prefix)
            <span class="price__prefix">{{ $prefix }}</span>
        @endisset
        @if (! empty($original) && $original > $amount)
            <s class="price__old">Rp{{ number_format($original, 0, ',', '.') }}</s>
        @endif
        <strong class="price__amount">Rp{{ number_format($amount, 0, ',', '.') }}</strong>
        @isset($suffix)
            <span class="price__suffix">{{ $suffix }}</span>
        @endisset
    </p>
@endif
