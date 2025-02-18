<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\User\Output;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ChangeEmailOutput
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Assert\NotNull, Assert\Uuid]
        public string $id,
    )
    {}
}
