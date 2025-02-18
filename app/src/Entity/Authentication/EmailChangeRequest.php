<?php
declare(strict_types=1);

namespace App\Entity\Authentication;

use App\Entity\Traits\WithTimestamps;
use App\Entity\Traits\WithUuid;
use App\Repository\Authentication\EmailChangeRequestRepository;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'change_email_requests', schema: 'authentication')]
#[ORM\Entity(repositoryClass: EmailChangeRequestRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_CHANGE_EMAIL_REQUEST', fields: ['user', 'oldEmail', 'newEmail'])]
#[ORM\HasLifecycleCallbacks]
class EmailChangeRequest
{
    use WithUuid, WithTimestamps;

    public const string STATUS_PENDING = 'penging';
    public const string STATUS_CONFIRMED = 'confirmed';
    public const string STATUS_CANCELLED = 'cancelled';

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $oldEmail = null;

    #[ORM\Column(length: 255)]
    private ?string $newEmail = null;

    #[ORM\Column(length: 128)]
    private ?string $oldEmailHash = null;

    #[ORM\Column(length: 128)]
    private ?string $newEmailHash = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?CarbonInterface $expiredAt = null;


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getOldEmail(): ?string
    {
        return $this->oldEmail;
    }

    public function setOldEmail(string $oldEmail): static
    {
        $this->oldEmail = $oldEmail;
        return $this;
    }

    public function getNewEmail(): ?string
    {
        return $this->newEmail;
    }

    public function setNewEmail(string $newEmail): static
    {
        $this->newEmail = $newEmail;
        return $this;
    }

    public function getOldEmailHash(): ?string
    {
        return $this->oldEmailHash;
    }

    public function setOldEmailHash(string $oldEmailHash): static
    {
        $this->oldEmailHash = $oldEmailHash;
        return $this;
    }

    public function getNewEmailHash(): ?string
    {
        return $this->newEmailHash;
    }

    public function setNewEmailHash(string $newEmailHash): static
    {
        $this->newEmailHash = $newEmailHash;
        return $this;
    }

    public function getExpiredAt(): ?CarbonInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?CarbonInterface $expiredAt): static
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }
}
