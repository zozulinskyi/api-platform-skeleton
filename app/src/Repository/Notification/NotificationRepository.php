<?php
declare(strict_types=1);

namespace App\Repository\Notification;

use App\Component\Notifier\Database\DatabaseNotificationMessage;
use App\Entity\Notification\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function findOrCreate(DatabaseNotificationMessage $message): Notification
    {
        $notification = $this->findOneBy(['id' => $message->getNotification()->getUuid()]);

        if (is_null($notification)) {
            $notification = (new Notification())
                ->setId(id: $message->getNotification()->getUuid())
                ->setSubject(subject: $message->getSubject())
                ->setContent(content: $message->getContent())
                ->setImportance(importance: $message->getImportance());

            $this->getEntityManager()->persist($notification);
        }

        return $notification;
    }
}
