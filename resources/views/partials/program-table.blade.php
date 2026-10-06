{{-- Props: $programs (koleksi StudyProgram dengan major dimuat) --}}
@php
    $programTypeOptions = $programs->pluck('program_type')->unique()->mapWithKeys(fn ($type) => [$type->value => $type->label()]);
    $degreeOptions = $programs->pluck('degree_level')->unique()->mapWithKeys(fn ($level) => [$level->value => $level->label()]);
@endphp
<div data-program-table>
    <div class="program-filters">
        <label class="program-filters__field">
            <span class="filter-label">Program</span>
            <select class="filter-input" data-program-filter="programType">
                <option value="">Semua program</option>
                @foreach ($programTypeOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="program-filters__field">
            <span class="filter-label">Jenjang</span>
            <select class="filter-input" data-program-filter="degreeLevel">
                <option value="">Semua jenjang</option>
                @foreach ($degreeOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Gelar</th>
                    <th scope="col">Program</th>
                    <th scope="col">Jadwal</th>
                    <th scope="col">Metode</th>
                    <th scope="col">Akreditasi</th>
                    <th scope="col">Herregistrasi</th>
                    <th scope="col">Pembayaran pertama</th>
                    <th scope="col">Angsuran per bulan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programs as $program)
                    <tr data-program-row data-program-type="{{ $program->program_type->value }}" data-degree-level="{{ $program->degree_level->value }}">
                        <th scope="row">
                            @if ($program->major)
                                <a href="{{ route('majors.show', $program->major) }}">{{ $program->major->name }}</a>
                            @else
                                -
                            @endif
                        </th>
                        <td>{{ $program->degree_title }} ({{ $program->degree_level->label() }})</td>
                        <td>{{ $program->program_type->label() }}</td>
                        <td>{{ $program->schedules->map(fn ($schedule) => $schedule->label())->implode(', ') }}</td>
                        <td>{{ $program->methods->map(fn ($method) => $method->label())->implode(', ') }}</td>
                        <td>{{ $program->accreditation }}</td>
                        <td>@include('partials.price', ['amount' => $program->registration_fee ?: null])</td>
                        <td>@include('partials.price', ['amount' => $program->first_payment ?: null])</td>
                        <td>@include('partials.price', ['amount' => $program->monthly_installment ?: null, 'original' => $program->original_monthly_installment])</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="program-table__empty" data-program-empty hidden>Tidak ada program studi yang cocok dengan filter ini.</p>
</div>
