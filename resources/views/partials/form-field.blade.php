{{-- Props: $name, $label, $type? (text), $value?, $required? (true), $hint?, $autocomplete? --}}
@php
    $isRequired = $required ?? true;
@endphp
<div class="field {{ $errors->has($name) ? 'field--error' : '' }}">
    <label class="field__label" for="field-{{ $name }}">{{ $label }}@if ($isRequired)<span class="field__required" aria-hidden="true">*</span>@endif</label>
    <input class="field__control" id="field-{{ $name }}" type="{{ $type ?? 'text' }}" name="{{ $name }}" value="{{ $value ?? old($name) }}" @required($isRequired) @isset($autocomplete) autocomplete="{{ $autocomplete }}" @endisset aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}">
    @isset($hint)
        <p class="field__hint">{{ $hint }}</p>
    @endisset
    @error($name)
        <p class="field__error" role="alert">{{ $message }}</p>
    @enderror
</div>
