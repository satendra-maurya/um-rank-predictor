<?php

namespace App\Enums;

enum ExamCycleStatus: string
{
    case DRAFT = 'DRAFT';
    case UPCOMING = 'UPCOMING';
    case ACTIVE = 'ACTIVE';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';
    case ARCHIVED = 'ARCHIVED';
}
