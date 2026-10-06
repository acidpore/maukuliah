{{-- Menampilkan pesan status sesi dan error umum (kunci "status"). Tanpa props. --}}
@if (session('status'))
    <p class="notice notice--success" role="status">{{ session('status') }}</p>
@endif
@error('status')
    <p class="notice notice--danger" role="alert">{{ $message }}</p>
@enderror
