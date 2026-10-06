<?php

namespace App\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Registered = 'registered';
    case Accepted = 'accepted';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Contacted => 'Dihubungi',
            self::Registered => 'Mendaftar',
            self::Accepted => 'Diterima',
        };
    }
}
