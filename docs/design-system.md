# Design System

Acuan tampilan frontend. Semua nilai di bawah berasal dari `public/css/app.css`.
Tampilan ditulis dengan Blade dan CSS statis, disiapkan agar mudah dipindah ke
Vue (Inertia): satu partial Blade sama dengan satu komponen Vue.

## Prinsip

- Satu keluarga biru, satu skala netral abu-biru, satu aksen oranye, dan warna
  status. Tidak ada warna di luar token.
- Semua warna, spasi, radius, dan bayangan adalah CSS variable di `:root`.
- Tanpa emoji dan simbol dekoratif unicode. Ikon memakai Phosphor Icons
  (`<i class="ph ph-nama">`), selalu dengan `aria-hidden="true"`.
- Tema tunggal terang. Gradien hanya turunan biru yang sama.
- Mobile-first. Breakpoint 640, 900, 1024, dan 1200 px.

## Token

### Warna

| Token | Nilai | Kegunaan |
|---|---|---|
| `--blue-600` | `#074bb2` | Warna utama, tombol, tautan |
| `--blue-800` | `#053a8a` | Hover tombol utama |
| `--blue-950` | `#04265c` | Latar gelap (footer, banner) |
| `--blue-500` | `#1f63c9` | Cincin fokus |
| `--blue-200` | `#b9d1f4` | Garis lembut, hover kartu |
| `--blue-100` | `#dce8fa` | Latar tint kuat |
| `--blue-50` | `#eef4fd` | Latar tint, badge |
| `--ink` | `#202939` | Judul |
| `--body` | `#364152` | Teks isi |
| `--muted` | `#4b5565` | Teks sekunder |
| `--line` | `#e3e8ef` | Garis dan border |
| `--surface` | `#ffffff` | Kartu |
| `--page` | `#f6f8fc` | Latar halaman |
| `--pattern-dots`, `--pattern-dots-light` | pola titik | Lapisan latar geometris halus |
| `--accent-warm` | `#f69d51` | Aksen oranye, hanya garis bawah kata sorotan hero |
| `--success-600` / `--success-50` | `#14744a` / `#e6f4ec` | Status sukses (beasiswa dibuka) |
| `--warning-600` / `--warning-50` | `#8a5a00` / `#fdf1dc` | Status peringatan |
| `--danger-600` / `--danger-50` | `#b42318` / `#fdeceb` | Status bahaya |

Kontras teks utama terhadap latar memenuhi WCAG AA (isi `#364152` pada putih
sekitar 10:1, teks sekunder `#4b5565` sekitar 7.6:1, putih pada `#074bb2` sekitar 8.6:1).

### Tipografi

- Font: Instrument Sans (Google Fonts), bobot 400, 500, 600, 700.
- Judul memakai `letter-spacing: -0.02em` sampai `-0.03em` dan `line-height` 1.08 sampai 1.15.
- Isi 16 px dengan `line-height: 1.6`. Teks panjang dibatasi `max-width` 52 sampai 68 karakter.
- Judul hero memakai `clamp()` agar muat dua baris di desktop.

### Spasi

`--space-1` 4px, `--space-2` 8px, `--space-3` 12px, `--space-4` 16px,
`--space-5` 24px, `--space-6` 32px, `--space-7` 48px, `--space-8` 64px.

### Radius dan bayangan

- `--radius-control` 12px untuk input dan kontrol.
- `--radius-card` 18px untuk kartu dan panel.
- `--radius-pill` 999px untuk tombol, chip, dan badge.
- `--shadow-card` dan `--shadow-lift` memakai warna biru gelap, bukan hitam murni.

Aturan bentuk: kontrol 12, kartu 18, tombol dan chip pil. Tidak dicampur.

## Struktur CSS

Urutan di `app.css`: token, base, tombol dan badge, header, hero, bagian halaman
(section, bento, rail), kartu, daftar baris, chip, banner, kepala halaman dan
tata letak daftar, paginasi, empty state, breadcrumb, detail, footer, gerakan,
lalu breakpoint mobile-first.

Komponen fitur fase 3 ada di `public/css/features.css` (dimuat setelah
`app.css`): harga, formulir, notice, auth card, accordion, tabel, ubin tautan,
menu pengguna, subnav admin, kartu statistik, tes potensi (kartu tes, soal,
opsi, bilah kemajuan, skor), tombol favorit. Berkas dipisah agar halaman inti
tetap ringkas dan tiap blok mudah dipindah menjadi komponen Vue. Kolom grid
halaman diberi `min-width: 0` supaya tabel lebar menggulir di dalam
`table-wrap`, bukan melebarkan halaman.

## Konvensi BEM

Format `blok__elemen--modifier`. Satu blok berarti satu partial. Contoh:
`campus-card`, `campus-card__name`, `badge--negeri`. Status dipasang lewat class
`is-active`, `is-open`, `is-visible`. Perilaku JS tidak memakai class gaya,
melainkan atribut `data-*`.

## Partial (calon komponen Vue)

Semua di `resources/views/partials/`. Props dikirim lewat `@include('partials.nama', [...])`.

