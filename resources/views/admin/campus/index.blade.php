@extends('layouts.admin')

@section('title', 'Kampus')

@section('content')
    <div class="admin-page-head">
        <h1 class="admin-page-title">Kampus</h1>
        <p class="admin-page-subtitle">Kelola data kampus. Klik nama kampus untuk mengatur program studinya.</p>
    </div>

    @include('partials.flash')

    <div class="inline-actions" style="margin-bottom: 1rem;">
        <a class="btn btn--primary btn--sm" href="{{ route('admin.campus.create') }}">Tambah kampus</a>
    </div>

    @if ($campuses->isEmpty())
        @include('partials.empty-state', [
            'icon' => 'bank',
            'title' => 'Belum ada kampus',
            'text' => 'Tambahkan kampus pertama untuk mulai mengatur program studi.',
        ])
    @else
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Kampus</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campuses as $campus)
                        <tr>
                            <th scope="row">
                                <a href="{{ route('admin.study-programs.index', ['campus' => $campus->id]) }}">{{ $campus->name }}</a>
                            </th>
                            <td>{{ $campus->city }}, {{ $campus->province }}</td>
                            <td>{{ $campus->verification_status->label() }}</td>
                            <td>
                                <div class="inline-actions">
                                    <a class="btn btn--ghost btn--sm" href="{{ route('admin.study-programs.index', ['campus' => $campus->id]) }}">Program studi</a>
                                    <a class="btn btn--ghost btn--sm" href="{{ route('admin.campus.edit', $campus) }}">Ubah</a>
                                    <form method="POST" action="{{ route('admin.campus.destroy', $campus) }}" onsubmit="return confirm('Hapus kampus ini? Semua data terkait (pendaftaran, brosur, foto, testimoni, program studi) ikut terhapus permanen.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn--ghost btn--sm" type="submit">Hapus</button>
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
@endsection
