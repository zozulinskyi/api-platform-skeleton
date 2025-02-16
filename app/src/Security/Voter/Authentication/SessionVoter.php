<?php
declare(strict_types=1);

namespace App\Security\Voter\Authentication;

use App\Entity\Authentication\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class SessionVoter extends Voter
{
    public const string DELETE = 'SESSION_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::DELETE]) && $subject instanceof Session;
    }

    /**
     * @param Session $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::DELETE => $subject->getId()->toString() !== $token->getAttribute(name: 'sessionId'),
            default => false,
        };
    }
}
