<?php
declare(strict_types=1);

namespace App\Entity\Authentication;

use App\Entity\Traits\WithCreatedAt;
use App\Entity\Traits\WithUuid;
use App\Repository\Authentication\SessionRepository;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'sessions', schema: 'authentication')]
#[ORM\Entity(repositoryClass: SessionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Session
{
    use WithUuid, WithCreatedAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    #[ORM\Column(length: 255)]
    private ?string $ip = null;

    #[ORM\Column(length: 255)]
    private ?string $userAgent = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?CarbonInterface $lastAccessAt = null;


    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): static
    {
        $this->ip = $ip;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): static
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getLastAccessAt(): ?CarbonInterface
    {
        return $this->lastAccessAt;
    }

    public function setLastAccessAt(CarbonInterface $lastAccessAt): static
    {
        $this->lastAccessAt = $lastAccessAt;
        return $this;
    }
}
