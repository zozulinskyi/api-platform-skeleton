<?php
declare(strict_types=1);

namespace App\ApiResource\Output;

use Symfony\Component\Validator\Constraints as Assert;

final class CountOutput
{
    public function __construct(
        #[Assert\NotNull, Assert\PositiveOrZero]
        public int $count = 0,
    )
    {}
}
