<?php
declare(strict_types=1);

namespace App\Entity\Traits;

trait WithTimestamps
{
    use WithCreatedAt, WithUpdatedAt;
}
