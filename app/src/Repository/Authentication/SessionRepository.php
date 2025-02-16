<?php
declare(strict_types=1);

namespace App\Repository\Authentication;

use App\Entity\Authentication\Session;
use App\Entity\Authentication\User;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Session>
 */
final class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function generate(User $user, Request $request): Session
    {
        $session = (new Session())
            ->setIp(ip: $request->getClientIp())
            ->setUser(user: $user)
            ->setUserAgent(userAgent: $request->headers->get(key: 'User-Agent', default: 'undefined'))
            ->setLastAccessAt(lastAccessAt: Carbon::now());

        $this->getEntityManager()->persist($session);
        $this->getEntityManager()->flush();

        return $session;
    }

    public function update(Request $request, string $sessionId): void
    {
        $this->createQueryBuilder(alias: 's')
            ->update()
            ->set(key: 's.lastAccessAt', value: ':lastAccessAt')
            ->set(key: 's.userAgent', value: ':userAgent')
            ->set(key: 's.ip', value: ':ip')
            ->where(predicates: 's.id = :sessionId')
            ->setParameter(key: 'sessionId', value: $sessionId)
            ->setParameter(key: 'lastAccessAt', value: Carbon::now())
            ->setParameter(key: 'userAgent', value: $request->headers->get(key: 'User-Agent', default: 'undefined'))
            ->setParameter(key: 'ip', value: $request->getClientIp())
            ->getQuery()
            ->execute();
    }

    public function exists(string $sessionId): bool
    {
        return (bool)$this->count(['id' => $sessionId]);
    }

    /**
     * @return Paginator<Session>
     */
    public function getUserSessions(User $user, int $page = 1, int $limit = 10): Paginator
    {
        $query = $this->createQueryBuilder(alias: 's')
            ->where(predicates: 's.user = :user')
            ->setParameter(key: 'user', value: $user)
            ->setMaxResults(maxResults: $limit)
            ->setFirstResult(firstResult: ($page - 1) * $limit);

        return new Paginator(query: $query, fetchJoinCollection: false);
    }
}
