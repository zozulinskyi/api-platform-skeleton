<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\Notification\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Operations\Notification\Output\NotificationOutput;
use App\Repository\Notification\NotificationRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<NotificationOutput[]>
 */
final readonly class NotificationListProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private Pagination $pagination,
        private NotificationRepository $notificationRepository,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        $user = $this->security->getUser();
        [$page, , $limit] = $this->pagination->getPagination($operation, $context);

        $onlyUnread = str_contains(haystack: $context['request_uri'], needle: 'unread');
        $doctrinePaginator = $this->notificationRepository->getUserNotifications($user, $page, $limit, $onlyUnread);

        return new Paginator($doctrinePaginator);
    }
}
