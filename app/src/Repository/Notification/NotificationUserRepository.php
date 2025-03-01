<?php
declare(strict_types=1);

namespace App\Repository\Notification;

use App\Entity\Authentication\User;
use App\Entity\Notification\Notification;
use App\Entity\Notification\NotificationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationUser>
 */
class NotificationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationUser::class);
    }

    public function assign(Notification $notification, User $user): NotificationUser
    {
        $entity = $this->findOneBy(['user' => $user, 'notification' => $notification]);

        if (is_null($entity)) {
            $entity = (new NotificationUser())
                ->setNotification(notification: $notification)
                ->setUser(user: $user);

            $this->getEntityManager()->persist($entity);
        }

        return $entity;
    }
}
