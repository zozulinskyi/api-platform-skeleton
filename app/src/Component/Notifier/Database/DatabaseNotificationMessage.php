<?php
declare(strict_types=1);

namespace App\Component\Notifier\Database;

use App\Component\Notifier\Notification;
use Symfony\Component\Notifier\Message\FromNotificationInterface;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\MessageOptionsInterface;
use Symfony\Component\Notifier\Recipient\NoRecipient;

final readonly class DatabaseNotificationMessage implements MessageInterface, FromNotificationInterface
{
    public function __construct(
        private null|string $userId,
        private string $subject,
        private string $content,
        private string $importance,
        private Notification $notification,
    )
    {}

    public static function fromNoticiation(Notification $notification, DatabaseRecipientInterface|NoRecipient $recipient): self
    {
        return new self(
            userId: $recipient instanceof NoRecipient ? null : $recipient->getUserId(),
            subject: $notification->getSubject(),
            content: $notification->getContent(),
            importance: $notification->getImportance(),
            notification: $notification,
        );
    }

    public function getRecipientId(): ?string
    {
        return $this->userId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getOptions(): ?MessageOptionsInterface
    {
        return null;
    }

    public function getTransport(): ?string
    {
        return null;
    }

    public function getImportance(): string
    {
        return $this->importance;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }
}
