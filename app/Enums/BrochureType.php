<?php

namespace App\Enums;

enum BrochureType: string
{
    case Program = 'program';
    case Tuition = 'tuition';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Program => 'Brosur Program Studi',
            self::Tuition => 'Biaya Kuliah',
            self::General => 'Brosur Umum',
        };
    }
}
