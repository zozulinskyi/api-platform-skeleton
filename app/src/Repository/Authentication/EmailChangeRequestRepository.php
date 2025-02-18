<?php
declare(strict_types=1);

namespace App\Repository\Authentication;

use App\Entity\Authentication\EmailChangeRequest;
use App\Entity\Authentication\User;
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

    public function generate(User $user, string $newEmail, string $oldEmailHash, string $newEmailHash): EmailChangeRequest
    {
        $entity = $this->findOneBy(['user' => $user, 'oldEmail' => $user->getEmail(), 'newEmail' => $newEmail]);

        if (is_null($entity)) {
            $entity = (new EmailChangeRequest())
                ->setUser($user)
                ->setOldEmail($user->getEmail())
                ->setNewEmail($newEmail);

            $this->getEntityManager()->persist($entity);
        }

        $entity->setStatus(EmailChangeRequest::STATUS_PENDING);
        $entity->setExpiredAt(Carbon::now()->addDay());
        $entity->setOldEmailHash($oldEmailHash);
        $entity->setNewEmailHash($newEmailHash);

        $this->getEntityManager()->flush();

        return $entity;
    }
}
