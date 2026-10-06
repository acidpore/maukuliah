{{-- Props: $name, $label, $options (array nilai => teks), $selected?, $placeholder?, $required? (true) --}}
@php
    $isRequired = $required ?? true;
    $current = (string) ($selected ?? old($name));
@endphp
<div class="field {{ $errors->has($name) ? 'field--error' : '' }}">
    <label class="field__label" for="field-{{ $name }}">{{ $label }}@if ($isRequired)<span class="field__required" aria-hidden="true">*</span>@endif</label>
    <select class="field__control" id="field-{{ $name }}" name="{{ $name }}" @required($isRequired) aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}">
        <option value="">{{ $placeholder ?? 'Pilih salah satu' }}</option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected($current === (string) $value)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="field__error" role="alert">{{ $message }}</p>
    @enderror
</div>
