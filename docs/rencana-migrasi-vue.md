# Rencana Migrasi Frontend ke Vue

Status: ditunda. Frontend tetap Blade untuk saat ini, tetapi ditulis agar
mudah dipindah ke Vue di kemudian hari.

## Keputusan

- Sementara: Blade dengan CSS dan JS statis di `public/`, tanpa build step.
- Rencana nanti: Inertia + Vue dengan SSR.

Alasan memilih Inertia, bukan SPA dengan API terpisah:

- Controller, service, model, seeder, dan test tidak berubah.
- Routing tetap di Laravel, tidak perlu `routes/api.php` dan auth token.
- SEO tetap aman lewat SSR. SEO penting untuk situs pencarian kampus.
- Tetap satu repository.

Alternatif yang ditolak: Vue SPA + JSON API. Butuh API Resource, Sanctum, dan
SSR terpisah (Nuxt) agar SEO setara. Pekerjaannya lebih besar tanpa manfaat
yang dibutuhkan saat ini.

## Konvensi agar Migrasi Murah

Berlaku untuk semua kode frontend baru.

1. Desain token (warna, spasi, radius, bayangan) disimpan sebagai CSS
   variables di `:root`. Nilainya dipakai ulang apa adanya di Vue.
2. UI dipecah menjadi partial kecil dengan satu tanggung jawab (card, badge,
   section head, filter bar, empty state). Data masuk lewat parameter
   `@include`, sama seperti props komponen Vue.
3. Blade tidak berisi logika bisnis. Hanya loop dan kondisi sederhana.
   Format tampilan memakai method model (contoh `salaryRange()`,
   `typeLabel()`).
4. JS vanilla dipisah per perilaku (navbar toggle, teks ketik, tab), tanpa
   inline script, berbasis atribut `data-*`.
5. Nama class memakai BEM secara konsisten.
6. Controller hanya menyiapkan data lalu mengirimnya ke view. Variabel view
   dianggap kontrak, karena nanti menjadi props Vue.

## Langkah Migrasi (saat dikerjakan)

1. Pasang Node, aktifkan Vite (`vite.config.js` sudah ada di repository).
2. Pasang `inertiajs/inertia-laravel` dan adapter Vue, siapkan SSR.
3. Ganti `return view(...)` di controller menjadi `Inertia::render(...)`
   dengan data yang sama.
4. Ubah tiap partial Blade menjadi komponen `.vue`, partial yang menerima
   parameter menjadi komponen dengan props.
5. Pindahkan CSS variables ke stylesheet utama Vite. Class BEM dipertahankan
   sehingga tampilan tidak berubah.
6. Pindahkan perilaku JS vanilla menjadi komponen atau composable.
7. Hapus view Blade yang sudah digantikan, jalankan test fitur untuk
   memastikan data tetap sama.

## Yang Tidak Perlu Diubah

- `app/Services/*`
- `app/Models/*`
- `database/*`
- Test unit dan test service
