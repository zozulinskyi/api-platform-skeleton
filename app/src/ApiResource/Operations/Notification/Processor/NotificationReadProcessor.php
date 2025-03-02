<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\Notification\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Authentication\User;
use App\Entity\Notification\Notification;
use App\Repository\Notification\NotificationRepository;
use App\Repository\Notification\NotificationUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final readonly class NotificationReadProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private NotificationRepository $notificationRepository,
        private NotificationUserRepository $notificationUserRepository,
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        /** @var User $user **/
        $user = $this->security->getUser();

        if ($operation instanceof Patch) {
            $this->processSingleNotification(user: $user, notification: $uriVariables['id']);
        }
        if ($operation instanceof Post) {
            $this->processMultipleNotifications(user: $user);
        }

        $this->entityManager->flush();
    }

    private function processSingleNotification(User $user, Notification|string $notification): void
    {
        $notification = is_string($notification)
            ? $this->notificationRepository->getSingleUserNotification(user: $user, notificationId: $notification)
            : $notification;

        if (is_null($notification)) {
            throw new AccessDeniedHttpException(message: 'You does not have access for this notification');
        }

        $this->notificationUserRepository->assignAndRead($notification, $user);
    }

    private function processMultipleNotifications(User $user): void
    {
        $notifications = $this->notificationRepository->getAllUnreadUserNotifications($user);

        foreach ($notifications as $notification) {
            $this->processSingleNotification(user: $user, notification: $notification);
        }
    }
}
