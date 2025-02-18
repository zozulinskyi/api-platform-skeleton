<?php
declare(strict_types=1);

namespace App\Entity\Authentication;

use App\Entity\Traits\WithCreatedAt;
use App\Entity\Traits\WithUuid;
use App\Repository\Authentication\CodeRepository;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'codes', schema: 'authentication')]
#[ORM\Entity(repositoryClass: CodeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Code
{
    use WithUuid, WithCreatedAt;

    #[ORM\Column(length: 128, unique: true)]
    private ?string $login = null;

    #[ORM\Column(length: 128)]
    private ?string $hash = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?CarbonInterface $expiredAt = null;


    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;
        return $this;
    }

    public function getExpiredAt(): ?CarbonInterface
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(CarbonInterface $expiredAt): static
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }
}
