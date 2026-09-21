<?php

namespace App\Enums;

enum ExamAuthorityLevel: string
{
    case CENTRAL = 'CENTRAL';
    case STATE = 'STATE';
    case OTHER = 'OTHER';
}
