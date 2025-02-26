<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\Session\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\PaginatorInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Operations\Session\Output\SessionOutput;
use App\EventListener\Auhtentication\JwtTokenListener;
use AutoMapper\AutoMapperInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @implements ProviderInterface<SessionOutput[]>
 */
final readonly class SessionListProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private AutoMapperInterface $mapper,
        #[Autowire(service: CollectionProvider::class)]
        private CollectionProvider $collectionProvider,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): PaginatorInterface
    {
        $sessions = [];
        $paginator = $this->collectionProvider->provide($operation, $uriVariables, $context);
        $currentSessionId = $this->security->getToken()->getAttribute(name: JwtTokenListener::SESSION_KEY);

        foreach ($paginator as $session) {
            $isCurrentSession = $currentSessionId === $session->getId()->toString();

            $output = (new SessionOutput())->setIsCurrentSession($isCurrentSession);
            $sessions[] = $this->mapper->map(source: $session, target: $output, context: $context);
        }

        return new TraversablePaginator(
            traversable: new \ArrayIterator($sessions),
            currentPage: $paginator->getCurrentPage(),
            itemsPerPage: $paginator->getItemsPerPage(),
            totalItems: $paginator->getTotalItems(),
        );
    }
}
