<?php
declare(strict_types=1);

namespace App\Entity\Authentication;

use App\Entity\Enum\EmailChangeRequestStatus;
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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255, enumType: EmailChangeRequestStatus::class)]
    private ?EmailChangeRequestStatus $status = null;

    #[ORM\Column(length: 255)]
    private ?string $oldEmail = null;

    #[ORM\Column(length: 255)]
    private ?string $newEmail = null;

    #[ORM\Column(length: 128)]
    private ?string $oldEmailSecretCode = null;

    #[ORM\Column(length: 128)]
    private ?string $newEmailSecretCode = null;

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

    public function getStatus(): ?EmailChangeRequestStatus
    {
        return $this->status;
    }

    public function setStatus(EmailChangeRequestStatus $status): static
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

    public function getOldEmailSecretCode(): ?string
    {
        return $this->oldEmailSecretCode;
    }

    public function setOldEmailSecretCode(?string $oldEmailSecretCode): static
    {
        $this->oldEmailSecretCode = $oldEmailSecretCode;
        return $this;
    }

    public function getNewEmailSecretCode(): ?string
    {
        return $this->newEmailSecretCode;
    }

    public function setNewEmailSecretCode(?string $newEmailSecretCode): static
    {
        $this->newEmailSecretCode = $newEmailSecretCode;
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
