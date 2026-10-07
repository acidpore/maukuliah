<?php

/*
 * Katalog tryout tahap demo: soal dibaca dari berkas ini dan hasil tidak
 * disimpan. Model dan bank soal basis data menyusul pada fase tryout.
 */
return [
    'status_open' => 'open',
    'status_upcoming' => 'upcoming',

    'tryouts' => [
        'penalaran-umum' => [
            'title' => 'Tryout Penalaran Umum',
            'description' => 'Latihan penalaran logis dan numerik untuk seleksi masuk perguruan tinggi.',
            'status' => 'open',
            'duration_minutes' => 10,
            'price' => 0,
            'questions' => [
                [
                    'text' => 'Semua dokter adalah lulusan kedokteran. Sebagian lulusan kedokteran bekerja di rumah sakit. Kesimpulan yang pasti benar adalah ...',
                    'options' => ['Semua dokter bekerja di rumah sakit', 'Sebagian dokter mungkin bekerja di rumah sakit', 'Tidak ada dokter yang bekerja di rumah sakit', 'Semua lulusan kedokteran adalah dokter'],
                    'answer' => 1,
                    'explanation' => 'Premis kedua hanya menyebut sebagian lulusan kedokteran, sehingga keterkaitan dokter dengan rumah sakit hanya mungkin, bukan pasti.',
                ],
                [
                    'text' => 'Deret 2, 6, 12, 20, 30, ... Angka berikutnya adalah ...',
                    'options' => ['36', '40', '42', '44'],
                    'answer' => 2,
                    'explanation' => 'Selisih antar suku naik 4, 6, 8, 10, jadi selisih berikutnya 12 dan suku berikutnya 42.',
                ],
                [
                    'text' => 'Harga barang naik 20 persen, lalu turun 20 persen. Dibanding harga awal, harga akhir ...',
                    'options' => ['Sama', 'Lebih tinggi 4 persen', 'Lebih rendah 4 persen', 'Lebih rendah 2 persen'],
                    'answer' => 2,
                    'explanation' => '1,2 dikali 0,8 sama dengan 0,96, yaitu 4 persen lebih rendah dari harga awal.',
                ],
                [
                    'text' => 'Rina lebih tinggi dari Sari. Sari lebih tinggi dari Tina. Pernyataan yang pasti benar adalah ...',
                    'options' => ['Tina lebih tinggi dari Rina', 'Rina lebih tinggi dari Tina', 'Sari paling tinggi', 'Tina sama tinggi dengan Sari'],
                    'answer' => 1,
                    'explanation' => 'Urutan tinggi adalah Rina, Sari, Tina, sehingga Rina lebih tinggi dari Tina.',
                ],
                [
                    'text' => 'Mesin A menyelesaikan satu pekerjaan dalam 6 jam, mesin B dalam 3 jam. Jika bekerja bersama, pekerjaan selesai dalam ...',
                    'options' => ['1 jam', '2 jam', '3 jam', '4,5 jam'],
                    'answer' => 1,
                    'explanation' => 'Laju gabungan 1/6 ditambah 1/3 sama dengan 1/2 pekerjaan per jam, sehingga selesai dalam 2 jam.',
                ],
            ],
        ],
        'matematika-dasar' => [
            'title' => 'Tryout Matematika Dasar',
            'description' => 'Latihan aljabar, perbandingan, dan peluang tingkat SMA.',
            'status' => 'open',
            'duration_minutes' => 8,
            'price' => 0,
            'questions' => [
                [
                    'text' => 'Nilai x yang memenuhi 3x + 5 = 20 adalah ...',
                    'options' => ['3', '4', '5', '6'],
                    'answer' => 2,
                    'explanation' => '3x sama dengan 15, jadi x sama dengan 5.',
                ],
                [
                    'text' => 'Perbandingan kelereng Andi dan Budi adalah 3 banding 5. Jika jumlahnya 40, kelereng Budi ada ...',
                    'options' => ['15', '20', '25', '30'],
                    'answer' => 2,
                    'explanation' => 'Satu bagian bernilai 40 dibagi 8 sama dengan 5, sehingga Budi memiliki 5 kali 5 sama dengan 25.',
                ],
                [
                    'text' => 'Peluang muncul angka genap pada pelemparan satu dadu adalah ...',
                    'options' => ['1/6', '1/3', '1/2', '2/3'],
                    'answer' => 2,
                    'explanation' => 'Ada 3 angka genap dari 6 sisi, jadi peluangnya 1/2.',
                ],
                [
                    'text' => 'Jika f(x) = 2x - 3, maka f(4) adalah ...',
                    'options' => ['3', '5', '8', '11'],
                    'answer' => 1,
                    'explanation' => 'f(4) sama dengan 2 kali 4 dikurangi 3, yaitu 5.',
                ],
            ],
        ],
        'bahasa-inggris' => [
            'title' => 'Tryout Bahasa Inggris',
            'description' => 'Latihan reading dan grammar untuk seleksi masuk dan beasiswa.',
            'status' => 'upcoming',
            'duration_minutes' => 10,
            'price' => 25000,
            'questions' => [],
        ],
    ],
];
