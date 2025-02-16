<?php
declare(strict_types=1);

namespace App\State\Common\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use AutoMapper\AutoMapperInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class EntityToDtoStateProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: ItemProvider::class)]
        private ItemProvider $itemProvider,
        #[Autowire(service: CollectionProvider::class)]
        private CollectionProvider $collectionProvider,
        private AutoMapperInterface $mapper,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $toClass = $operation->getOutput()['class'] ?? $operation->getClass();

        if ($operation instanceof CollectionOperationInterface) {
            $output = [];
            $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);

            foreach ($entities as $entity) {
                $output[] = $this->mapper->map(source: $entity, target: $toClass);
            }

            return $output;
        }

        return $this->mapper->map(
            source: $this->itemProvider->provide($operation, $uriVariables, $context),
            target: $toClass,
        );
    }
}
