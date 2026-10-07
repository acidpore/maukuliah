<?php

return [
    'per_page' => 9,

    'related_count' => 3,

    'latest_on_home' => 4,

    // Kecepatan baca rata-rata kata per menit untuk estimasi lama baca.
    'words_per_minute' => 200,

    'default_icon' => 'newspaper',

    /*
     * Kunci kategori dipakai di URL (?category=). Ikon memakai Phosphor.
     */
    'categories' => [
        'kampus' => ['label' => 'Kampus', 'icon' => 'buildings'],
        'jurusan' => ['label' => 'Jurusan', 'icon' => 'graduation-cap'],
        'karier' => ['label' => 'Karier', 'icon' => 'briefcase'],
        'beasiswa' => ['label' => 'Beasiswa', 'icon' => 'medal'],
        'tips-kuliah' => ['label' => 'Tips Kuliah', 'icon' => 'lightbulb'],
        'biaya-pendaftaran' => ['label' => 'Biaya dan Pendaftaran', 'icon' => 'receipt'],
    ],
];