| Partial | Props | Keterangan |
|---|---|---|
| `badge` | `label`, `variant?` | Varian: `cat`, `negeri`, `swasta`, `kedinasan`, `accred`, `outline`, `open`, `closed`, `warning`, `danger` |
| `section-head` | `title`, `subtitle?`, `eyebrow?`, `linkLabel?`, `linkUrl?` | Judul bagian dengan tautan opsional |
| `empty-state` | `title`, `text`, `icon?`, `actionLabel?`, `actionUrl?` | Keadaan kosong |
| `filter-bar` | `action`, `query`, `placeholder`, `hidden?` | Kotak cari, `hidden` berisi pasangan nama dan nilai |
| `breadcrumb` | `items` | Array `['label' => ..., 'url' => ...|null]` |
| `campus-card` | `campus` | Perlu `majors_count` |
| `major-card` | `major` | Perlu `campuses_count` |
| `career-row` | `career` | Memakai `salaryRange()` |
| `scholarship-card` | `scholarship` | Memakai `isOpen()`, tanggal, relasi `campus` opsional |
| `article-card` | `article` | Kartu artikel: cover biru berpola dengan ikon kategori, badge, judul, ringkasan, tanggal dan lama baca |
| `price` | `amount`, `original?`, `prefix?`, `suffix?` | Harga rupiah, harga coret bila `original` lebih besar |
| `form-field` | `name`, `label`, `type?`, `value?`, `required?`, `hint?`, `autocomplete?` | Input dengan label, pesan error dari `$errors` |
| `form-select` | `name`, `label`, `options`, `selected?`, `placeholder?`, `required?` | `options` berupa pasangan nilai dan teks |
| `accordion` | `items`, `title?` | Memakai `details` bawaan peramban, item berisi `question` dan `answer` |
| `region-accordion` | `regions`, `regionRoute?`, `regionParams?`, `title?` | Daftar wilayah, tautan ke halaman SEO atau daftar kampus |
| `campus-filters` | opsi dari `CampusSearchService::indexData`, `resetUrl` | Seluruh field filter kampus |
| `program-table` | `programs` | Tabel program studi dengan filter klien |
| `link-tiles` | `tiles`, `modifier?` | Ubin tautan (`icon`, `title`, `text?`, `url`) |
| `favorite-button` | `type`, `model` | Tombol simpan atau lepas favorit, tamu diarahkan ke login |
| `flash` | - | Pesan status sesi dan error umum |
| `auth-actions` | - | Tombol masuk dan daftar, atau menu pengguna |
| `layouts.admin` | `@yield(title, content)` | Layout admin terpisah dari situs publik: sidebar kiri tetap (desktop) atau drawer (di bawah 900 px), topbar dengan nama pengguna dan tombol keluar. Gaya di `public/css/admin.css`, perilaku di `public/js/admin-drawer.js` |
| `navbar`, `footer` | `navItems` | Dibangun di `layouts/app` dengan rute opsional yang jatuh ke `soon` |

Aturan Blade: hanya perulangan dan kondisi sederhana. Pemformatan data
dilakukan di method model (`typeLabel()`, `initials()`, `salaryRange()`).

## JavaScript

Satu file satu perilaku di `public/js`, tanpa skrip inline, dipicu atribut data.

| File | Atribut | Fungsi |
|---|---|---|
| `navbar-toggle.js` | `data-nav-toggle`, `data-nav` | Buka tutup menu mobile |
| `typed-text.js` | `data-typed`, `data-typed-label` | Efek ketik hero |
| `tabs-spy.js` | `data-tabs`, `data-tab-link` | Sorot tab sesuai bagian terlihat |
| `auto-submit.js` | `data-auto-submit` | Kirim form saat nilai berubah |
| `reveal.js` | `data-reveal` | Munculkan bagian saat masuk layar |
| `program-filter.js` | `data-program-table`, `data-program-filter`, `data-program-row`, `data-program-empty` | Saring baris program studi |
| `test-progress.js` | `data-test-form`, `data-test-progress`, `data-test-answered` | Kemajuan pengerjaan tes |
| `copy-field.js` | `data-copy-source`, `data-copy-button` | Salin tautan rujukan |
| `admin-drawer.js` | `data-admin`, `data-admin-toggle`, `data-admin-backdrop` | Buka tutup menu samping admin di layar kecil |

## Aksesibilitas

- Tautan lewati ke konten (`.skip-link`) dan landmark `main`, `nav`, `header`, `footer`.
- Cincin fokus terlihat 3 px pada semua elemen interaktif.
- Setiap input punya label atau `aria-label`, bukan placeholder saja.
- Ikon dekoratif memakai `aria-hidden="true"`, gambar memakai `alt` bermakna.
- Gerakan (efek ketik, reveal, hover naik) dimatikan atau dibuat statis saat
  `prefers-reduced-motion: reduce`.
- Target sentuh tombol dan kontrol minimal 42 sampai 44 px.

## Latar dan ilustrasi tanpa foto

Tidak ada gambar eksternal. Visual dibangun dari token:

- `--pattern-dots` (titik putih tipis, untuk latar biru gelap) dan
  `--pattern-dots-light` (titik biru tipis, untuk latar tint). Dipakai sebagai
  lapisan `background: var(--pattern-dots), var(--blue-800)`.
- `.detail-hero`: latar biru solid dengan pola titik dan satu cincin transparan.
  Dipakai di halaman detail kampus, jurusan, karier, dan beasiswa.
- `.hero-panel` (beranda): komposisi kartu mock memakai data nyata. Berisi
  `campus-card` kampus pertama, badge, dan dua tile statistik, di atas latar tint berpola.
- `.bento__cell--pattern`: sel bento biru dengan pola titik.
- `.partner__panel`: panel biru berpola dengan ikon besar, untuk bagian kampus (B2B).

Gambar yang aslinya perlu foto (galeri kampus, testimoni) belum ada. Bila
ditambahkan nanti, simpan di `public/images` dan sertakan `alt` bermakna.

## Format data di Blade

Tanggal dan label tampil lewat method model, bukan format di view. Contoh:
`$scholarship->periodLabel()`, `$career->salaryRange()`, `$campus->typeLabel()`.
