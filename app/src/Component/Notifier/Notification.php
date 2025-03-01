<?php
declare(strict_types=1);

namespace App\Component\Notifier;

use Symfony\Component\Notifier\Notification\Notification as BaseNotification;
use Symfony\Component\Uid\Uuid;

final class Notification extends BaseNotification
{
    private Uuid $uuid;

    public function __construct(string $subject = '', array $channels = [])
    {
        parent::__construct($subject, $channels);

        $this->uuid = Uuid::v7();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}
