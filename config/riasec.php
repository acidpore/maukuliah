<?php

return [
    'scale' => [
        'min' => 1,
        'max' => 5,
    ],

    'code_length' => 3,

    'recommendation_limit' => 5,

    'types' => [
        'R' => 'Realistic',
        'I' => 'Investigative',
        'A' => 'Artistic',
        'S' => 'Social',
        'E' => 'Enterprising',
        'C' => 'Conventional',
    ],

    'questions' => [
        ['type' => 'R', 'text' => 'Saya suka memperbaiki atau merakit alat dan mesin.'],
        ['type' => 'R', 'text' => 'Saya nyaman bekerja di lapangan atau di luar ruangan.'],
        ['type' => 'R', 'text' => 'Saya suka menggunakan peralatan tangan atau teknis.'],
        ['type' => 'R', 'text' => 'Saya lebih suka pekerjaan yang hasilnya nyata dan bisa dipegang.'],

        ['type' => 'I', 'text' => 'Saya suka memecahkan masalah yang membutuhkan analisis mendalam.'],
        ['type' => 'I', 'text' => 'Saya penasaran bagaimana sesuatu bekerja secara ilmiah.'],
        ['type' => 'I', 'text' => 'Saya senang melakukan penelitian atau eksperimen.'],
        ['type' => 'I', 'text' => 'Saya suka mempelajari data dan mencari polanya.'],

        ['type' => 'A', 'text' => 'Saya suka menggambar, mendesain, atau berkarya seni.'],
        ['type' => 'A', 'text' => 'Saya senang mengekspresikan ide dengan cara yang orisinal.'],
        ['type' => 'A', 'text' => 'Saya suka menulis cerita, musik, atau konten kreatif.'],
        ['type' => 'A', 'text' => 'Saya lebih suka pekerjaan yang bebas dan tidak terlalu terikat aturan.'],

        ['type' => 'S', 'text' => 'Saya senang membantu orang lain menyelesaikan masalahnya.'],
        ['type' => 'S', 'text' => 'Saya suka mengajar atau menjelaskan sesuatu kepada orang lain.'],
        ['type' => 'S', 'text' => 'Saya mudah bekerja sama dalam kelompok.'],
        ['type' => 'S', 'text' => 'Saya peduli pada kesehatan dan kesejahteraan orang di sekitar saya.'],

        ['type' => 'E', 'text' => 'Saya suka memimpin dan mengarahkan sebuah tim.'],
        ['type' => 'E', 'text' => 'Saya tertarik memulai usaha atau proyek sendiri.'],
        ['type' => 'E', 'text' => 'Saya percaya diri meyakinkan orang lain.'],
        ['type' => 'E', 'text' => 'Saya suka mengambil risiko demi mencapai target.'],

        ['type' => 'C', 'text' => 'Saya suka mengatur data, berkas, atau jadwal dengan rapi.'],
        ['type' => 'C', 'text' => 'Saya teliti dan nyaman mengikuti prosedur yang jelas.'],
        ['type' => 'C', 'text' => 'Saya senang bekerja dengan angka dan laporan.'],
        ['type' => 'C', 'text' => 'Saya lebih suka pekerjaan yang terstruktur dan terukur.'],
    ],
];
