<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\User\Output;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UserOutput
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Assert\NotNull, Assert\Uuid]
        public string $id,

        #[Assert\NotNull, Assert\Email]
        public string $email,

        public string|null $name = null,
    )
    {}
}
