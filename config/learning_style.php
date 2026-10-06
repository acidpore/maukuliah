<?php

return [
    'scale' => [
        'min' => 1,
        'max' => 5,
    ],

    'styles' => [
        'visual' => [
            'label' => 'Visual',
            'tips' => 'Gunakan diagram, peta konsep, warna, dan video untuk merangkum materi.',
        ],
        'auditori' => [
            'label' => 'Auditori',
            'tips' => 'Belajar dengan berdiskusi, merekam penjelasan, dan membaca materi dengan suara keras.',
        ],
        'kinestetik' => [
            'label' => 'Kinestetik',
            'tips' => 'Praktikkan langsung, gunakan contoh nyata, dan bergerak atau membuat model saat belajar.',
        ],
        'baca_tulis' => [
            'label' => 'Baca-Tulis',
            'tips' => 'Buat catatan sendiri, ringkasan tertulis, daftar, dan latihan menulis ulang materi.',
        ],
    ],

    'questions' => [
        ['style' => 'visual', 'text' => 'Saya lebih mudah paham jika materi disajikan dengan gambar atau diagram.'],
        ['style' => 'visual', 'text' => 'Saya sering mencoret peta konsep atau sketsa saat belajar.'],
        ['style' => 'visual', 'text' => 'Saya mengingat tempat dan wajah lebih baik daripada nama.'],
        ['style' => 'visual', 'text' => 'Warna dan tata letak catatan membantu saya mengingat isi materi.'],
        ['style' => 'visual', 'text' => 'Saya lebih suka menonton video penjelasan daripada mendengar ceramah.'],

        ['style' => 'auditori', 'text' => 'Saya lebih mudah paham jika materi dijelaskan secara lisan.'],
        ['style' => 'auditori', 'text' => 'Saya suka berdiskusi dengan teman untuk memahami pelajaran.'],
        ['style' => 'auditori', 'text' => 'Saya sering membaca catatan dengan suara keras agar mudah diingat.'],
        ['style' => 'auditori', 'text' => 'Saya mengingat isi percakapan dengan baik.'],
        ['style' => 'auditori', 'text' => 'Musik atau suara latar membantu saya fokus saat belajar.'],

        ['style' => 'kinestetik', 'text' => 'Saya lebih mudah paham setelah mencoba atau mempraktikkan sendiri.'],
        ['style' => 'kinestetik', 'text' => 'Saya sulit duduk diam dalam waktu lama saat belajar.'],
        ['style' => 'kinestetik', 'text' => 'Saya suka belajar lewat percobaan, proyek, atau kunjungan lapangan.'],
        ['style' => 'kinestetik', 'text' => 'Saya sering menggerakkan tangan atau berjalan saat memikirkan sesuatu.'],
        ['style' => 'kinestetik', 'text' => 'Saya mengingat sesuatu dengan baik jika pernah melakukannya sendiri.'],

        ['style' => 'baca_tulis', 'text' => 'Saya lebih mudah paham dengan membaca buku atau modul tertulis.'],
        ['style' => 'baca_tulis', 'text' => 'Saya rajin menulis ringkasan dan catatan dengan kata-kata sendiri.'],
        ['style' => 'baca_tulis', 'text' => 'Saya suka membuat daftar untuk merencanakan dan mengingat sesuatu.'],
        ['style' => 'baca_tulis', 'text' => 'Saya lebih nyaman menjawab ujian dalam bentuk esai atau tulisan.'],
        ['style' => 'baca_tulis', 'text' => 'Saya sering mencari definisi dan membaca ulang materi sampai paham.'],
    ],
];
