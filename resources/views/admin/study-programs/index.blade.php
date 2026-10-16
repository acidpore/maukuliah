@extends('layouts.admin')

@section('title', 'Program Studi')

@php
    $degreeOptions = collect(\App\Enums\DegreeLevel::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    $programTypeOptions = collect(\App\Enums\ProgramType::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()])->all();
    $majorOptions = $majors->pluck('name', 'id')->all();
@endphp

@section('content')
    <div class="admin-page-head">
        <h1 class="admin-page-title">Program studi</h1>
        <p class="admin-page-subtitle">Atur jurusan, harga, dan promo per program studi{{ $campus ? ' untuk '.$campus->name : '' }}.</p>
    </div>

    @include('partials.flash')

    @if ($campuses)
        <form class="form-inline" method="GET" action="{{ route('admin.study-programs.index') }}">
            <label class="field__label" for="field-campus-picker">Kampus</label>
            <select class="field__control" id="field-campus-picker" name="campus" data-auto-submit>
                <option value="">Pilih kampus</option>
                @foreach ($campuses as $option)
                    <option value="{{ $option->id }}" @selected($campus && $campus->id === $option->id)>{{ $option->name }}</option>
                @endforeach
            </select>
        </form>
    @endif

    @if (! $campus)
        @include('partials.empty-state', [
            'icon' => 'graduation-cap',
            'title' => 'Pilih kampus',
            'text' => 'Pilih kampus terverifikasi di atas untuk mengatur program studinya.',
        ])
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Jurusan</th>
                        <th scope="col">Jenjang</th>
                        <th scope="col">Program</th>
                        <th scope="col">Cicilan / bulan</th>
                        <th scope="col">Promo</th>
                        <th scope="col">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($studyPrograms as $program)
                        <tr>
                            <th scope="row">{{ $program->major->name }}</th>
                            <td>{{ $program->degree_level->label() }}</td>
                            <td>{{ $program->program_type->label() }}</td>
                            <td>Rp{{ number_format($program->monthly_installment, 0, ',', '.') }}</td>
                            <td>
                                @if ($program->hasDiscount())
                                    <s>Rp{{ number_format($program->original_monthly_installment, 0, ',', '.') }}</s>
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td>
                                <div class="inline-actions">
                                    <a class="btn btn--ghost btn--sm" href="{{ route('admin.study-programs.index', ['campus' => $campus->id, 'edit' => $program->id]) }}">Ubah</a>
                                    <form method="POST" action="{{ route('admin.study-programs.destroy', $program) }}" onsubmit="return confirm('Hapus program studi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn--ghost btn--sm" type="submit">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Belum ada program studi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @php
            $editing = request('edit') ? $studyPrograms->firstWhere('id', (int) request('edit')) : null;
        @endphp

        <div class="form-layout">
            <form class="form-card" method="POST" action="{{ $editing ? route('admin.study-programs.update', $editing) : route('admin.study-programs.store', $campus) }}">
                @csrf
                @if ($editing)
                    @method('PUT')
                @endif

                <fieldset class="form-card__group">
                    <legend class="form-card__legend">{{ $editing ? 'Ubah program studi' : 'Tambah program studi' }}</legend>

                    @include('partials.form-select', ['name' => 'major_id', 'label' => 'Jurusan', 'options' => $majorOptions, 'selected' => $editing?->major_id])
                    @include('partials.form-select', ['name' => 'degree_level', 'label' => 'Jenjang', 'options' => $degreeOptions, 'selected' => $editing?->degree_level?->value])
                    @include('partials.form-select', ['name' => 'program_type', 'label' => 'Jenis program', 'options' => $programTypeOptions, 'selected' => $editing?->program_type?->value])
                    @include('partials.form-field', ['name' => 'accreditation', 'label' => 'Akreditasi prodi', 'required' => false, 'value' => $editing?->accreditation])
                    @include('partials.form-field', ['name' => 'registration_fee', 'label' => 'Biaya pendaftaran (Rp)', 'type' => 'number', 'value' => $editing?->registration_fee])
                    @include('partials.form-field', ['name' => 'first_payment', 'label' => 'Pembayaran pertama (Rp)', 'type' => 'number', 'value' => $editing?->first_payment])
                    @include('partials.form-field', ['name' => 'monthly_installment', 'label' => 'Cicilan per bulan (Rp)', 'type' => 'number', 'value' => $editing?->monthly_installment])
                    @include('partials.form-field', ['name' => 'original_monthly_installment', 'label' => 'Harga sebelum promo (Rp)', 'required' => false, 'type' => 'number', 'hint' => 'Isi hanya jika sedang ada promo, harus lebih besar dari cicilan per bulan.', 'value' => $editing?->original_monthly_installment])

                    <div class="form-card__checks">
                        <p class="field__label">Jadwal kelas yang tersedia<span class="field__required" aria-hidden="true">*</span></p>
                        @foreach (\App\Enums\ClassSchedule::cases() as $schedule)
                            <label class="check">
                                <input type="checkbox" name="schedules[]" value="{{ $schedule->value }}" @checked(in_array($schedule->value, old('schedules', $editing?->schedules?->map(fn ($s) => $s->value)->all() ?? []), true))>
                                <span>{{ $schedule->label() }}</span>
                            </label>
                        @endforeach
                        @error('schedules')
                            <p class="field__error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-card__checks">
                        <p class="field__label">Metode belajar yang tersedia<span class="field__required" aria-hidden="true">*</span></p>
                        @foreach (\App\Enums\LearningMethod::cases() as $method)
                            <label class="check">
                                <input type="checkbox" name="methods[]" value="{{ $method->value }}" @checked(in_array($method->value, old('methods', $editing?->methods?->map(fn ($m) => $m->value)->all() ?? []), true))>
                                <span>{{ $method->label() }}</span>
                            </label>
                        @endforeach
                        @error('methods')
                            <p class="field__error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </fieldset>

                <button class="btn btn--primary" type="submit">{{ $editing ? 'Simpan perubahan' : 'Tambah program studi' }}</button>
                @if ($editing)
                    <a class="btn btn--ghost" href="{{ route('admin.study-programs.index', ['campus' => $campus->id]) }}">Batal</a>
                @endif
            </form>
        </div>
    @endif
@endsection
