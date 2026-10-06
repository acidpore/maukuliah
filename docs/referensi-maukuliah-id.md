# Referensi: maukuliah.id

Dokumen acuan hasil pengamatan situs https://maukuliah.id (dibaca pada 2026-10-06).
Dipakai sebagai pembanding dan sumber kebutuhan untuk proyek ini.

Batasan pengamatan:

- Data diambil dari isi halaman publik (teks dan HTML). Tampilan visual belum
  diperiksa lewat screenshot, jadi detail desain di bagian 5 terbatas pada
  warna, font, dan struktur yang terbaca dari kode.
- Halaman di balik login (favorit, riwayat, hasil tes, dashboard admin) tidak
  bisa dibuka. Isinya dicatat dari FAQ dan tautan saja.
- `/consultation` mengarah ke halaman login. `/riasec/guide` juga mengarah ke
  halaman login, jadi alur soal dan format hasil belum diketahui.

## 1. Gambaran Umum

| Item | Isi |
|---|---|
| Tujuan | Platform pencarian kampus dan jurusan untuk calon mahasiswa Indonesia |
| Klaim | 3.000+ rekomendasi kampus, lengkap dengan jurusan, biaya, prospek kerja |
| Tagline | "Persiapkan kuliah dengan mudah, raih masa depan cerah bersama Maukuliah.id" |
| Pemilik | PT Sentra Vidya Utama (footer: 2004-2026), produk Sevima |
| Model bisnis | B2C gratis untuk siswa, B2B CRM penerimaan mahasiswa baru untuk kampus (Sevima CRM) |
| Bahasa | Indonesia |

Dua sisi pengguna:

- Calon mahasiswa: cari kampus, tes minat, tryout, beasiswa, ajukan pendaftaran.
- Admin kampus: kelola profil, unggah brosur, lihat calon peminat.

## 2. Peta Situs

Navigasi utama (6 menu): Kampus, Jurusan, Karier, Beasiswa, Tryout, Tes Potensi.

| Rute | Fungsi | Akses |
|---|---|---|
| `/` | Beranda | Publik |
| `/universities` | Daftar kampus | Publik |
| `/universities/{slug}` | Detail kampus | Publik (unduh brosur dan daftar butuh login) |
| `/majors` | Daftar jurusan | Publik |
| `/majors/{slug}` | Detail jurusan | Publik |
| `/jobs` | Daftar karier | Publik |
| `/jobs/{slug}` | Detail karier | Publik |
| `/scholarships` | Daftar beasiswa | Publik |
| `/scholarships/{slug}` | Detail beasiswa | Publik |
| `/tryouts` | Daftar tryout | Publik (saat ini kosong) |
| `/tryouts/history` | Riwayat tryout | Login |
| `/test` | Hub tes potensi (3 tes) | Publik |
| `/riasec`, `/riasec/guide` | Tes RIASEC | Login |
| `/test/learning-style` | Tes gaya belajar | Login |
| `/test/mbti` | Tes MBTI | Login |
| `/test/history` | Riwayat tes potensi | Login |
| `/favourites` | Favorit | Login |
| `/regions` | Pilih provinsi, tautkan ke daftar kampus terfilter | Publik |
| `/consultation` | Konsultasi (belum jelas, tampil login) | Login |
| `/faq` | FAQ | Publik |
| `/register`, `/login` | Akun | Publik |
| Blog | `blog.maukuliah.id` (subdomain terpisah, tampaknya WordPress) | Publik |
| Hasil pencarian kota | `/universities?query={kota}` | Publik |

## 3. Fitur per Halaman

### 3.1 Beranda

Urutan bagian: hero (headline berefek ketik), rekomendasi kampus (3 kartu),
jurusan unggulan (8), karier (9), beasiswa, pencarian per kota (Jakarta,
Surabaya, Makassar, Bali, tautan "Lihat Kota Lainnya"), artikel blog (4),
profil kampus unggulan (12), promosi Sevima CRM, footer.

### 3.2 Kampus (`/universities`)

- Filter: lokasi (provinsi dan kota), akreditasi, jenis (Negeri/Swasta).
- Nilai akreditasi mencampur dua skema: A, B, C, Unggul, Baik, Baik Sekali,
  Belum Terakreditasi.
- Kartu: jenis, nama (tautan), deskripsi 1-3 kalimat, lokasi.
- Paginasi: 55 halaman.
- Pencarian teks lewat parameter `query`.

### 3.3 Detail Kampus

