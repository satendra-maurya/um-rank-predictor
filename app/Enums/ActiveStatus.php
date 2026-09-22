<?php

namespace App\Enums;

enum ActiveStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
    case COMING_SOON = 2;

    public function label(): string
    {
        return match ($this) {
            self::INACTIVE => 'Inactive',
            self::ACTIVE => 'Active',
            self::COMING_SOON => 'Coming Soon',
        };
    }
}
