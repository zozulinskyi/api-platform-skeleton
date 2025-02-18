<?php
declare(strict_types=1);

namespace App\Repository\Authentication;

use App\Entity\Authentication\Code;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Code>
 */
final class CodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    public function generate(string $login, string $hash): Code
    {
        $entity = $this->findOneBy(['login' => $login]);

        if (is_null($entity)) {
            $entity = (new Code())->setLogin(login: $login);

            $this->getEntityManager()->persist($entity);
        }

        $entity->setHash(hash: $hash);
        $entity->setExpiredAt(expiredAt: Carbon::now()->addMinutes(value: 10));

        $this->getEntityManager()->flush();

        return $entity;
    }

    public function clearByLogin(string $login): void
    {
        $this->createQueryBuilder(alias: 'c')
            ->delete()
            ->where(predicates: 'c.login = :login')
            ->setParameter(key: 'login', value: $login)
            ->getQuery()
            ->execute();
    }
}
