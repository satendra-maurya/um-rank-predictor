<?php

namespace App\Enums;

enum SubmissionTrustStatus: string
{
    case TRUSTED = 'TRUSTED';
    case SUSPICIOUS = 'SUSPICIOUS';
    case REJECTED = 'REJECTED';
    case PENDING_AUDIT = 'PENDING_AUDIT';
}
