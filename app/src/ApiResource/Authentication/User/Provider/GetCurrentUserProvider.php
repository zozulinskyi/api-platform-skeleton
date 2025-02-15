<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\User\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Authentication\User\Output\UserOutput;
use App\Entity\Authentication\User;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<UserOutput>
 */
final readonly class GetCurrentUserProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserOutput
    {
        /** @var User $user **/
        $user = $this->security->getUser();

        return new UserOutput(
            id: $user->getId()->toString(),
            email: $user->getEmail(),
            name: $user->getName(),
        );
    }
}
