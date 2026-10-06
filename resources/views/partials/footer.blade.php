<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="brand footer-brand" href="{{ route('home') }}">
                    <span class="brand__mark">{{ mb_substr(config('app.name'), 0, 1) }}</span>
                    <span>{{ config('app.name') }}</span>
                </a>
                <p class="footer-tagline">Cari kampus, jurusan, karier, dan beasiswa dalam satu tempat. Gratis untuk calon mahasiswa.</p>
            </div>
            <div>
                <h4 class="footer-title">Jelajahi</h4>
                <ul class="footer-links">
                    @foreach ($navItems as $item)
                        <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Layanan</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('applications.create') }}">Daftar Kuliah</a></li>
                    <li><a href="{{ route('tests.index') }}">Tes Potensi</a></li>
                    <li><a href="{{ route('campuses.by-schedule', ['schedule' => 'malam']) }}">Kuliah Malam</a></li>
                    <li><a href="{{ route('campuses.by-program', ['programType' => 'karyawan']) }}">Kelas Karyawan</a></li>
                    <li><a href="{{ route('affiliate.index') }}">Program Afiliasi</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.</span>
            <span>Data kampus dan jurusan masih berupa contoh.</span>
        </div>
    </div>
</footer>
