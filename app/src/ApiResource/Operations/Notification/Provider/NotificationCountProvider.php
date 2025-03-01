<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\Notification\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Output\CountOutput;
use App\Repository\Notification\NotificationRepository;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class NotificationCountProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private NotificationRepository $notificationRepository,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        return new CountOutput(
            $this->notificationRepository->getUserNotificationCount(
                $this->security->getUser(),
            ),
        );
    }
}