Urutan: breadcrumb, header (jenis, akreditasi, kota, tombol Unduh Brosur dan
Ajukan Pendaftaran), lima tab anchor.

1. Tentang Kampus: galeri foto, sejarah, misi, tautan Google Maps, kontak
   (situs, telepon, email, media sosial).
2. Program Studi: tab jenjang (S1, S2) dengan kartu prodi dan deskripsi.
3. Jalur Pendaftaran: daftar gelombang dengan tanggal buka dan tutup
   (reguler, karyawan, beasiswa prestasi, mandiri, nilai rapor), tombol
   "Tampilkan Lebih Banyak".
4. Testimoni: carousel alumni (nama, prodi, pekerjaan sekarang).
5. Artikel: berita kampus dengan tanggal.

Modal brosur: daftar PDF per prodi, biaya kuliah, dan brosur umum. Wajib
login (pengumpulan lead). Ukuran maksimum unggahan admin 2 MB.

### 3.4 Jurusan

- `/majors`: filter modal dengan 14 kategori (Bahasa dan Budaya, Ekonomi dan
  Bisnis, Geografi, Ilmu Kesehatan dan Olahraga, Ilmu Pendidikan dan Agama,
  Ilmu Sosial Hukum dan Politik, Kehutanan dan Peternakan, Kelautan,
  Matematika dan IPA, Pariwisata dan Perhotelan, Pertanian, Seni dan Musik,
  Teknik dan Industri, Teknologi dan Informatika). 16 halaman.
- Detail: tentang jurusan, prospek karier (tautan ke profil karier), ajakan
  tes potensi, rekomendasi kampus (8 kartu).

### 3.5 Karier

- `/jobs`: 37 halaman. Kartu berisi judul dan deskripsi. Tidak ada filter
  terlihat.
- Detail: judul, kisaran gaji (contoh "Rp4,5jt - Rp9jt"), tentang karier,
  jenjang posisi (contoh Associate, Consultant, Manager, Partner), jurusan
  rekomendasi, kampus rekomendasi (8), ajakan tes potensi.

### 3.6 Beasiswa

- Kartu: nama dan periode pendaftaran. Saat dibaca baru 2 data.
- Detail: judul, periode, tombol "Daftar Beasiswa", deskripsi, lembaga
  penyelenggara. Syarat dan manfaat tidak dirinci. Beasiswa terkait dan
  ajakan tryout di bagian bawah.
- Beasiswa terhubung ke kampus (contoh beasiswa milik STIKes Mitra Keluarga).

### 3.7 Tryout

Menampilkan keadaan kosong "Belum ada tryout yang dibuka". Disebut ada
tryout gratis dan berbayar. Detail soal, timer, dan pembahasan belum terlihat.

### 3.8 Tes Potensi

Tiga tes, semua gratis dan butuh akun:

| Tes | Label | Keterangan |
|---|---|---|
| RIASEC | Populer | Sekitar 3 menit, hasil berupa jurusan cocok |
| Gaya Belajar | Baru | Penilaian diri |
| MBTI | Baru | 16 tipe kepribadian |

Hasil berupa "Jurusan Rekomendasi" lengkap dengan prospek karier dan program
tersedia (dari FAQ).

### 3.9 Akun dan Pendaftaran

- Daftar hanya butuh email, gratis, bisa lewat ponsel.
- Login: email dan password, atau Google. Ada "Ingat Saya" dan lupa password
  (tautan reset lewat email).
- Email yang sudah terdaftar diarahkan ke login Google.
- Ajukan pendaftaran ke kampus: tombol "Ajukan Pendaftaran" di profil kampus,
  isi data, kampus menghubungi. Tidak ada batas jumlah kampus.

### 3.10 Sisi Admin Kampus

- Akun admin tidak bisa daftar mandiri. Pengajuan lewat WhatsApp tim
  (0811351022 menurut FAQ), gratis.
- Fitur dari FAQ: unggah dan hapus brosur (maks 2 MB), daftar peminat
  ("List Peminat"), reset password.
- Dashboard CRM tidak bisa diamati.

## 4. Sistem dan Teknologi (dari kode halaman)

| Aspek | Temuan |
|---|---|
| Backend | Laravel (rute bergaya Laravel, aset di `/build/assets/` hasil Vite) |
| Interaktivitas | Livewire (`/livewire/livewire.min.js`) |
| Aset | Vite untuk CSS dan JS utama, Swiper untuk carousel, typed.js untuk efek ketik |
| Font | Instrument Sans (Google Fonts) |
| SEO | Meta description, keywords, Open Graph (gambar 1200 px), server-side render |
| Filter | Parameter query string (`?query=`) sehingga bisa diindeks |
| Gerbang lead | Unduh brosur dan ajukan pendaftaran wajib login |
| CRM | Terintegrasi Sevima CRM, halaman login bertema CRM |

