<?php

namespace App\Enums;

enum ClassSchedule: string
{
    case Pagi = 'pagi';
    case Sore = 'sore';
    case Malam = 'malam';
    case AkhirPekan = 'akhir-pekan';
    case Shift = 'shift';

    public function label(): string
    {
        return match ($this) {
            self::Pagi => 'Pagi',
            self::Sore => 'Sore',
            self::Malam => 'Malam',
            self::AkhirPekan => 'Akhir Pekan',
            self::Shift => 'Shift',
        };
    }
}
