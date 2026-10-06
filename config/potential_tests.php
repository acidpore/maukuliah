<?php

use App\Models\TestResult;

return [
    /*
     * Label skala Likert yang dipakai tes berjawaban 1 sampai 5. Kunci mengikuti
     * rentang skala pada config tiap tes.
     */
    'likert_labels' => [
        1 => 'Sangat tidak setuju',
        2 => 'Tidak setuju',
        3 => 'Netral',
        4 => 'Setuju',
        5 => 'Sangat setuju',
    ],

    'history_per_page' => 10,

    'session_key' => 'pending_potential_test',

    'tests' => [
        'riasec' => [
            'type' => TestResult::TYPE_RIASEC,
            'title' => 'Tes Minat Bakat RIASEC',
            'description' => 'Kenali tipe minat kariermu lalu temukan jurusan yang paling sesuai.',
            'badge' => 'Populer',
            'duration_label' => '3 menit',
            'input' => 'likert',
        ],
        'learning-style' => [
            'type' => TestResult::TYPE_LEARNING_STYLE,
            'title' => 'Tes Gaya Belajar',
            'description' => 'Ketahui cara belajar yang paling efektif untukmu.',
            'badge' => 'Baru',
            'duration_label' => '3 menit',
            'input' => 'likert',
        ],
        'mbti' => [
            'type' => TestResult::TYPE_MBTI,
            'title' => 'Tes Kepribadian MBTI',
            'description' => 'Kenali 16 tipe kepribadian dan jurusan yang cocok untukmu.',
            'badge' => 'Baru',
            'duration_label' => '8 menit',
            'input' => 'choice',
        ],
    ],
];
