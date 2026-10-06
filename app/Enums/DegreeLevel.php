<?php

namespace App\Enums;

enum DegreeLevel: string
{
    case D1 = 'd1';
    case D3 = 'd3';
    case S1 = 's1';
    case S2 = 's2';
    case S3 = 's3';

    public function label(): string
    {
        return strtoupper($this->value);
    }
}
