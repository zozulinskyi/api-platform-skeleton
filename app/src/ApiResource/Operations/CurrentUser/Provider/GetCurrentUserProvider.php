<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\CurrentUser\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Operations\CurrentUser\Output\CurrentUserOutput;
use AutoMapper\AutoMapperInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<CurrentUserOutput>
 */
final readonly class GetCurrentUserProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private AutoMapperInterface $mapper,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        return $this->mapper->map(source: $this->security->getUser(), target: CurrentUserOutput::class);
    }
}
