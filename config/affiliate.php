<?php

return [
    /*
     * Nilai awal komisi per mahasiswa yang menyelesaikan pendaftaran dan
     * pembayaran. Porsi final ditetapkan bersama PM, jadi dibaca dari env
     * dan tidak boleh ditanam di kode.
     */
    'commission_per_student' => (int) env('AFFILIATE_COMMISSION', 200000),

    // Mahasiswa harus membayar dalam jangka ini agar rujukan dihitung.
    'payment_window_days' => (int) env('AFFILIATE_PAYMENT_WINDOW_DAYS', 60),

    'referral_query_key' => 'ref',

    'cookie' => [
        'name' => 'affiliate_ref',
        'lifetime_minutes' => (int) env('AFFILIATE_COOKIE_MINUTES', 60 * 24 * 30),
    ],

    'code_length' => 8,
];
