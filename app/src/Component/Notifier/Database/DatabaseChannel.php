<?php
declare(strict_types=1);

namespace App\Component\Notifier\Database;

use App\Repository\Authentication\UserRepository;
use App\Repository\Notification\NotificationRepository;
use App\Repository\Notification\NotificationUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Notifier\Channel\ChannelInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

#[AsAlias(id: 'notifier.channel.database')]
#[AutoconfigureTag(name: 'notifier.channel', attributes: ['channel' => 'database'])]
final readonly class DatabaseChannel implements ChannelInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private NotificationRepository $notificationRepository,
        private NotificationUserRepository $notificationUserRepository,
    )
    {}

    /**
     * @param \App\Component\Notifier\Notification $notification
     * @param DatabaseRecipientInterface $recipient
     * @param string|null $transportName
     * @return void
     */
    public function notify(Notification $notification, RecipientInterface $recipient, ?string $transportName = null): void
    {
        $message = DatabaseNotificationMessage::fromNoticiation($notification, $recipient);
        $notificationEntity = $this->notificationRepository->findOrCreate(message: $message);

        if ($message->getRecipientId()) {
            $user = $this->userRepository->find($message->getRecipientId());

            if (isset($user)) {
                $this->notificationUserRepository->assign($notificationEntity, $user);
            }
        }

        $this->entityManager->flush();
    }

    public function supports(Notification $notification, RecipientInterface $recipient): bool
    {
        return true;
    }
}
