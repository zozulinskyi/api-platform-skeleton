<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Session\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Authentication\Session\Output\SessionOutput;
use App\Repository\Authentication\SessionRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<SessionOutput[]>
 */
final readonly class SessionListProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private Pagination $pagination,
        private SessionRepository $sessionRepository,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        [$page, , $limit] = $this->pagination->getPagination($operation, $context);
        $sessions = $this->sessionRepository->getUserSessions(user: $this->security->getUser(), page: $page, limit: $limit);

        foreach ($sessions as $session) {
            yield new SessionOutput(
                id: $session->getId()->toString(),
                ipAddress: $session->getIp(),
                userAgent: $session->getUserAgent(),
                lastAccessAt: $session->getLastAccessAt(),
            );
        }

        // todo: we forgot about pagination result...
    }
}
