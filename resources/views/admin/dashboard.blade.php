@extends('layouts.admin')

@section('title', 'Dasbor')

@php
    $statusLabels = ['new' => 'Baru', 'contacted' => 'Dihubungi', 'registered' => 'Mendaftar', 'accepted' => 'Diterima'];
    $sourceLabels = ['brochure' => 'Unduh brosur', 'favorite' => 'Favorit', 'application' => 'Pendaftaran'];
@endphp

@section('content')
    <div class="admin-page-head">
        <h1 class="admin-page-title">Dasbor</h1>
        <p class="admin-page-subtitle">
            @if ($campus)
                Calon mahasiswa untuk {{ $campus->name }}.
            @else
                Seluruh calon mahasiswa dari semua kampus.
            @endif
        </p>
    </div>

    @include('partials.flash')

    <section aria-label="Ringkasan">
        <div class="stat-cards">
            <div class="stat-card">
                <span class="stat-card__label">Total calon mahasiswa</span>
                <strong class="stat-card__value">{{ $stats['leads_total'] }}</strong>
            </div>
            <div class="stat-card">
                <span class="stat-card__label">Pendaftaran baru</span>
                <strong class="stat-card__value">{{ $stats['recent_applications'] }}</strong>
                <span class="stat-card__hint">{{ $stats['recent_days'] }} hari terakhir</span>
            </div>
            <div class="stat-card">
                <span class="stat-card__label">Kampus terverifikasi</span>
                <strong class="stat-card__value">{{ $stats['verified_campuses'] }}</strong>
            </div>
            @if ($stats['pending_campuses'] !== null)
                <a class="stat-card" href="{{ route('admin.campuses.index') }}">
                    <span class="stat-card__label">Menunggu verifikasi</span>
                    <strong class="stat-card__value">{{ $stats['pending_campuses'] }}</strong>
                    <span class="stat-card__hint">Periksa pengajuan</span>
                </a>
            @endif
        </div>
    </section>

    <section aria-label="Calon mahasiswa per status">
        <div class="stat-cards">
            @foreach ($stats['leads_by_status'] as $item)
                <div class="stat-card">
                    <span class="stat-card__label">{{ $item['label'] }}</span>
                    <strong class="stat-card__value">{{ $item['value'] }}</strong>
                </div>
            @endforeach
        </div>
    </section>

    @if ($leads->isEmpty())
        @include('partials.empty-state', [
            'icon' => 'users-three',
            'title' => 'Belum ada calon mahasiswa',
            'text' => 'Peminat yang mengajukan pendaftaran, mengunduh brosur, atau memfavoritkan kampus akan muncul di sini.',
        ])
    @else
        <section aria-label="Daftar calon mahasiswa">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Calon mahasiswa</th>
                            <th scope="col">Kontak</th>
                            <th scope="col">Kampus</th>
                            <th scope="col">Sumber</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leads as $lead)
                            <tr>
                                <th scope="row">{{ $lead->application?->full_name ?? $lead->user?->name ?? '-' }}</th>
                                <td>
                                    {{ $lead->application?->whatsapp ?? $lead->user?->phone ?? '-' }}<br>
                                    <small class="muted">{{ $lead->application?->email ?? $lead->user?->email }}</small>
                                </td>
                                <td>{{ $lead->campus->name }}</td>
                                <td>{{ $sourceLabels[$lead->source->value] ?? $lead->source->value }}</td>
                                <td>{{ $lead->created_at->translatedFormat('d M Y') }}</td>
                                <td>
                                    <form class="inline-form" method="POST" action="{{ route('admin.leads.status', $lead) }}">
                                        @csrf
                                        <label class="visually-hidden" for="status-{{ $lead->id }}">Status {{ $lead->application?->full_name ?? $lead->user?->name }}</label>
                                        <select class="filter-input" id="status-{{ $lead->id }}" name="status" data-auto-submit>
                                            @foreach ($statusOptions as $option)
                                                <option value="{{ $option->value }}" @selected($lead->status === $option)>{{ $statusLabels[$option->value] ?? $option->value }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $leads->links('pagination.custom') }}
        </section>
    @endif
@endsection
