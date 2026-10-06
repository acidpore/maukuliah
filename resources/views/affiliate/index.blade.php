@extends('layouts.app')

@section('title', 'Program Afiliasi')
@section('description', 'Bagikan tautan rujukan dan dapatkan komisi untuk setiap mahasiswa yang mendaftar dan menyelesaikan pembayaran.')

@php
    $categoryLabels = [
        'umum' => 'Umum',
        'mahasiswa' => 'Mahasiswa atau alumni',
        'dosen' => 'Dosen',
        'staf-kampus' => 'Staf kampus',
    ];
@endphp

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Program afiliasi</h1>
            <p class="page-subtitle">Ajak teman, keluarga, atau rekan kuliah lewat tautan rujukanmu. Setiap mahasiswa yang mendaftar dan menyelesaikan pembayaran menghasilkan komisi.</p>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Cara kerjanya</h2>
                    <ol class="steps">
                        <li>Daftar sebagai afiliator dan dapatkan kode rujukan pribadimu.</li>
                        <li>Bagikan tautan rujukan ke calon mahasiswa.</li>
                        <li>Calon mahasiswa mendaftar kuliah lewat tautanmu.</li>
                        <li>Setelah pembayaran selesai, komisi tercatat di dasbormu.</li>
                    </ol>
                </section>

                <section class="content-panel">
                    <h2 class="content-panel__title">Ketentuan</h2>
                    <ul class="check-list">
                        <li><i class="ph ph-check-circle" aria-hidden="true"></i> Komisi per mahasiswa: @include('partials.price', ['amount' => (int) $commissionPerStudent])</li>
                        <li><i class="ph ph-check-circle" aria-hidden="true"></i> Pembayaran mahasiswa harus selesai dalam {{ $paymentWindowDays }} hari sejak mendaftar.</li>
                        <li><i class="ph ph-check-circle" aria-hidden="true"></i> Rujukan ke diri sendiri tidak dihitung.</li>
                        <li><i class="ph ph-check-circle" aria-hidden="true"></i> Kecurangan atau manipulasi data membatalkan komisi.</li>
                    </ul>
                </section>
            </div>

            <aside>
                <div class="sidebar-card">
                    @if ($affiliate)
                        <h2 class="sidebar-card__title">Kamu sudah terdaftar</h2>
                        <p class="sidebar-card__text">Lihat tautan rujukan, jumlah rujukan, dan komisi di dasbor afiliasimu.</p>
                        <a class="btn btn--primary" href="{{ route('affiliate.dashboard') }}">Buka dasbor</a>
                    @else
                        <h2 class="sidebar-card__title">Daftar sebagai afiliator</h2>
                        @auth
                            <form class="auth-card__form" method="POST" action="{{ route('affiliate.register') }}" novalidate>
                                @csrf
                                @include('partials.form-select', [
                                    'name' => 'category',
                                    'label' => 'Kategori',
                                    'options' => collect($categories)->mapWithKeys(fn ($case) => [$case->value => $categoryLabels[$case->value] ?? $case->value])->all(),
                                ])
                                <button class="btn btn--primary btn--block" type="submit">Daftar afiliasi</button>
                            </form>
                        @else
                            <p class="sidebar-card__text">Masuk atau buat akun terlebih dahulu untuk mendaftar sebagai afiliator.</p>
                            <a class="btn btn--primary" href="{{ route('login') }}">Masuk</a>
                            <a class="btn btn--ghost" href="{{ route('register') }}">Buat akun</a>
                        @endauth
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection
