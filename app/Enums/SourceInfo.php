<?php

namespace App\Enums;

enum SourceInfo: string
{
    case Website = 'website';
    case SocialMedia = 'social-media';
    case Brochure = 'brochure';
    case Friend = 'friend';
    case Advertisement = 'advertisement';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Situs web',
            self::SocialMedia => 'Media sosial',
            self::Brochure => 'Brosur',
            self::Friend => 'Teman atau keluarga',
            self::Advertisement => 'Iklan',
            self::Other => 'Lainnya',
        };
    }
}
