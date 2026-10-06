<?php

namespace App\Enums;

enum LastEducation: string
{
    case Sma = 'sma';
    case PaketC = 'paket-c';
    case D3 = 'd3';
    case S1 = 's1';
    case S2 = 's2';
    case S3 = 's3';

    public function label(): string
    {
        return match ($this) {
            self::Sma => 'SMA/SMK',
            self::PaketC => 'SMA Paket C',
            self::D3 => 'D3',
            self::S1 => 'S1',
            self::S2 => 'S2',
            self::S3 => 'S3',
        };
    }
}
