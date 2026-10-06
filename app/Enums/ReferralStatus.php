<?php

namespace App\Enums;

enum ReferralStatus: string
{
    case Registered = 'registered';
    case Paid = 'paid';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Registered => 'Terdaftar',
            self::Paid => 'Sudah bayar',
            self::Rejected => 'Ditolak',
        };
    }
}
