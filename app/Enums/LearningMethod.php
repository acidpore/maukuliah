<?php

namespace App\Enums;

enum LearningMethod: string
{
    case Blended = 'blended';
    case TatapMuka = 'tatap-muka';
    case Hybrid = 'hybrid';
    case FullOnline = 'full-online';

    public function label(): string
    {
        return match ($this) {
            self::Blended => 'Blended Learning',
            self::TatapMuka => 'Tatap Muka',
            self::Hybrid => 'Hybrid Learning',
            self::FullOnline => 'Full Online',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Blended => 'Menggabungkan tatap muka di kelas dengan pembelajaran daring.',
            self::TatapMuka => 'Seluruh perkuliahan dilakukan langsung di kampus.',
            self::Hybrid => 'Mahasiswa dapat memilih hadir di kelas atau mengikuti secara daring.',
            self::FullOnline => 'Seluruh perkuliahan dilakukan secara daring.',
        };
    }
}
