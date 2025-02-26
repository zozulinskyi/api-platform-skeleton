<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\CurrentUser\Output;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Authentication\User;
use AutoMapper\Attribute\MapFrom;
use AutoMapper\Attribute\Mapper;
use Symfony\Component\Validator\Constraints as Assert;

#[Mapper(source: User::class)]
final readonly class CurrentUserOutput
{
    public function __construct(
        #[MapFrom(property: 'id')]
        #[ApiProperty(identifier: true)]
        #[Assert\NotNull, Assert\Uuid]
        public string $id,

        #[MapFrom(property: 'email')]
        #[Assert\NotNull, Assert\Email]
        public string $email,

        #[MapFrom(property: 'name')]
        public string|null $name = null,
    )
    {}
}
