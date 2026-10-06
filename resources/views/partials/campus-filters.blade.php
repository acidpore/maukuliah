{{-- Props: opsi dari CampusSearchService::indexData ($filters, $programTypes, $schedules, $methods, $degreeLevels, $typeOptions, $forms, $formLabels, $provinces, $cities, $accreditations) dan $resetUrl --}}
@php
    $selected = fn (string $key) => $filters[$key] ?? '';
@endphp
<div class="filter-form">
    <h2 class="filter-form__title">Filter</h2>

    <div class="filter-group">
        <label class="filter-label" for="filter-program">Program</label>
        <select class="filter-input" id="filter-program" name="program_type">
            <option value="">Semua program</option>
            @foreach ($programTypes as $option)
                <option value="{{ $option->value }}" @selected($selected('program_type') === $option->value)>{{ $option->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-schedule">Jadwal kuliah</label>
        <select class="filter-input" id="filter-schedule" name="schedule">
            <option value="">Semua jadwal</option>
            @foreach ($schedules as $option)
                <option value="{{ $option->value }}" @selected($selected('schedule') === $option->value)>{{ $option->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-method">Metode belajar</label>
        <select class="filter-input" id="filter-method" name="method">
            <option value="">Semua metode</option>
            @foreach ($methods as $option)
                <option value="{{ $option->value }}" @selected($selected('method') === $option->value)>{{ $option->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-degree">Jenjang</label>
        <select class="filter-input" id="filter-degree" name="degree_level">
            <option value="">Semua jenjang</option>
            @foreach ($degreeLevels as $option)
                <option value="{{ $option->value }}" @selected($selected('degree_level') === $option->value)>{{ $option->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <span class="filter-label" id="filter-fee-label">Biaya per bulan (Rp)</span>
        <div class="filter-range" role="group" aria-labelledby="filter-fee-label">
            <input class="filter-input" type="number" min="0" step="50000" name="fee_min" value="{{ $selected('fee_min') }}" placeholder="Minimum" aria-label="Biaya minimum">
            <input class="filter-input" type="number" min="0" step="50000" name="fee_max" value="{{ $selected('fee_max') }}" placeholder="Maksimum" aria-label="Biaya maksimum">
        </div>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-type">Jenis</label>
        <select class="filter-input" id="filter-type" name="type">
            <option value="">Semua jenis</option>
            @foreach ($typeOptions as $value => $label)
                <option value="{{ $value }}" @selected($selected('type') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-form">Bentuk</label>
        <select class="filter-input" id="filter-form" name="form">
            <option value="">Semua bentuk</option>
            @foreach ($forms as $form)
                <option value="{{ $form }}" @selected($selected('form') === $form)>{{ $formLabels[$form] ?? ucfirst($form) }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-province">Provinsi</label>
        <select class="filter-input" id="filter-province" name="province">
            <option value="">Semua provinsi</option>
            @foreach ($provinces as $province)
                <option value="{{ $province }}" @selected($selected('province') === $province)>{{ $province }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-city">Kota</label>
        <select class="filter-input" id="filter-city" name="city">
            <option value="">Semua kota</option>
            @foreach ($cities as $city)
                <option value="{{ $city }}" @selected($selected('city') === $city)>{{ $city }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label class="filter-label" for="filter-accreditation">Akreditasi</label>
        <select class="filter-input" id="filter-accreditation" name="accreditation">
            <option value="">Semua akreditasi</option>
            @foreach ($accreditations as $accreditation)
                <option value="{{ $accreditation }}" @selected($selected('accreditation') === $accreditation)>{{ $accreditation }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-actions">
        <button class="btn btn--primary" type="submit">Terapkan</button>
        <a class="btn btn--ghost" href="{{ $resetUrl }}">Reset</a>
    </div>
</div>