Paginasi: 55 halaman kampus, 16 halaman jurusan, 37 halaman karier.

## 5. Desain

| Aspek | Temuan |
|---|---|
| Warna utama | Biru `#074BB2` (paling dominan dalam HTML) |
| Netral | `#364152`, `#4B5565`, `#202939`, `#E3E8EF` |
| Aksen | Oranye `#F69D51` (jarang dipakai) |
| Font | Instrument Sans, bobot 400-700 |
| Komponen | Kartu kampus, kartu jurusan, kartu karier, carousel testimoni, modal filter, modal brosur |
| Tata letak | Navigasi atas 6 menu, hero dengan CTA, bagian berurutan penuh lebar, footer 3 kolom |
| Keadaan kosong | Pesan ramah dengan emoji (tidak ditiru, lihat aturan proyek) |
| Pola ajakan | Banner "Tes Potensi" berulang di beranda, detail jurusan, detail karier, detail kampus |

Pola desain yang konsisten: setiap halaman detail berakhir dengan ajakan tes
potensi dan rekomendasi silang (kampus ke jurusan ke karier).

## 6. Model Data yang Tersirat

- Kampus: nama, slug, jenis (negeri/swasta), akreditasi, kota, deskripsi,
  sejarah, misi, galeri, kontak, peta, brosur[], prodi[], jalur[],
  testimoni[], artikel[].
- Prodi (di dalam kampus): nama, jenjang (S1/S2), deskripsi.
- Jurusan: nama, slug, kategori (14), deskripsi, karier[], kampus[].
- Karier: nama, slug, deskripsi, kisaran gaji, jenjang posisi[], jurusan[],
  kampus[].
- Beasiswa: nama, slug, periode mulai dan selesai, penyelenggara, deskripsi,
  tautan daftar, kampus (opsional).
- Pengguna: email, kredensial atau Google, favorit, riwayat tes, riwayat
  tryout, pengajuan pendaftaran.
- Lead: pengguna ke kampus (pengajuan, unduh brosur), dilihat admin kampus.

## 7. Kelemahan yang Terlihat (peluang perbaikan)

- Skema akreditasi bercampur (huruf dan istilah) di satu filter.
- Deskripsi kampus panjang dan tidak seragam di kartu.
- Filter karier tidak terlihat. Beasiswa hanya 2 data. Tryout kosong.
- Banyak fitur inti (tes, brosur, daftar) terkunci di balik login.
- Detail beasiswa tidak memuat syarat dan manfaat.

## 8. Status Proyek Ini terhadap Referensi

Sumber: `docs/internal/progress.md` dan kode saat ini.

| Area | maukuliah.id | Proyek ini |
|---|---|---|
| Stack | Laravel, Livewire, Vite | Laravel 11, Blade, SQLite, CSS/JS statis |
| Beranda | Ada | Selesai |
| Kampus (daftar, filter, detail) | Ada | Selesai (18 data seed) |
| Jurusan (daftar, filter, detail) | Ada | Selesai (24 data seed, 10 kategori) |
| Pencarian global | Ada | Selesai (`/search`) |
| Karier | Ada | Belum (`/soon`) |
| Beasiswa | Ada | Belum (`/soon`) |
| Tryout | Ada (kosong) | Belum |
| Tes Potensi (RIASEC, Gaya Belajar, MBTI) | Ada | Belum |
| Akun, Google login | Ada | Belum |
| Favorit, riwayat | Ada | Belum |
| Brosur, ajukan pendaftaran | Ada | Belum |
| Admin dan CRM | Ada | Belum |
| Blog | Ada (subdomain) | Belum |
| Wilayah (`/regions`) | Ada | Belum (hanya chip kota di beranda) |
| Desain | Biru `#074BB2`, Instrument Sans | Indigo dan emerald, Plus Jakarta Sans |

Urutan lanjutan mengikuti roadmap di `docs/internal/progress.md`.

## 9. Hal yang Belum Diketahui

Perlu dicek manual setelah login: alur dan jumlah soal RIASEC, MBTI, Gaya
Belajar, format hasil, soal dan penilaian tryout, isi `/consultation`, tampilan
dashboard admin, dan tampilan visual lengkap (tata letak, jarak, ikon).
