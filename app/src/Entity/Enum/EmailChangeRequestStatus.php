<?php
declare(strict_types=1);

namespace App\Entity\Enum;

enum EmailChangeRequestStatus: string
{
    case PENDING = 'penging';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}
