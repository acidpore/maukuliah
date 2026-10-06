<?php

/*
 * Pilihan A selalu mewakili huruf pertama dimensi, pilihan B huruf kedua.
 * Skor seri jatuh ke huruf pada 'tie_breakers' agar hasil deterministik.
 */
return [
    'dimensions' => ['EI', 'SN', 'TF', 'JP'],

    'tie_breakers' => [
        'EI' => 'I',
        'SN' => 'N',
        'TF' => 'F',
        'JP' => 'P',
    ],

    'recommendation_limit' => 5,

    'questions' => [
        ['dimension' => 'EI', 'text' => 'Setelah hari yang panjang, saya lebih suka:', 'a' => 'Berkumpul dengan banyak orang', 'b' => 'Menyendiri untuk memulihkan tenaga'],
        ['dimension' => 'EI', 'text' => 'Dalam pertemuan baru, saya biasanya:', 'a' => 'Memulai percakapan lebih dulu', 'b' => 'Menunggu orang lain menyapa'],
        ['dimension' => 'EI', 'text' => 'Saya berpikir paling baik dengan cara:', 'a' => 'Mengobrol dan bertukar gagasan', 'b' => 'Merenung sendirian'],
        ['dimension' => 'EI', 'text' => 'Teman-teman saya menganggap saya:', 'a' => 'Mudah bergaul dan ekspresif', 'b' => 'Tenang dan tertutup'],
        ['dimension' => 'EI', 'text' => 'Saat belajar, saya lebih suka:', 'a' => 'Belajar kelompok', 'b' => 'Belajar sendiri'],
        ['dimension' => 'EI', 'text' => 'Saya lebih nyaman dengan:', 'a' => 'Lingkaran pergaulan yang luas', 'b' => 'Beberapa teman dekat'],
        ['dimension' => 'EI', 'text' => 'Saat ada masalah, saya cenderung:', 'a' => 'Langsung membicarakannya', 'b' => 'Memikirkannya dulu sendiri'],
        ['dimension' => 'EI', 'text' => 'Di acara ramai, saya:', 'a' => 'Merasa bersemangat', 'b' => 'Cepat merasa lelah'],
        ['dimension' => 'EI', 'text' => 'Saya lebih suka pekerjaan yang:', 'a' => 'Banyak berinteraksi', 'b' => 'Memberi ruang untuk fokus sendiri'],
        ['dimension' => 'EI', 'text' => 'Saya biasanya:', 'a' => 'Berbicara dulu baru berpikir', 'b' => 'Berpikir dulu baru berbicara'],

        ['dimension' => 'SN', 'text' => 'Saya lebih percaya pada:', 'a' => 'Fakta dan pengalaman nyata', 'b' => 'Firasat dan kemungkinan'],
        ['dimension' => 'SN', 'text' => 'Saya lebih tertarik pada:', 'a' => 'Apa yang ada sekarang', 'b' => 'Apa yang mungkin terjadi nanti'],
        ['dimension' => 'SN', 'text' => 'Saat belajar hal baru, saya suka:', 'a' => 'Langkah rinci yang jelas', 'b' => 'Gambaran besar dan konsepnya'],
        ['dimension' => 'SN', 'text' => 'Saya menilai diri saya:', 'a' => 'Praktis dan realistis', 'b' => 'Imajinatif dan penuh ide'],
        ['dimension' => 'SN', 'text' => 'Saya lebih suka petunjuk yang:', 'a' => 'Spesifik dan tepat', 'b' => 'Umum dan memberi kebebasan'],
        ['dimension' => 'SN', 'text' => 'Saya lebih memperhatikan:', 'a' => 'Detail di sekitar saya', 'b' => 'Pola dan makna di baliknya'],
        ['dimension' => 'SN', 'text' => 'Saya lebih menyukai:', 'a' => 'Cara yang sudah terbukti', 'b' => 'Cara baru yang belum dicoba'],
        ['dimension' => 'SN', 'text' => 'Saat bercerita, saya:', 'a' => 'Menjelaskan kejadian apa adanya', 'b' => 'Menambahkan kiasan dan gagasan'],
        ['dimension' => 'SN', 'text' => 'Saya lebih nyaman dengan tugas yang:', 'a' => 'Konkret dan terukur', 'b' => 'Terbuka dan eksploratif'],
        ['dimension' => 'SN', 'text' => 'Saya lebih menghargai:', 'a' => 'Pengalaman', 'b' => 'Inspirasi'],

        ['dimension' => 'TF', 'text' => 'Saat memutuskan sesuatu, saya mengutamakan:', 'a' => 'Logika dan analisis', 'b' => 'Perasaan orang yang terlibat'],
        ['dimension' => 'TF', 'text' => 'Saya lebih menghargai:', 'a' => 'Keadilan', 'b' => 'Belas kasih'],
        ['dimension' => 'TF', 'text' => 'Saat memberi kritik, saya cenderung:', 'a' => 'Terus terang dan objektif', 'b' => 'Berhati-hati agar tidak menyakiti'],
        ['dimension' => 'TF', 'text' => 'Dalam perdebatan, saya lebih fokus pada:', 'a' => 'Siapa yang argumennya benar', 'b' => 'Menjaga suasana tetap harmonis'],
        ['dimension' => 'TF', 'text' => 'Saya lebih mudah dianggap:', 'a' => 'Tegas dan rasional', 'b' => 'Hangat dan peduli'],
        ['dimension' => 'TF', 'text' => 'Untuk menilai sebuah pilihan, saya:', 'a' => 'Menimbang untung dan rugi', 'b' => 'Menimbang dampak ke orang lain'],
        ['dimension' => 'TF', 'text' => 'Saya lebih terganggu oleh:', 'a' => 'Ketidaklogisan', 'b' => 'Ketidakpedulian'],
        ['dimension' => 'TF', 'text' => 'Saya lebih mudah mengambil keputusan dengan:', 'a' => 'Kepala dingin', 'b' => 'Hati yang peka'],
        ['dimension' => 'TF', 'text' => 'Pemimpin yang baik menurut saya:', 'a' => 'Berpikir jernih dan konsisten', 'b' => 'Memahami kebutuhan anggotanya'],
        ['dimension' => 'TF', 'text' => 'Saya lebih bangga jika dianggap:', 'a' => 'Kompeten', 'b' => 'Baik hati'],

        ['dimension' => 'JP', 'text' => 'Saya lebih suka hidup yang:', 'a' => 'Teratur dan terencana', 'b' => 'Fleksibel dan spontan'],
        ['dimension' => 'JP', 'text' => 'Menghadapi tenggat, saya:', 'a' => 'Menyelesaikan jauh hari', 'b' => 'Bekerja kencang mendekati batas'],
        ['dimension' => 'JP', 'text' => 'Saat bepergian, saya:', 'a' => 'Menyusun jadwal rinci', 'b' => 'Mengikuti suasana hati'],
        ['dimension' => 'JP', 'text' => 'Meja atau ruang kerja saya biasanya:', 'a' => 'Rapi dan tertata', 'b' => 'Berantakan tetapi saya tahu letaknya'],
        ['dimension' => 'JP', 'text' => 'Saya merasa nyaman jika:', 'a' => 'Keputusan sudah ditetapkan', 'b' => 'Pilihan masih terbuka'],
        ['dimension' => 'JP', 'text' => 'Terhadap rencana, saya:', 'a' => 'Berpegang pada rencana', 'b' => 'Mudah mengubah rencana'],
        ['dimension' => 'JP', 'text' => 'Saya lebih suka mengerjakan tugas:', 'a' => 'Satu per satu sampai selesai', 'b' => 'Beberapa sekaligus sesuai suasana'],
        ['dimension' => 'JP', 'text' => 'Saya lebih menyukai:', 'a' => 'Kepastian', 'b' => 'Kejutan'],
        ['dimension' => 'JP', 'text' => 'Daftar tugas bagi saya:', 'a' => 'Penting dan saya centang satu per satu', 'b' => 'Hanya panduan longgar'],
        ['dimension' => 'JP', 'text' => 'Saat liburan, saya lebih suka:', 'a' => 'Agenda yang jelas', 'b' => 'Santai tanpa agenda'],
    ],

    'types' => [
        'ISTJ' => ['name' => 'Logistik', 'summary' => 'Teliti, bertanggung jawab, dan menyukai keteraturan.', 'categories' => ['Ekonomi & Bisnis', 'Hukum', 'Teknik & Teknologi']],
        'ISFJ' => ['name' => 'Pembela', 'summary' => 'Setia, perhatian, dan senang membantu orang lain secara praktis.', 'categories' => ['Kesehatan', 'Pendidikan', 'Sosial & Humaniora']],
        'INFJ' => ['name' => 'Advokat', 'summary' => 'Idealis, empatik, dan berpandangan jauh.', 'categories' => ['Sosial & Humaniora', 'Pendidikan', 'Kesehatan']],
        'INTJ' => ['name' => 'Arsitek', 'summary' => 'Strategis, mandiri, dan suka menyusun sistem yang efisien.', 'categories' => ['Komputer & Informatika', 'Sains & Matematika', 'Teknik & Teknologi']],
        'ISTP' => ['name' => 'Perajin', 'summary' => 'Tenang, praktis, dan pandai memecahkan masalah teknis.', 'categories' => ['Teknik & Teknologi', 'Komputer & Informatika', 'Pertanian']],
        'ISFP' => ['name' => 'Petualang', 'summary' => 'Peka, fleksibel, dan menikmati ekspresi kreatif.', 'categories' => ['Seni & Desain', 'Kesehatan', 'Pertanian']],
        'INFP' => ['name' => 'Mediator', 'summary' => 'Idealis, reflektif, dan berpegang pada nilai pribadi.', 'categories' => ['Seni & Desain', 'Sosial & Humaniora', 'Pendidikan']],
        'INTP' => ['name' => 'Logikawan', 'summary' => 'Analitis, ingin tahu, dan menyukai teori.', 'categories' => ['Sains & Matematika', 'Komputer & Informatika', 'Teknik & Teknologi']],
        'ESTP' => ['name' => 'Pengusaha', 'summary' => 'Energik, berani mengambil peluang, dan cepat bertindak.', 'categories' => ['Ekonomi & Bisnis', 'Teknik & Teknologi', 'Sosial & Humaniora']],
        'ESFP' => ['name' => 'Penghibur', 'summary' => 'Spontan, ramah, dan senang berada di tengah orang.', 'categories' => ['Seni & Desain', 'Sosial & Humaniora', 'Kesehatan']],
        'ENFP' => ['name' => 'Juru Kampanye', 'summary' => 'Antusias, kreatif, dan mudah menginspirasi orang lain.', 'categories' => ['Sosial & Humaniora', 'Seni & Desain', 'Pendidikan']],
        'ENTP' => ['name' => 'Pendebat', 'summary' => 'Cerdik, suka menantang gagasan, dan penuh inovasi.', 'categories' => ['Ekonomi & Bisnis', 'Komputer & Informatika', 'Hukum']],
        'ESTJ' => ['name' => 'Direktur', 'summary' => 'Tegas, terorganisir, dan pandai memimpin pelaksanaan.', 'categories' => ['Ekonomi & Bisnis', 'Hukum', 'Teknik & Teknologi']],
        'ESFJ' => ['name' => 'Konsul', 'summary' => 'Ramah, kooperatif, dan peduli pada kebutuhan kelompok.', 'categories' => ['Kesehatan', 'Pendidikan', 'Ekonomi & Bisnis']],
        'ENFJ' => ['name' => 'Protagonis', 'summary' => 'Karismatik, peduli, dan pandai membimbing orang lain.', 'categories' => ['Pendidikan', 'Sosial & Humaniora', 'Hukum']],
        'ENTJ' => ['name' => 'Komandan', 'summary' => 'Berani memimpin, terarah pada tujuan, dan efisien.', 'categories' => ['Ekonomi & Bisnis', 'Hukum', 'Komputer & Informatika']],
    ],
];
