<?php

namespace App\Enums;

enum SubmissionTrustStatus: int
{
    case TRUSTED = 1;
    case SUSPICIOUS = 2;
    case REJECTED = 3;
    case PENDING_AUDIT = 4;

    public function label(): string
    {
        return match ($this) {
            self::TRUSTED => 'Trusted',
            self::SUSPICIOUS => 'Suspicious',
            self::REJECTED => 'Rejected',
            self::PENDING_AUDIT => 'Pending Audit',
        };
    }
}
