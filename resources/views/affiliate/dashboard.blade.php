@extends('layouts.app')

@section('title', 'Dasbor Afiliasi')

@php
    $referralLabels = ['registered' => 'Terdaftar', 'paid' => 'Sudah bayar', 'rejected' => 'Ditolak'];
    $referralVariants = ['registered' => 'warning', 'paid' => 'open', 'rejected' => 'danger'];
    $commissionLabels = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'paid' => 'Dibayar'];
@endphp

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Dasbor afiliasi</h1>
            <p class="page-subtitle">Kode rujukanmu: <strong>{{ $affiliate->code }}</strong></p>
        </div>
    </div>

    <div class="container page-body">
        @include('partials.flash')

        <section class="content-panel">
            <h2 class="content-panel__title">Tautan rujukan</h2>
            <div class="copy-field">
                <input class="field__control" type="text" value="{{ $referralUrl }}" readonly aria-label="Tautan rujukan" data-copy-source>
                <button class="btn btn--primary" type="button" data-copy-button>Salin</button>
            </div>
        </section>

        <div class="stat-cards">
            <div class="stat-card">
                <span class="stat-card__label">Total rujukan</span>
                <strong class="stat-card__value">{{ $stats['total'] }}</strong>
            </div>
            <div class="stat-card">
                <span class="stat-card__label">Sudah bayar</span>
                <strong class="stat-card__value">{{ $stats['paid'] }}</strong>
            </div>
            <div class="stat-card">
                <span class="stat-card__label">Komisi tertunda</span>
                <strong class="stat-card__value">Rp{{ number_format($stats['pending_commission'], 0, ',', '.') }}</strong>
            </div>
            <div class="stat-card">
                <span class="stat-card__label">Komisi dibayar</span>
                <strong class="stat-card__value">Rp{{ number_format($stats['paid_commission'], 0, ',', '.') }}</strong>
            </div>
        </div>

        <section class="content-panel">
            <h2 class="content-panel__title">Daftar rujukan</h2>
            @if ($referrals->isEmpty())
                @include('partials.empty-state', [
                    'icon' => 'users-three',
                    'title' => 'Belum ada rujukan',
                    'text' => 'Bagikan tautan rujukanmu untuk mulai mengumpulkan komisi.',
                ])
            @else
                <div class="table-wrap">
                    <table class="table table--compact">
                        <thead>
                            <tr>
                                <th scope="col">Mahasiswa</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Status</th>
                                <th scope="col">Komisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($referrals as $referral)
                                <tr>
                                    <th scope="row">{{ $referral->application?->full_name ?? '-' }}</th>
                                    <td>{{ $referral->created_at->translatedFormat('d M Y') }}</td>
                                    <td>@include('partials.badge', ['label' => $referralLabels[$referral->status->value] ?? $referral->status->value, 'variant' => $referralVariants[$referral->status->value] ?? 'cat'])</td>
                                    <td>
                                        @if ($referral->commission)
                                            Rp{{ number_format($referral->commission->amount, 0, ',', '.') }}
                                            <small class="muted">({{ $commissionLabels[$referral->commission->status->value] ?? $referral->commission->status->value }})</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $referrals->links('pagination.custom') }}
            @endif
        </section>
    </div>
@endsection
