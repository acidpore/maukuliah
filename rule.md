# Aturan Pengembangan (Project Rules)

Aturan ini bersifat mengikat untuk seluruh pengembangan di repository ini.
Apabila terjadi perbedaan, urutan prioritas dari yang tertinggi adalah nomor 1 ke bawah.

## 1. Instruksi Owner Bersifat Mutlak

- Setiap instruksi, keputusan, dan arahan dari Owner adalah prioritas tertinggi.
- Instruksi Owner tidak boleh diabaikan, ditawar, atau diganti dengan asumsi sendiri.
- Apabila aturan di bawah bertentangan dengan instruksi Owner, instruksi Owner yang menang.

## 2. Clean Code

- Kode harus mudah dibaca, dipahami, dan dipelihara.
- Gunakan penamaan variabel, fungsi, kelas, dan file yang bermakna serta konsisten.
- Setiap fungsi atau method harus kecil dan menjalankan satu tanggung jawab (Single Responsibility).
- Hindari duplikasi; pindahkan logika berulang ke helper atau service yang jelas.
- Tulis komentar hanya untuk menjelaskan alasan ("mengapa"), bukan menjelaskan ulang kode ("apa").
- Hindari magic number dan nilai hard-coded; gunakan konstanta atau konfigurasi yang bermakna.
- Ikuti standar gaya penulisan PHP (PSR-12) dan konvensi Laravel.

## 3. Arsitektur Rapi dan Bersih

- Pisahkan tanggung jawab antar lapisan (presentation, application, domain, infrastructure).
- Terapkan pola MVC Laravel dengan benar:
  - Controller tipis, hanya mengatur request dan response.
  - Logika bisnis diletakkan di Service atau Repository.
  - Model berisi relasi dan akses data.
  - View (Blade) tidak boleh berisi logika bisnis yang rumit.
- Routing diorganisir secara konsisten dan diberi nama yang jelas.
- Migrasi, seeder, factory, dan test dipisah dengan jelas.
- Tidak boleh ada god-class, god-controller, atau file yang terlalu besar tanpa alasan.

## 4. Tanpa Emoji

- Dilarang menggunakan emoji di dalam:
  - Kode sumber.
  - Komentar.
  - String di aplikasi.
  - Nama file, kelas, fungsi, dan variabel.
  - Pesan commit.
  - Dokumentasi teknis di dalam repository.
- Gunakan teks biasa untuk menyampaikan makna.

## Referensi

- Standar PHP: PSR-12
- Dokumentasi Laravel: https://laravel.com/docs
