@extends('layouts.app')

@section('title', 'Dasbor Admin')

@php
    $statusLabels = ['new' => 'Baru', 'contacted' => 'Dihubungi', 'registered' => 'Mendaftar', 'accepted' => 'Diterima'];
    $sourceLabels = ['brochure' => 'Unduh brosur', 'favorite' => 'Favorit', 'application' => 'Pendaftaran'];
@endphp

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Dasbor admin</h1>
            <p class="page-subtitle">
                @if ($campus)
                    Calon mahasiswa untuk {{ $campus->name }}.
                @else
                    Seluruh calon mahasiswa dari semua kampus.
                @endif
            </p>
        </div>
    </div>

    <div class="container page-body">
        @include('partials.admin-nav', ['active' => 'dashboard'])
        @include('partials.flash')

        @if ($leads->isEmpty())
            @include('partials.empty-state', [
                'icon' => 'users-three',
                'title' => 'Belum ada calon mahasiswa',
                'text' => 'Peminat yang mengajukan pendaftaran, mengunduh brosur, atau memfavoritkan kampus akan muncul di sini.',
            ])
        @else
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
        @endif
    </div>
@endsection
