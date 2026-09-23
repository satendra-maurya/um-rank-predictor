<?php

namespace App\Enums;

enum ConsentStatus: int
{
    case GRANTED = 1;
    case WITHDRAWN = 2;

    public function label(): string
    {
        return match ($this) {
            self::GRANTED => 'Granted',
            self::WITHDRAWN => 'Withdrawn',
        };
    }
}
