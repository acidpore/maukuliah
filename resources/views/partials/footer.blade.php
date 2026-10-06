<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="footer-brand" href="{{ route('home') }}">
                    <span class="brand__mark">K</span>
                    <span>{{ config('app.name') }}</span>
                </a>
                <p class="footer-tagline">Temukan kampus dan jurusan yang tepat untuk masa depanmu. Informasi perkuliahan yang lengkap dan mudah diakses.</p>
            </div>
            <div>
                <h4 class="footer-title">Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('campuses.index') }}">Kampus</a></li>
                    <li><a href="{{ route('majors.index') }}">Jurusan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Fitur</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('soon') }}">Karier</a></li>
                    <li><a href="{{ route('soon') }}">Beasiswa</a></li>
                    <li><a href="{{ route('soon') }}">Tryout</a></li>
                    <li><a href="{{ route('soon') }}">Tes Potensi</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}. Seluruh hak cipta dilindungi.</span>
            <span class="footer-note">Prototipe MVP - data sebatas contoh</span>
        </div>
    </div>
</footer>
