<?php
declare(strict_types=1);

namespace App\ContentNegotiation\Hateoas\Serializer;

use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\ResourceClassResolverInterface;
use ApiPlatform\Serializer\AbstractCollectionNormalizer;
use AutoMapper\AutoMapperInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class CollectionNormalizer extends AbstractCollectionNormalizer
{
    public const string FORMAT = 'json';

    public function __construct(
        private readonly AutoMapperInterface $mapper,
        ResourceClassResolverInterface $resourceClassResolver,
        #[Autowire(value: '%api_platform.collection.pagination.page_parameter_name%')]
        string $pageParameterName,
        ?ResourceMetadataCollectionFactoryInterface $resourceMetadataFactory = null,
    )
    {
        parent::__construct($resourceClassResolver, $pageParameterName, $resourceMetadataFactory);
    }

    protected function getPaginationData(iterable $object, array $context = []): array
    {
        [$paginator, , $currentPage, $itemsPerPage, , , $totalItems] = $this->getPaginationConfig($object, $context);

        if ($paginator === false) {
            return [];
        }

        $data = [];
        $data['meta']['totalItems'] = (int)$totalItems;
        $data['meta']['currentPage'] = (int)$currentPage;
        $data['meta']['itemsPerPage'] = (int)$itemsPerPage;

        return $data;
    }

    protected function getItemsData(iterable $object, ?string $format = null, array $context = []): array
    {
        $data = ['data' => []];
        $transformTo = $context['output']['class'] ?? $context['resource_class'];

        foreach ($object as $obj) {
            $transformedObject = $transformTo ? $this->mapper->map(source: $obj, target: $transformTo) : $obj;
            $data['data'][] = $this->normalizer->normalize($transformedObject, $format, $context);
        }

        return $data;
    }
}
