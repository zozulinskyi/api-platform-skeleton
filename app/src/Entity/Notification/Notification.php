<?php
declare(strict_types=1);

namespace App\Entity\Notification;

use App\Entity\Traits\WithTimestamps;
use App\Repository\Notification\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Table(name: 'notifications', schema: 'notification')]
#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Notification
{
    use WithTimestamps;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(length: 64)]
    private ?string $importance = null;

    /**
     * @var Collection<int, NotificationUser>
     */
    #[ORM\OneToMany(mappedBy: 'notification', targetEntity: NotificationUser::class, cascade: ['persist', 'remove'])]
    private Collection $users;


    public function __construct()
    {
        $this->users = new ArrayCollection();
    }


    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getImportance(): ?string
    {
        return $this->importance;
    }

    public function setImportance(string $importance): static
    {
        $this->importance = $importance;
        return $this;
    }

    /**
     * @return Collection<int, NotificationUser>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(NotificationUser $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setNotification($this);
        }

        return $this;
    }

    public function removeUser(NotificationUser $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getNotification() === $this) {
                $user->setNotification(null);
            }
        }

        return $this;
    }
}
