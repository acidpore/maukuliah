<?php

namespace App\Enums;

enum LeadSource: string
{
    case Brochure = 'brochure';
    case Favorite = 'favorite';
    case Application = 'application';

    public function label(): string
    {
        return match ($this) {
            self::Brochure => 'Unduh brosur',
            self::Favorite => 'Favorit',
            self::Application => 'Pendaftaran',
        };
    }
}
