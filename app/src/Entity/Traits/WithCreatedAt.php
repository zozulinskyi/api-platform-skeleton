<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait WithCreatedAt
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['with:createdAt', 'with:timestamps'])]
    protected CarbonInterface $createdAt;

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[ORM\PrePersist]
    public function touchCreatedAt(): void
    {
        $this->setCreatedAt(Carbon::now());
    }
}
