<?php
declare(strict_types=1);

namespace App\Repository\Authentication;

use App\Entity\Authentication\Code;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\ByteString;

/**
 * @extends ServiceEntityRepository<Code>
 */
final class CodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Code::class);
    }

    public function generate(string $login): Code
    {
        $code = ByteString::fromRandom(length: 6, alphabet: '1234567890')->toString();
        $expiredAt = Carbon::now()->addMinutes(value: 10)->toDateTimeImmutable();
        $hashedCode = password_hash(password: $code, algo: PASSWORD_BCRYPT);

        $entity = $this->findOneBy(['login' => $login]);

        if (is_null($entity)) {
            $entity = (new Code())->setLogin(login: $login);

            $this->getEntityManager()->persist($entity);
        }

        $entity->setCode(code: $code);
        $entity->setHash(hash: $hashedCode);
        $entity->setExpiredAt(expiredAt: $expiredAt);

        $this->getEntityManager()->flush();

        return $entity;
    }
}
