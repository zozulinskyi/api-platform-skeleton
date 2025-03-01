<?php
declare(strict_types=1);

namespace App\Repository\Notification;

use App\Component\Notifier\Database\DatabaseNotificationMessage;
use App\Entity\Authentication\User;
use App\Entity\Notification\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function getUserNotifications(User $user, int $page, int $limit, bool $onlyUnread = false): Paginator
    {
        $queryBuilder = $this->getUserNotificationsQueryBuilder($user, $onlyUnread);

        $queryBuilder->orderBy(sort: 'n.createdAt', order: 'DESC');
        $queryBuilder->setFirstResult(firstResult: ($page - 1) * $limit);
        $queryBuilder->setMaxResults(maxResults: $limit);

        $paginator = new Paginator(query: $queryBuilder, fetchJoinCollection: false);
        $paginator->setUseOutputWalkers(useOutputWalkers: false);

        return $paginator;
    }

    public function getUserNotificationCount(User $user, bool $onlyUnread = true): int
    {
        $queryBuilder = $this->getUserNotificationsQueryBuilder($user, $onlyUnread);

        $queryBuilder->distinct();
        $queryBuilder->select(select: 'count(n.id)');
        $queryBuilder->groupBy(groupBy: 'n.id');

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    private function getUserNotificationsQueryBuilder(User $user, bool $onlyUnread): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'n')
            ->select(select: ['n.id', 'n.subject', 'n.content', 'n.importance', 'n.createdAt', 'nu.readAt'])
            ->leftJoin(join: 'n.users', alias: 'nu', conditionType: 'WITH', condition: 'nu.user = :user')
            ->where(predicates: 'nu.id is null or nu.user = :user')
            ->setParameter(key: 'user', value: $user);

        if ($onlyUnread) {
            $queryBuilder->andWhere('nu.readAt is null');
        }

        return $queryBuilder;
    }
}
