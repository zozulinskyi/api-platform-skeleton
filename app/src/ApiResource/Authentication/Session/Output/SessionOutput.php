<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Session\Output;

use ApiPlatform\Metadata\ApiProperty;
use Carbon\CarbonInterface;
use Symfony\Component\Validator\Constraints as Asserts;

final readonly class SessionOutput
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Asserts\NotNull, Asserts\Uuid]
        public string $id,

        #[Asserts\NotNull, Asserts\Ip]
        public string $ipAddress,

        #[Asserts\NotNull]
        public string $userAgent,

        #[Asserts\DateTime]
        public CarbonInterface $lastAccessAt,
    )
    {}
}
