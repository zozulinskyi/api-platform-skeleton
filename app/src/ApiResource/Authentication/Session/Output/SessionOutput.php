<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Session\Output;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Authentication\Session;
use AutoMapper\Attribute\MapFrom;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Asserts;

final readonly class SessionOutput
{
    public function __construct(
        #[MapFrom(source: Session::class, property: 'id')]
        #[ApiProperty(identifier: true)]
        #[Asserts\NotNull, Asserts\Uuid]
        public string $id,

        #[MapFrom(source: Session::class, property: 'ip')]
        #[Asserts\NotNull, Asserts\Ip]
        public string $ipAddress,

        #[MapFrom(source: Session::class, property: 'userAgent')]
        #[Asserts\NotNull]
        public string $userAgent,

        #[MapFrom(source: Session::class, property: 'lastAccessAt')]
        #[Asserts\DateTime]
        public DateTimeInterface $lastAccessAt,
    )
    {}
}
