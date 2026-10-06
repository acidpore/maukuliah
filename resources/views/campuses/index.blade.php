@extends('layouts.app')

@section('title', 'Daftar Kampus')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Daftar Kampus</h1>
            <p class="page-subtitle">Temukan kampus yang tepat berdasarkan lokasi, jenis, bentuk, dan akreditasi.</p>
            <p class="page-count">{{ $campuses->total() }} kampus ditemukan</p>
        </div>
    </div>

    <div class="container">
        <form action="{{ route('campuses.index') }}" method="GET">
            <div class="list-layout">
                <aside class="sidebar">
                    <div class="filter-form">
                        <h2 class="filter-form__title">Filter</h2>

                        <div class="filter-group">
                            <label class="filter-label" for="filter-type">Jenis</label>
                            <select class="filter-input" id="filter-type" name="type">
                                <option value="">Semua Jenis</option>
                                @foreach ($typeOptions as $value => $label)
                                    <option value="{{ $value }}" {{ ($filters['type'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label" for="filter-form">Bentuk</label>
                            <select class="filter-input" id="filter-form" name="form">
                                <option value="">Semua Bentuk</option>
                                @foreach ($forms as $form)
                                    <option value="{{ $form }}" {{ ($filters['form'] ?? '') === $form ? 'selected' : '' }}>{{ $formLabels[$form] ?? ucfirst($form) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label" for="filter-province">Provinsi</label>
                            <select class="filter-input" id="filter-province" name="province">
                                <option value="">Semua Provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province }}" {{ ($filters['province'] ?? '') === $province ? 'selected' : '' }}>{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label" for="filter-city">Kota</label>
                            <select class="filter-input" id="filter-city" name="city">
                                <option value="">Semua Kota</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city }}" {{ ($filters['city'] ?? '') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label" for="filter-accreditation">Akreditasi</label>
                            <select class="filter-input" id="filter-accreditation" name="accreditation">
                                <option value="">Semua Akreditasi</option>
                                @foreach ($accreditations as $accreditation)
                                    <option value="{{ $accreditation }}" {{ ($filters['accreditation'] ?? '') === $accreditation ? 'selected' : '' }}>{{ $accreditation }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button class="btn btn--primary" type="submit">Terapkan Filter</button>
                            <a class="btn btn--ghost" href="{{ route('campuses.index') }}">Reset</a>
                        </div>
                    </div>
                </aside>

                <div>
                    <div class="toolbar">
                        <div class="toolbar__search">
                            <input class="toolbar__search-input" type="search" name="q" value="{{ $query }}" placeholder="Cari nama kampus, kota, atau jenis..." aria-label="Cari kampus">
                            <button class="btn btn--primary btn--sm" type="submit">Cari</button>
                        </div>
                        <select class="toolbar__sort" name="sort" data-auto-submit aria-label="Urutkan kampus">
                            <option value="">Urutkan: Nama (A-Z)</option>
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                        </select>
                    </div>

                    @if ($campuses->isEmpty())
                        <div class="empty">
                            <h2 class="empty__title">Tidak ada kampus yang cocok</h2>
                            <p class="empty__text">Coba ubah kata kunci atau filter pencarianmu.</p>
                            <a class="btn btn--primary" href="{{ route('campuses.index') }}">Reset Pencarian</a>
                        </div>
                    @else
                        <div class="grid grid--campuses">
                            @foreach ($campuses as $campus)
                                @include('partials.campus-card', ['campus' => $campus])
                            @endforeach
                        </div>
                        {{ $campuses->links('pagination.custom') }}
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
