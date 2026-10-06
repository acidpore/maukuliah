@extends('layouts.app')

@section('title', 'Verifikasi Kampus')

@section('content')
    <div class="page-head">
        <div class="page-head__inner">
            <h1 class="page-title">Verifikasi kampus</h1>
            <p class="page-subtitle">Periksa data kampus yang diajukan. Hanya kampus terverifikasi yang tampil ke publik.</p>
        </div>
    </div>

    <div class="container page-body">
        @include('partials.admin-nav', ['active' => 'campuses'])
        @include('partials.flash')

        @if ($campuses->isEmpty())
            @include('partials.empty-state', [
                'icon' => 'seal-check',
                'title' => 'Tidak ada pengajuan',
                'text' => 'Semua kampus yang diajukan sudah diperiksa.',
            ])
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Kampus</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Akreditasi</th>
                            <th scope="col">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($campuses as $campus)
                            <tr>
                                <th scope="row">{{ $campus->name }}</th>
                                <td>{{ $campus->city }}, {{ $campus->province }}</td>
                                <td>{{ $campus->accreditation }}</td>
                                <td>
                                    <div class="inline-actions">
                                        <form method="POST" action="{{ route('admin.campuses.approve', $campus) }}">
                                            @csrf
                                            <button class="btn btn--primary btn--sm" type="submit">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.campuses.reject', $campus) }}">
                                            @csrf
                                            <button class="btn btn--ghost btn--sm" type="submit">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $campuses->links('pagination.custom') }}
        @endif
    </div>
@endsection
