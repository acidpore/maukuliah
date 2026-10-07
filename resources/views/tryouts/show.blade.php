@extends('layouts.app')

@section('title', $tryout['title'])
@section('description', $tryout['description'])

@section('content')
    <div class="container">
        @include('partials.breadcrumb', ['items' => [
            ['label' => 'Beranda', 'url' => route('home')],
            ['label' => 'Tryout', 'url' => route('tryouts.index')],
            ['label' => $tryout['title'], 'url' => null],
        ]])
    </div>

    <div class="detail-hero">
        <div class="container detail-hero__main">
            <div>
                <p class="eyebrow eyebrow--light">Tryout</p>
                <h1 class="detail-hero__title">{{ $tryout['title'] }}</h1>
                <p class="detail-hero__where">{{ $tryout['description'] }}</p>
                <div class="detail-hero__actions">
                    @if ($tryout['is_open'])
                        <a class="btn btn--light" href="{{ route('tryouts.take', $tryout['slug']) }}">Mulai mengerjakan</a>
                    @else
                        <span class="btn btn--outline-light" aria-disabled="true">Belum dibuka</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="detail-layout">
            <div>
                <section class="content-panel">
                    <h2 class="content-panel__title">Aturan pengerjaan</h2>
                    <ul class="prose-list">
                        <li>Waktu pengerjaan {{ $tryout['duration_minutes'] }} menit. Jawaban terkirim otomatis saat waktu habis.</li>
                        <li>Setiap soal punya satu jawaban benar. Soal yang kosong dihitung salah.</li>
                        <li>Skor dan pembahasan tampil setelah jawaban dikirim.</li>
                    </ul>
                </section>
            </div>

            <aside>
                <div class="sidebar-card">
                    <h2 class="sidebar-card__title">Ringkasan</h2>
                    <dl class="fact-list">
                        <div><dt>Soal</dt><dd>{{ $tryout['question_count'] > 0 ? $tryout['question_count'] : 'Menyusul' }}</dd></div>
                        <div><dt>Durasi</dt><dd>{{ $tryout['duration_minutes'] }} menit</dd></div>
                        <div><dt>Biaya</dt><dd>{{ $tryout['price'] === 0 ? 'Gratis' : 'Rp'.number_format($tryout['price'], 0, ',', '.') }}</dd></div>
                    </dl>
                </div>
            </aside>
        </div>
    </div>
@endsection
