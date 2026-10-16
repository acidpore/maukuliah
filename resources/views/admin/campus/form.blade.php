@extends('layouts.admin')

@section('title', $campus ? 'Ubah Kampus' : 'Tambah Kampus')

@php
    $typeOptions = collect(\App\Models\Campus::TYPES)->mapWithKeys(fn ($t) => [$t => \App\Models\Campus::TYPE_LABELS[$t]])->all();
    $formOptions = \App\Models\Campus::FORM_LABELS;
@endphp

@section('content')
    <div class="admin-page-head">
        <h1 class="admin-page-title">{{ $campus ? 'Ubah kampus' : 'Tambah kampus' }}</h1>
        @if ($campus)
            <p class="admin-page-subtitle">{{ $campus->name }}</p>
        @endif
    </div>

    @include('partials.flash')

    <div class="form-layout">
        <form class="form-card" method="POST" action="{{ $campus ? route('admin.campus.update', $campus) : route('admin.campus.store') }}">
            @csrf
            @if ($campus)
                @method('PUT')
            @endif

            <fieldset class="form-card__group">
                <legend class="form-card__legend">Data kampus</legend>

                @include('partials.form-field', ['name' => 'name', 'label' => 'Nama kampus', 'value' => $campus?->name])
                @include('partials.form-select', ['name' => 'type', 'label' => 'Jenis kampus', 'options' => $typeOptions, 'selected' => $campus?->type])
                @include('partials.form-select', ['name' => 'form', 'label' => 'Bentuk institusi', 'options' => $formOptions, 'selected' => $campus?->form])
                @include('partials.form-field', ['name' => 'city', 'label' => 'Kota', 'value' => $campus?->city])
                @include('partials.form-field', ['name' => 'province', 'label' => 'Provinsi', 'value' => $campus?->province])
                @include('partials.form-field', ['name' => 'accreditation', 'label' => 'Akreditasi', 'value' => $campus?->accreditation])
                @include('partials.form-field', ['name' => 'established_year', 'label' => 'Tahun berdiri', 'type' => 'number', 'required' => false, 'value' => $campus?->established_year])
                @include('partials.form-field', ['name' => 'website', 'label' => 'Situs web', 'type' => 'url', 'required' => false, 'value' => $campus?->website])
                @include('partials.form-field', ['name' => 'phone', 'label' => 'Telepon', 'required' => false, 'value' => $campus?->phone])
                @include('partials.form-field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => false, 'value' => $campus?->email])
                @include('partials.form-field', ['name' => 'address', 'label' => 'Alamat', 'required' => false, 'value' => $campus?->address])

                <div class="field {{ $errors->has('description') ? 'field--error' : '' }}">
                    <label class="field__label" for="field-description">Deskripsi<span class="field__required" aria-hidden="true">*</span></label>
                    <textarea class="field__control" id="field-description" name="description" rows="5" required>{{ old('description', $campus?->description) }}</textarea>
                    @error('description')
                        <p class="field__error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </fieldset>

            <button class="btn btn--primary" type="submit">{{ $campus ? 'Simpan perubahan' : 'Tambah kampus' }}</button>
            @if ($campus)
                <a class="btn btn--ghost" href="{{ route('admin.study-programs.index', ['campus' => $campus->id]) }}">Kelola program studi</a>
            @endif
            <a class="btn btn--ghost" href="{{ route('admin.campus.index') }}">Batal</a>
        </form>
    </div>
@endsection
