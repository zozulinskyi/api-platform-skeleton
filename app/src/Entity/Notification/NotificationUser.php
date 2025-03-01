<?php
declare(strict_types=1);

namespace App\Entity\Notification;

use App\Entity\Authentication\User;
use App\Entity\Traits\WithUuid;
use App\Repository\Notification\NotificationUserRepository;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'notification_users', schema: 'notification')]
#[ORM\Entity(repositoryClass: NotificationUserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class NotificationUser
{
    use WithUuid;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Notification $notification = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?CarbonInterface $readAt = null;


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }

    public function setNotification(?Notification $notification): static
    {
        $this->notification = $notification;
        return $this;
    }

    public function getReadAt(): ?CarbonInterface
    {
        return $this->readAt;
    }

    public function setReadAt(?CarbonInterface $readAt): static
    {
        $this->readAt = $readAt;
        return $this;
    }
}
