<?php
declare(strict_types=1);

namespace App\Repository\Authentication;

use App\Entity\Authentication\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
final class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOrCreate(string $email): User
    {
        $user = $this->findOneBy(['email' => $email]);

        if (is_null($user)) {
            $user = (new User())->setEmail(email: $email);

            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }

        return $user;
    }
}
