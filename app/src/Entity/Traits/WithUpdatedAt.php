<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait WithUpdatedAt
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['with:updatedAt', 'with:timestamps'])]
    protected CarbonInterface $updatedAt;

    public function getUpdatedAt(): CarbonInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(CarbonInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PreUpdate, ORM\PrePersist]
    public function touchUpdatedAt(): void
    {
        $this->setUpdatedAt(Carbon::now());
    }
}
