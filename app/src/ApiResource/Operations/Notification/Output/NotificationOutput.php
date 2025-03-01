<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\Notification\Output;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class NotificationOutput
{
    #[ApiProperty(identifier: true)]
    #[Assert\NotNull, Assert\Uuid]
    public string $id;

    #[Assert\NotNull]
    public string $subject;

    #[Assert\NotNull]
    public string $content;

    #[Assert\NotNull]
    public string $importance;

    #[Assert\NotNull, Assert\DateTime]
    public DateTimeInterface $createdAt;

    #[Assert\DateTime]
    public null|DateTimeInterface $readAt = null;
}
