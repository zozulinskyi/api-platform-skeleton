<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Session\Output;

use ApiPlatform\Metadata\ApiProperty;
use App\Entity\Authentication\Session;
use AutoMapper\Attribute\MapFrom;
use AutoMapper\Attribute\Mapper;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[Mapper(source: Session::class)]
final class SessionOutput
{
    #[MapFrom(property: 'id')]
    #[ApiProperty(identifier: true)]
    #[Assert\NotNull, Assert\Uuid]
    public string $id;

    #[MapFrom(property: 'ip')]
    #[Assert\NotNull, Assert\Ip]
    public string $ipAddress;

    #[MapFrom(property: 'userAgent')]
    #[Assert\NotNull]
    public string $userAgent;

    #[MapFrom(property: 'lastAccessAt')]
    #[Assert\NotNull, Assert\DateTime]
    public DateTimeInterface $lastAccessAt;

    #[MapFrom(ignore: true)]
    #[Assert\NotNull]
    public bool $isCurrentSession = false;


    public function setIsCurrentSession(bool $isCurrentSession): self
    {
        $this->isCurrentSession = $isCurrentSession;
        return $this;
    }
}
