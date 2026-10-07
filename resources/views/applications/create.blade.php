@extends('layouts.app')

@php
    $enumOptions = fn ($cases) => collect($cases)->mapWithKeys(fn ($case) => [$case->value => $case->label()])->all();
    $campusOptions = $campuses->mapWithKeys(fn ($campus) => [$campus->id => $campus->name.' ('.$campus->city.')'])->all();
    $majorOptions = $majors->pluck('name', 'id')->all();
    $selectedCampus = $selectedCampusId ?? null;
@endphp

@section('title', 'Daftar Kuliah')
@section('description', 'Isi formulir singkat untuk mendaftar kuliah. Tim kami akan menghubungimu lewat WhatsApp, tanpa perlu membuat akun.')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Daftar kuliah</h1>
            <p class="page-subtitle">Isi data dirimu dan pilihan kuliahmu. Tim kami akan menghubungimu lewat WhatsApp untuk langkah berikutnya.</p>
        </div>
    </div>

    <div class="container">
        <div class="form-layout">
            <form class="form-card" method="POST" action="{{ route('applications.store') }}" data-campus-majors="{{ json_encode($campusMajors) }}" novalidate>
                @csrf

                @if (session('status'))
                    <p class="notice notice--success" role="status">{{ session('status') }}</p>
                @endif

                @if ($errors->any())
                    <p class="notice notice--danger" role="alert">Beberapa isian belum benar. Periksa kolom yang ditandai.</p>
                @endif

                <fieldset class="form-card__group">
                    <legend class="form-card__legend">Data diri</legend>
                    @include('partials.form-field', ['name' => 'full_name', 'label' => 'Nama lengkap', 'autocomplete' => 'name', 'value' => auth()->user()?->name])
                    @include('partials.form-field', ['name' => 'email', 'label' => 'Email aktif', 'type' => 'email', 'autocomplete' => 'email', 'value' => auth()->user()?->email])
                    @include('partials.form-field', ['name' => 'whatsapp', 'label' => 'Nomor WhatsApp', 'type' => 'tel', 'hint' => 'Contoh: 081234567890', 'autocomplete' => 'tel', 'value' => auth()->user()?->phone])
                    @include('partials.form-select', ['name' => 'last_education', 'label' => 'Pendidikan terakhir', 'options' => $enumOptions($options['last_education'])])
                    @include('partials.form-field', ['name' => 'region', 'label' => 'Wilayah (kota atau provinsi)'])
                </fieldset>

                <fieldset class="form-card__group">
                    <legend class="form-card__legend">Pilihan kuliah</legend>
                    @include('partials.form-select', ['name' => 'campus_id', 'label' => 'Kampus', 'options' => $campusOptions, 'selected' => old('campus_id', $selectedCampus)])
                    @include('partials.form-select', ['name' => 'major_id', 'label' => 'Jurusan', 'options' => $majorOptions])
                    @include('partials.form-select', ['name' => 'program_type', 'label' => 'Program kuliah', 'options' => $enumOptions($options['program_type'])])
                    @include('partials.form-select', ['name' => 'schedule', 'label' => 'Jadwal kuliah', 'options' => $enumOptions($options['schedule'])])
                    @include('partials.form-select', ['name' => 'source_info', 'label' => 'Tahu dari mana tentang kami', 'options' => $enumOptions($options['source_info'])])
                </fieldset>

                <div class="form-card__checks">
                    <label class="check {{ $errors->has('accepted_terms') ? 'check--error' : '' }}">
                        <input type="checkbox" name="accepted_terms" value="1" @checked(old('accepted_terms'))>
                        <span>Saya menyetujui syarat dan ketentuan yang berlaku.</span>
                    </label>
                    @error('accepted_terms')
                        <p class="field__error" role="alert">{{ $message }}</p>
                    @enderror
                    <label class="check">
                        <input type="checkbox" name="create_account" value="1" @checked(old('create_account'))>
                        <span>Buatkan akun dari data di atas agar saya bisa memantau pendaftaran.</span>
                    </label>
                </div>

                <button class="btn btn--primary btn--lg btn--block" type="submit">Kirim pendaftaran</button>
            </form>

            <aside class="form-aside">
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Yang terjadi setelah kamu mendaftar</h2>
                    <ol class="steps">
                        <li>Data kamu kami teruskan ke kampus pilihanmu.</li>
                        <li>Tim kami menghubungimu lewat WhatsApp dalam waktu singkat.</li>
                        <li>Kamu mendapat rincian biaya, jadwal, dan jalur pendaftaran.</li>
                    </ol>
                    <p class="sidebar-card__text">Pendaftaran gratis dan tidak mengikat. Kamu bisa mendaftar ke beberapa kampus.</p>
                </div>
            </aside>
        </div>
    </div>
@endsection
