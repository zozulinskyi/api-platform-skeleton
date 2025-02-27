<?php
declare(strict_types=1);

namespace App\Repository\Authentication;

use App\Entity\Authentication\EmailChangeRequest;
use App\Entity\Authentication\User;
use App\Entity\Enum\EmailChangeRequestStatus;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmailChangeRequest>
 */
final class EmailChangeRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailChangeRequest::class);
    }

    public function generate(User $user, string $newEmail, string $oldEmailSecretCode, string $newEmailSecretCode): EmailChangeRequest
    {
        $entity = $this->findOneBy(['user' => $user, 'oldEmail' => $user->getEmail(), 'newEmail' => $newEmail]);

        if (is_null($entity)) {
            $entity = (new EmailChangeRequest())
                ->setUser($user)
                ->setOldEmail($user->getEmail())
                ->setNewEmail($newEmail);

            $this->getEntityManager()->persist($entity);
        }

        $entity->setStatus(status: EmailChangeRequestStatus::PENDING);
        $entity->setExpiredAt(expiredAt: Carbon::now()->addDay());
        $entity->setOldEmailSecretCode(oldEmailSecretCode: $oldEmailSecretCode);
        $entity->setNewEmailSecretCode(newEmailSecretCode: $newEmailSecretCode);

        $this->getEntityManager()->flush();

        return $entity;
    }

    public function cancelOverdueRequests(): void
    {
        $this->createQueryBuilder(alias: 'r')
            ->update()
            ->set(key: 'r.status', value: ':cancelled')
            ->where(predicates: 'r.status != :pending')
            ->andWhere("r.expiredAt < DATE_SUB(NOW(), 1, 'DAY')")
            ->setParameter(key: 'pending', value: EmailChangeRequestStatus::PENDING)
            ->setParameter(key: 'cancelled', value: EmailChangeRequestStatus::CANCELLED)
            ->getQuery()
            ->execute();
    }
}
