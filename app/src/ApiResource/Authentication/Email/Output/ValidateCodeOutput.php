<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Email\Output;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ValidateCodeOutput
{
    public function __construct(
        #[Assert\NotNull]
        public string $token,
    )
    {}
}
