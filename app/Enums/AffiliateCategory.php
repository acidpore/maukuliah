<?php

namespace App\Enums;

enum AffiliateCategory: string
{
    case Umum = 'umum';
    case Mahasiswa = 'mahasiswa';
    case Dosen = 'dosen';
    case StafKampus = 'staf-kampus';

    public function label(): string
    {
        return match ($this) {
            self::Umum => 'Umum',
            self::Mahasiswa => 'Mahasiswa',
            self::Dosen => 'Dosen',
            self::StafKampus => 'Staf Kampus',
        };
    }
}
